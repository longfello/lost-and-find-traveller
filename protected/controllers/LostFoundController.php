<?php


class LostFoundController extends AController {
  public $service_id = SERVICE_BURO_NAHODOK;
  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  public $layout = '//layouts/column2';
  public $pageTitle = "Единая информационная служба - Бюро находок";

  private $categories = null;

  /**
   * @return array action filters
   */
  public function filters() {
    return array('accessControl', // perform access control for CRUD operations
        'postOnly + delete', // we only allow deletion via POST request
        'DomainControl',);
  }

  public function filterDomainControl($filterChain) {
    if (Yii::app()->params['subdomain'] != SERVICE_BURO_NAHODOK) {
      throw new CHttpException(404, 'Страница не найдена');
    }
    $filterChain->run();
  }

  /**
   * Specifies the access control rules.
   * This method is used by the 'accessControl' filter.
   * @return array access control rules
   */
  public function actions() {
    return array(// captcha action renders the CAPTCHA image displayed on the contact page
        'captcha' => array('class' => 'CCaptchaAction', 'backColor' => 0xFFFFFF, 'maxLength' => 6, 'minLength' => 6, 'width' => 100, 'height' => 40,));
  }
  public function accessRules() {
    return array(
        array(
          'allow',  // allow all users to perform 'index' and 'view' actions
          'actions' => array('getPhone', 'create', 'index', 'view', 'captcha', 'sendemail', 'details'),
          'users' => array('*'),),
        array(
          'allow', // allow authenticated user to perform 'create' and 'update' actions
          'actions' => array('My', 'update', 'Del_my'),
          'users' => array('@'),),
        array(
          'allow', // allow admin user to perform 'admin' and 'delete' actions
          'actions' => array('admin', 'delete', 'toModerate', 'moderate'),
          'users' => array('admin', 'moderator'),),
        array(
          'deny',  // deny all users
          'users' => array('*'),),
    );
  }

  public function __construct($id,$module=null){
    parent::__construct($id,$module);
    $categoryStat = array();

    $command = "SELECT * FROM  `category`    ORDER BY FIND_IN_SET(title, 'Люди,Животные,Автомобили,Документы,Электроника,Ключи,Другое')";
    $command = Yii::app()->db->createCommand($command);
    $this->categories = $command->queryAll();

    if ($this->categories != NULL) {
      foreach ($this->categories as $cat) {
        $command = 'SELECT COUNT( * ) as `cnt`, `lost_or_found`
                                    FROM  `lost_found`
                                    WHERE category = ' . $cat['id_cat'] . ' AND status <> 0
                                    GROUP BY lost_or_found ';
        $command = Yii::app()->db->createCommand($command);
        $catStats = $command->queryAll();

        foreach ($catStats as $catStat) {
          if ($catStat['lost_or_found'] == 0) {
            $categoryStat[$cat['id_cat']]['count_lost'] = $catStat['cnt'];
          } else {
            $categoryStat[$cat['id_cat']]['count_found'] = $catStat['cnt'];
          }
          $categoryStat[$cat['id_cat']]['title'] = $cat['title'];
        }
      }
    }

    $this->right_column = $this->renderPartial('right_column', array(
        'categoryStat' => $categoryStat,
    ), true);
  }

  public function actionSendemail() {
    $captcha = Yii::app()->getController()->createAction("captcha");
    $code = $captcha->verifyCode;

    if (Yii::app()->request->isAjaxRequest) {
      $user = User::model()->find(array('condition' => 'id=:id', 'params' => array(':id' => $_POST['user'])));

      $response ['sign'] = 0;
      if ($code === $_REQUEST['captcha']) {
        $response ['sign'] = 1;
        $name = '=?UTF-8?B?' . base64_encode($_POST['name']) . '?=';
        $subject = '=?UTF-8?B?' . base64_encode("Вам пишут из сервиса Бюро находок") . '?=';
        $headers = "From: $name <{$_POST['sender']}>\r\n" . "Reply-To: {$user->email}\r\n" . "Sender: admin@infotoway.ru\r\n" . "MIME-Version: 1.0\r\n" . "Errors-to: admin@infotoway.ru\r\n" . "Content-Type: text/plain; charset=UTF-8";

        if (!mail($user->email, $subject, $_POST['text'], $headers)) {
          $response ['sign'] = 2;
        }
      }

      print json_encode($response);
      Yii::app()->end();
    }
  }
  public function actionMy() {
    $orders = array();
    if (!Yii::app()->user->isGuest) {
      $id = Yii::app()->user->id;

      $orders = LostFound::model()->findAll(array('condition' => "id_user = $id AND status=1", 'order' => 'id_lf DESC'));

      $count_orders = count($orders);
      if ($count_orders > 10) {
        $pages = (int)(($count_orders + 9) / 10);
        $current_page = $_GET['page'] ? $_GET['page'] : 1;
        $orders = array_slice($orders, 10 * ($current_page - 1), 10);
      }
    }

    $this->render('my', array('orders' => $orders, 'pages' => $pages

    ));
  }
  public function actionDel_my($id) {
    $order = $this->loadModel($id);

    $order->status = 0;
    $order->save();
    if (!isset($_GET['ajax'])) {
      $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
  }
  public function actionModerate($id) {
    $cu = Yii::app()->user;

    if (isset($id) && $id != NULL) {
      $o = $this->loadModel($id);
      $o->status = 1;
      $o->activecode = 1;
      $o->operator = $cu->id;
      $o->save(FALSE);
    } else if (isset($_POST['orders'])) {
      $Ids = implode(', ', $_POST['orders']);
      LostFound::model()->updateAll(array('status' => 1, 'activecode' => 1, 'operator' => $cu->id), 'id_lf IN (' . $Ids . ')');

    }
    $this->redirect(array('toModerate'));
  }
  /**
   * Displays a particular model.
   * @param integer $id the ID of the model to be displayed
   */
  public function actionView($id) {
    $this->render('view', array('model' => $this->loadModel($id),));
  }
  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate() {
    $model = new LostFound;

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if (isset($_POST['LostFound'])) {
      $model->attributes = $_POST['LostFound'];

      if ($model->save()) {

        if ($model->activecode) {
          $this->redirect(array('general/activate_order', 'module' => 'lostfound', 'id' => $model->id_lf));
        } else {
          $this->redirect(array('ru/order/complete'));
        }
      }
    }

    $cu = Yii::app()->user;
    $user_data = array();

    if ($cu->id && !$cu->checkAccess('operator')) {
      $user = User::model()->find(array('condition' => 'id=:id', 'params' => array(':id' => $cu->id)));
      $profile = $user->profile;
      if ($model->isNewRecord) {
        $user_data['first_name'] = $profile->first_name;
      }
      $user_data['email'] = $user->email;
    }

    $this->render('create', array('model' => $model, 'categories' => $this->categories, 'user_data' => $user_data,));
  }
  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id the ID of the model to be updated
   */
  public function actionUpdate($id) {
    $model = $this->loadModel($id);
    $cu = Yii::app()->user;
    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if (isset($_POST['LostFound'])) {

      $model->lost_or_found = $_POST['LostFound']['lost_or_found'];
      $model->category = $_POST['LostFound']['category'];
      $model->id_settlement = $_POST['LostFound']['id_settlement'];
      $model->place_disc = $_POST['LostFound']['place_disc'];
      $model->thing = $_POST['LostFound']['thing'];
      $model->date_lf = $_POST['LostFound']['date_lf'];
      $model->comment = $_POST['LostFound']['comment'];
      $model->phone = $_POST['LostFound']['phone'];

      if (!$cu->checkAccess('operator')) {
        $model->status = 0;
      }
      if ($model->save()) {
        $this->redirect(array('index', 'id_order' => $model->id_lf));
      }
    }


    if ($cu->id && !$cu->checkAccess('operator')) {
      $user = User::model()->find(array('condition' => 'id=:id', 'params' => array(':id' => $model->id_user)));
      $profile = $user->profile;
      $user_data['first_name'] = $profile->first_name;
      $user_data['email'] = $user->email;
    }


    $this->render('update', array('model' => $model, 'categories' => $this->categories, 'user_data' => $user_data,));
  }
  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($id) {
    if (isset($id) && $id != NULL) {
      $this->loadModel($id)->delete();
    } else if (isset($_POST['orders'])) {

      $Ids = implode(', ', $_POST['orders']);
      LostFound::model()->deleteAll('id_lf IN (' . $Ids . ')');

    }
    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    if (!isset($_GET['ajax'])) {
      $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
  }
  /**
   * Lists all models.
   */
  public function actionIndex() {
    $search = 0;
    $where = array('lf.status <> 0');
    $orders_ids = array();
    if (Yii::app()->request->isPostRequest || isset($_GET['search']) || isset($_GET['category']) || isset($_GET['type_order'])) {
      $search = 1;
      $type_order = Core::getPost('type_order',      Core::getGet('type_order',      Core::getSession('lf_filter__type_order',      false)));
      $category   = Core::getPost('category',        Core::getGet('category',        Core::getSession('lf_filter__category',        false)));
      $city_id    = Core::getPost('settlement_id',   Core::getGet('settlement_id',   Core::getSession('lf_filter__settlement_id',   false)));
      $city_name  = Core::getPost('settlement_name', Core::getGet('settlement_name', Core::getSession('lf_filter__settlement_name', false)));

      $city_id    = $city_name?$city_id:false;
      $type_order = $type_order?$type_order:1;

      $_SESSION['lf_filter__type_order']      = $type_order;
      $_SESSION['lf_filter__category']        = $category;
      $_SESSION['lf_filter__settlement_id']   = $city_id;
      $_SESSION['lf_filter__settlement_name'] = $city_name;

      switch ($type_order) {
        case 2:
          $where[] = " lf.lost_or_found = 0 ";
          break;
        case 3:
          $where[] = " lf.lost_or_found = 1 ";
          break;
      }

      if ($city_id)  { $where[] = " lf.id_settlement = " . $city_id;  }
      if ($category) { $where[] = " lf.category = "      . $category; }
    } else {
      unset($_SESSION['lf_filter__type_order']);
      unset($_SESSION['lf_filter__category']);
      unset($_SESSION['lf_filter__settlement_id']);
      unset($_SESSION['lf_filter__settlement_name']);
    }
    if (isset($_GET['id_order'])) { $where[] = " id_lf = " . $_GET['id_order']; }
    $where_str = ' WHERE ' . implode(' AND ', $where);

    $command = 'SELECT DISTINCT lf.id_lf FROM lost_found lf ' . $where_str . ' ORDER BY id_lf DESC';
    $command = Yii::app()->db->createCommand($command);
    $orders = $command->queryAll();

    $count_orders = count($orders);
    if ($count_orders > 10) {
      $pages = (int)(($count_orders + 9) / 10);
      $current_page = Core::getGet('page', 1, type_int);
      $orders = array_slice($orders, 10 * ($current_page - 1), 10);
    }
    foreach ($orders as $order) {
      $orders_ids[] = $order['id_lf'];
    }
    $orders_ids = implode(', ', $orders_ids);

    if ($orders_ids) {
      $orders = LostFound::model()->with('idCategory', 'idSaSettlement')->findAll(array('condition' => "id_lf IN ($orders_ids)", 'order' => 'id_lf DESC'));
    } else {
      $orders = array();
    }

    $data = array(
        'orders'       => $orders,
        'search'       => $search,
        'pages'        => isset($pages) ? $pages : NULL,
        'categories'   => $this->categories,
        'category'     => $category,
        'type_order'   => $type_order,
        'city_id'      => $city_id,
        'city_name'    => $city_name,
    );

    $this->render('index', $data);
  }

  public function actionToModerate() {
    $orders = LostFound::model()->findAll(array('order' => 'id_lf ASC', 'condition' => "status=0 AND activecode = 0"));
    $this->render('moderate', array('orders' => $orders,));
  }

  /**
   * Manages all models.
   */
  public function actionGetPhone() {
    if ($_POST) {
      $o = $this->loadModel($_POST['id_order']);
      print json_encode('<div class="phone">+' . $o->phone . '</div>');
      Yii::app()->end();
    }
  }

  public function actionAdmin() {
    $model = new LostFound('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['LostFound'])) {
      $model->attributes = $_GET['LostFound'];
    }

    $this->render('admin', array('model' => $model,));
  }

  public function actionDetails($id) {
    $order = LostFound::model()->findAll(array('condition' => "id_lf = $id "));
    $this->render('_details', array('order' => $order[0]));
  }

  /**
   * Performs the AJAX validation.
   * @param LostFound $model the model to be validated
   */
  protected function performAjaxValidation($model) {
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'lost-found-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }
  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return LostFound the loaded model
   * @throws CHttpException
   */
  public function loadModel($id) {
    $model = LostFound::model()->findByPk($id);
    if ($model === NULL) {
      throw new CHttpException(404, 'The requested page does not exist.');
    }
    return $model;
  }

}
