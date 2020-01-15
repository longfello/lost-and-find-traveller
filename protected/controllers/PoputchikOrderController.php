<?php

class PoputchikOrderController extends AController {
  public $service_id = SERVICE_POPUTCHIK;

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';
    public $flag = 1;
    public $pageTitle = "Единая информационная служба - Попутчик";

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
            'DomainControl',
        );
    }
    public function filterDomainControl($filterChain) {
        if (Yii::app()->params['subdomain'] != SERVICE_POPUTCHIK)
            $this->redirect("http://".SERVICE_POPUTCHIK.'.'.Yii::app()->params['domain'].Yii::app()->request->requestUri);
        //	throw new CHttpException(404, 'Страница не найдена');
        $filterChain->run();
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('add', 'index','details', 'view', 'create', 'getPhone', 'sendemail', 'captcha','CheckUser','testa', 'addAdvert', 'getRoutePath'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('update', 'my', 'Del_my'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete', 'toModerate', 'moderate'),
                'users' => array('admin', 'moderator'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
                'maxLength' => 6,
                'minLength' => 6,
                'width' => 100,
                'height' => 40,
                ));
    }
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionDetails($id){
      $order = PpRoute::model()->findByPk($id);

      if ($order) {
        $locations = $order->getRouteLocations();
        $this->render('_details', array(
            'order'=>$order,
            'route_detailed_path' => $locations['near']
        ));
      } else throw new CHttpException(404, Yii::t('poputchik', 'Запрошенный маршрут не найден'));

  }
    public function actionCheckUser(){
	
		if(isset($_POST)){
			$arr ['key']=1;
		
			$user = User::model()->find(array('condition'=>'username=:username', 'params'=>array(':username'=>$_POST['phone'])));
		
			if( !isset($user) ){
				$arr ['key']=0;			
			}
			print json_encode($arr);
		}
	
	
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
                $subject = '=?UTF-8?B?' . base64_encode("Вам пишут из сервиса Попутчик") . '?=';
                $headers = "From: $name <{$_POST['sender']}>\r\n" .
                        "Reply-To: {$user->email}\r\n" .
                        "Sender: admin@infotoway.ru\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Errors-to: admin@infotoway.ru\r\n" .
                        "Content-Type: text/plain; charset=UTF-8";

                if (!mail($user->email, $subject, $_POST['text'], $headers))
                    $response ['sign'] = 2;
            }

            print json_encode($response);
            Yii::app()->end();
        }
    }
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }
    public function getLists($model) {
        $rims = array();
        $rims['rim_sid'] = array();
        $rims['rim_sname'] = array();
        $rims['rim_sp_sid'] = array();
        $rims['rim_sp_sname'] = array();
        
        if(isset($model->route)){
            for ($i = 0; $i < count($model->route->routePaths) - 1; $i++) {
                if ($model->route->routePaths[$i]->direction) {
                    $rims['rim_sid'][] = $model->route->routePaths[$i]->idPath->id_settlement_1;
                    $rims['rim_sname'][] = urlencode($model->route->routePaths[$i]->idPath->startSettlement->getSettlementFullname());
                } else {
                    $rims['rim_sid'][] = $model->route->routePaths[$i]->idPath->id_settlement_2;
                    $rims['rim_sname'][] = urlencode($model->route->routePaths[$i]->idPath->endSettlement->getSettlementFullname());
                }
            }
        }
        
        for ($i = 0; $i < count($model->rims_sp); $i++) {
            $rims['rim_sp_sid'][] = $model->rims_sp[$i]->id_settlement;
            $rims['rim_sp_sname'][] = urlencode($model->rims_sp[$i]->idSettlement->getSettlementFullname());
        }

        switch ($model->type_route) {
            case 1:
                $startSettlement = $model->idSettlement;
                $s_areas = SAreas::model()->findAll(array('order' => 'name', 'condition' => 'id_settlement= :id_settlement',
                    'params' => array(':id_settlement' => (int) $model->id_settlement)));
                break;
            case 2:
                if (count($rims['rim_sid'])) {
                    if (!$model->direction) {
                        $startSettlement = $model->route->startSettlement;
                        $endSettlement = $model->route->endSettlement;
                    } else {
                        $startSettlement = $model->route->endSettlement;
                        $endSettlement = $model->route->startSettlement;
                    }
                } else {
                    if (!$model->direction) {
                        $startSettlement = $model->path->startSettlement;
                        $endSettlement = $model->path->endSettlement;
                    } else {
                        $startSettlement = $model->path->endSettlement;
                        $endSettlement = $model->path->startSettlement;
                    }
                }
                break;
        }

        return array(
            'targets' => Referens::getReferensByAlias('poput_target'),
            'countries' => Countries::model()->findAll(array('order' => 'name')),
            'types_sum' => Referens::getReferensByAlias('poput_type_sum'),
            'types_auto' => Referens::getReferensByAlias('poput_type_auto'),
            'brands_auto' => AutoBrands::model()->findAll(array('order' => 'turn, brand')),
            'models_auto' => AutoModels::model()->findAll(array('order' => 'model', 'condition' => 'id_brand= :id_brand', 'params' => array(':id_brand' => $model->id_brand ? $model->id_brand : 0))),
            'rims' => $rims,
            'startSettlement' => isset($startSettlement)?$startSettlement:false,
            'endSettlement' => isset($endSettlement)?$endSettlement:false,
            'sa_1' => isset($s_areas)?$s_areas:false,
            'sa_2' => isset($s_areas)?$s_areas:false,
        );
    }
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new PoputchikOrder;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $cu = Yii::app()->user;
        $user_data = array();
      $model->type_route = 1;
      $model->type_time = 1;
        if ($cu->id && !$cu->checkAccess('operator')) {
            $user = User::model()->find(array('condition' => 'id=:id', 'params' => array(':id' => $cu->id)));
            $profile = $user->profile;

            if ($model->isNewRecord)
			$user_data['first_name'] = $profile->first_name;
            $user_data['email'] = $user->email;
            $user_data['phone'] = $user->username;
			
		}

        if (isset($_POST['PoputchikOrder'])) {
		
            $model->attributes = $_POST['PoputchikOrder'];
                        
            if ($model->save()) {
                if ($model->type_route == 1) {
                    $seoCity = SeoCity::model()->find('city_id=:city_id', array(':city_id' => $model->id_settlement));
                    if ($seoCity == null) {
                        Yii::import('application.extensions.UrlTransliterate.UrlTransliterate');
                        $seoCity = new SeoCity;
                        $seoCity->city_id = $model->id_settlement;
                        $seoCity->url = UrlTransliterate::cleanString($model->idSettlement->name);
                        $seoCity->save();
                    }
                }
                if ($model->activecode)
                    $this->redirect(array('general/activate_order', 'module' => 'poputchik', 'id' => $model->id_order));
                else
                    $this->redirect(array('ru/order/complete'));
            }
        }

        $this->render('create', array(
            'model' => $model,
            'user_data' => $user_data,
        ));
    }
    public function actionComplete(){
        $this->render('complete', array(
            
        ));
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
        $user_data = array();
		
		if($model->id_user != 	$cu->id && !$cu->checkAccess('operator') )
			  $this->redirect(array('/general/cabinet'));



          $user = User::model()->find(array('condition' => 'id=:id', 'params' => array(':id' => $model->id_user)));
          $profile = $user->profile;
          $user_data['email'] =  $user->email;

          $user_data['first_name'] =  $model->name ? $model->name  :  $profile->first_name;
          $user_data['phone'] =$model->phone ? $model->phone  :   $user->username;


        if (isset($_POST['PoputchikOrder'])) {

            $model->attributes = $_POST['PoputchikOrder'];
            if (!$cu->checkAccess('operator'))
                $model->status = 0;
            $model->activecode = 0;
            if ($model->save())
                $this->redirect(array('index', 'id_order' => $model->id_order));
        }

        $this->render('update', array(
            'model' => $model,
            'user_data' => $user_data,
        ));
    }
    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
	
		if(	isset($id) &&  $id!=null){
			$this->loadModel($id)->delete();
		}else 
		if(isset($_POST['orders']))		{
		
			$Ids = implode(', ', $_POST['orders']);
			PoputchikOrder::model()->deleteAll('id_order IN (' . $Ids . ')');		
		
		}
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
    public function actionDel_my($id) {
       // $order = $this->loadModel($id);

    //    $order->status = 0;
    //    $order->save();
	  $this->loadModel($id)->delete();
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
    public function actionModerate($id) {
	
		if(	isset($id) &&  $id!=null){
			$o = $this->loadModel($id);
			$o->status = 1;
			$o->activecode = 1;
			$o->save(false);
		}else 
		if(isset($_POST['orders']))		{
			$Ids = implode(', ', $_POST['orders']);
			PoputchikOrder::model()->updateAll(array( 'status' => 1,'activecode'=>1 ), 'id_order IN (' . $Ids . ')');
			
		}
		
      
        $this->redirect(array('toModerate'));
    }
    /**
     * Lists all models.
     */
    public function actionIndex() {
      $form = new PoputchikForm();

      $searcher = new PoputchikSearcher($form);
      $searcher->search();

      $seoModel = $form->getCityFrom()->seo;
      $seoModel = $seoModel?$seoModel:new GeonameSeo();

          $this->render('index', array(
        'seoCity' => $seoModel,
        'form'    => $form,
        'adverts' => $searcher->form()
      ));
    }
    public function actionMy() {
        $orders = array();
        if (!Yii::app()->user->isGuest) {
            $id = Yii::app()->user->id;


            $orders = PoputchikOrder::model()->findAll(array('condition' => "id_user = $id AND status=1", 'order' => 'id_order DESC'));

            $count_orders = count($orders);
            if ($count_orders > 10) {
                $pages = (int) (($count_orders + 9) / 10);
                $current_page = $_GET['page'] ? $_GET['page'] : 1;
                $orders = array_slice($orders, 10 * ($current_page - 1), 10);
            }
        }

        $this->render('my', array(
            'orders' => $orders,
            'pages' => $pages
        ));
    }
    public function actionToModerate() {
        $orders = PoputchikOrder::model()->findAll(array('order' => 'id_order ASC', 'condition' => "status = 0")); //->with('days_of_week')
        $this->render('moderate', array(
            'orders' => $orders,
        ));
    }
    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new PoputchikOrder('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PoputchikOrder']))
            $model->attributes = $_GET['PoputchikOrder'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }
    public function actionGetPhone() {
        if (Yii::app()->request->isPostRequest) {
            $o = $this->loadModel(Core::getPost('id_order', false, type_int));
            print json_encode('<div class="phone">' . $o->user->username . '</div>');
            Yii::app()->end();
        }
    }

    public function actionAddAdvert(){
      $type = Core::getGet('type', null);
      switch($type) {
        case PpRoute::TYPE_DRIVER:
          if (Yii::app()->request->isPostRequest) {
            if (Yii::app()->request->isAjaxRequest) {
              $action = Core::getPost('action', 'unknown');
              $result = array(
                'result'   => 'Error',
                'messages' => array()
              );

              switch($action) {
                case 'validate':
                  $step   = Core::getPost('step', 1);
                  $result = PpRoute::validateStep($type, $step, Core::getPost('data'));
                  break;
                case 'create':
                  $route = new PpRoute();
                  $data = Core::setArrayDefaults($_POST, array(
                    'additional' => array(
                        'deviation' => PpRoute::DEVIATION_NONE,
                        'luggage' => PpRoute::LUGGAGE_SMALL,
                        'punctuality' => PpRoute::PUNCTUALITY_EXACTLY,
                    ),
                    'auto-model' => 1,
                    'available_until' => array(
                      'date' => date('d.m.Y'),
                      'time' => array(
                        'H' => 0,
                        'm' => 0,
                      )
                    ),
                    'periodicity-type' => 'once',
                    'periodicity-weekly-days' => null,
                    'returnAt' => array(
                        'date' => date('d.m.Y'),
                        'time' => array(
                            'H' => 0,
                            'm' => 0,
                        )
                    ),
                    'startAt' => array(
                        'date' => date('d.m.Y'),
                        'time' => array(
                            'H' => 0,
                            'm' => 0,
                        )
                    ),
                    'avatar' => '',
                    'cost' => array(
                      'forPlace' => 1,
                      'price' => 1,
                    ),
                    'email' => '',
                    'end-location' => array(
                      'id'   => 0,
                      'lat'  => 0,
                      'lng'  => 0
                    ),
                    'start-location' => array(
                      'id'   => 0,
                      'lat'  => 0,
                      'lng'  => 0
                    ),
                    'i_approve' => true,
                    'name' => '',
                    'path' => array(),
                    'short-path' => array(),
                    'phone' => '',
                    'places' => 1,
                    'comment' => '',
                    'route-type' => 'city',
                    'start-address' => '',
                    'end-address' => ''
                  ));

                  $periodicity = null;
                  $periodicity = ($data['periodicity-type'] == 'weekly')?implode(',', $data['periodicity-weekly-days']):null;
                  $periodicity = $periodicity?$periodicity:null;

                  $departure = PpRouteHelper::convertDate($data['startAt']);

                  if ($data['route-type'] == 'city') {
                    $path_full = PpRouteHelper::inverseCoordinatesInPath($data['path']);
                  } else {
                    $path_full = PpRouteHelper::getFullPath($data['path']);
                  }

                  $attributes = array(
                    'uid'                  => PpRouteHelper::getOrCreateUserID($data),
                    'from_id'              => intval($data['start-location']['id']),
                    'to_id'                => intval($data['end-location']['id']),
                    'from'                 => array(floatval($data['start-location']['lng']), floatval($data['start-location']['lat'])),
                    'to'                   => array(floatval($data['end-location']['lng']), floatval($data['end-location']['lat'])),
                    'path'                 => PpRouteHelper::array2path($data['short-path']),
                    'path_full'            => PpRouteHelper::array2path($path_full),
                    'departure'            => $departure,
                    'departure_periodicity'=> $periodicity,
                    'return'               => PpRouteHelper::convertDate($data['returnAt']),
                    'cost'                 => round(intval($data['cost']['price']) / intval($data['cost']['forPlace'])),
                    'car_id'               => intval($data['auto-model']),
                    'free_seats'           => intval($data['places']),
                    'luggage'              => $data['additional']['luggage'],
                    'punctuality'          => $data['additional']['punctuality'],
                    'deviation_from_route' => $data['additional']['deviation'],
                    'available_until'      => PpRouteHelper::convertDate($data['available_until']),
                    'comment'              => strip_tags($data['comment']),
                    'type'                 => PpRoute::TYPE_DRIVER,
                    'from_address'         => $data['route-type'] == 'city'?$data['start-address']:null,
                    'to_address'           => $data['route-type'] == 'city'?$data['end-address']:null,
                    'enabled'              => 0
                  );

                  $errors = '';

                  $route->setAttributes($attributes, false);

                  if (!$route->validate()) {
                    $errors  .= CHtml::errorSummary($route);
                  } else {
                    if (!$route->save()) {
                      $errors  .= CHtml::errorSummary($route);
                    }
                  }

                  if ($errors) {
                    $result['messages']['overall'] = $errors;
                  } else {
                    $result['result'] = 'ok';
                  }
                  break;
                default:
                  $result['messages']['overall'] = 'Неизвестное действие.';
              }

              echo(json_encode($result));
              Core::yiiTerminate();
            }
          } else {
            Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->getBasePath().'/../js/poputchik/router.js'),CClientScript::POS_END);
            $this->render('advert/driver-form');
          }
          break;
        case PpRoute::TYPE_PASSENGER:
          if (Yii::app()->request->isPostRequest) {
            if (Yii::app()->request->isAjaxRequest) {
              $action = Core::getPost('action', 'unknown');
              $result = array(
                  'result'   => 'Error',
                  'messages' => array()
              );

              switch($action) {
                case 'validate':
                  $step   = Core::getPost('step', 1);
                  $result = PpRoute::validateStep($type, $step, Core::getPost('data'));
                  break;
                case 'create':
                  $route = new PpRoute();
                  $data = Core::setArrayDefaults($_POST, array(
                      'additional' => array(
                          'deviation' => PpRoute::DEVIATION_NONE,
                          'luggage' => PpRoute::LUGGAGE_SMALL,
                          'punctuality' => PpRoute::PUNCTUALITY_EXACTLY,
                      ),
                      'auto-model' => 1,
                      'available_until' => array(
                          'date' => date('d.m.Y'),
                          'time' => array(
                              'H' => 0,
                              'm' => 0,
                          )
                      ),
                      'returnAt' => array(
                          'date' => date('d.m.Y'),
                          'time' => array(
                              'H' => 0,
                              'm' => 0,
                          )
                      ),
                      'periodicity-type' => 'once',
                      'periodicity-weekly-days' => null,
                      'startAt' => array(
                          'date' => date('d.m.Y'),
                          'time' => array(
                              'H' => 0,
                              'm' => 0,
                          )
                      ),
                      'avatar' => '',
                      'cost' => array(
                          'forPlace' => 1,
                          'price' => 1,
                      ),
                      'email' => '',
                      'end-location' => array(
                          'id'   => 0,
                          'lat'  => 0,
                          'lng'  => 0
                      ),
                      'start-location' => array(
                          'id'   => 0,
                          'lat'  => 0,
                          'lng'  => 0
                      ),
                      'i_approve' => true,
                      'name' => '',
                      'path' => array(),
                      'short-path' => array(),
                      'phone' => '',
                      'places' => 1,
                      'comment' => ''
                  ));

                  $periodicity = null;
                  $periodicity = ($data['periodicity-type'] == 'weekly')?implode(',', $data['periodicity-weekly-days']):null;
                  $periodicity = $periodicity?$periodicity:null;

                  $departure = PpRouteHelper::convertDate($data['startAt']);

                  $attributes = array(
                      'uid'                  => PpRouteHelper::getOrCreateUserID($data),
                      'from_id'              => intval($data['start-location']['id']),
                      'to_id'                => intval($data['end-location']['id']),
                      'from'                 => array(floatval($data['start-location']['lng']), floatval($data['start-location']['lat'])),
                      'to'                   => array(floatval($data['end-location']['lng']), floatval($data['end-location']['lat'])),
                      'path'                 => PpRouteHelper::array2path($data['short-path']),
                      'path_full'            => PpRouteHelper::array2path(PpRouteHelper::getFullPath($data['path'])),
                      'departure'            => $departure,
                      'departure_periodicity'=> $periodicity,
                      'return'               => PpRouteHelper::convertDate($data['returnAt']),
                      'cost'                 => 0,
                      'car_id'               => null,
                      'free_seats'           => 0,
                      'luggage'              => PpRoute::LUGGAGE_SMALL,
                      'punctuality'          => PpRoute::PUNCTUALITY_EXACTLY,
                      'deviation_from_route' => PpRoute::DEVIATION_NONE,
                      'available_until'      => date('Y-m-d H:i:s',time()+4*7*24*60*60), // 4 недели
                      'comment'              => strip_tags($data['comment']),
                      'type'                 => PpRoute::TYPE_PASSENGER,
                      'enabled'              => 0,
                  );

                  $errors = '';

                  $route->setAttributes($attributes, false);

                  if (!$route->validate()) {
                    $errors  .= CHtml::errorSummary($route);
                  } else {
                    if (!$route->save()) {
                      $errors  .= CHtml::errorSummary($route);
                    }
                  }

                  if ($errors) {
                    $result['messages']['overall'] = $errors;
                  } else {
                    $result['result'] = 'ok';
                  }
                  break;
                default:
                  $result['messages']['overall'] = 'Неизвестное действие.';
              }

              echo(json_encode($result));
              Core::yiiTerminate();
            } else {
              echo('Save Advert');
              Core::yiiTerminate();
            }
          } else {
            Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->getBasePath().'/../js/poputchik/router.js'),CClientScript::POS_END);
            $this->render('advert/passenger-form');
          }
          break;
        default:
          die();
      }
    }

    public function actionGetRoutePath(){
      $id = Core::getGet('id', 0, type_int);
      $model = $this->loadModel($id);

      // $path = $model->getRouteLocations();
      $path = PpRouteHelper::reduce($model->getRouteWaypoints(), 5);
//      $path = array();

      echo(json_encode(
        array(
          'from' => $model->from,
          'to'   => $model->to,
          'path' => $path,
//          'pathf' => $model->path_full
        )
      ));
//      echo $id;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return PpRoute the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = PpRoute::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param PoputchikOrder $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'poputchik-order-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}

/*
  public function actionAdd(){
    Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->getBasePath().'/../js/poputchik/router-test.js'),CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->getBasePath().'/../js/datetimepicker/jquery.datetimepicker.js'),CClientScript::POS_END);
    Yii::app()->clientScript->registerCssFile(Yii::app()->assetManager->publish(Yii::app()->getBasePath().'/../js/datetimepicker/jquery.datetimepicker.css'));
    $this->render('add');
  }
  public function actionTesta(){
    $data = Yii::app()->request->getPost('data');

    $x0=0; $y0=0; $pid = array();
    foreach($data as $point){
  //    echo($point['lat'].','.$point['lng'].'<br>');
      $info = EGeocoderHelper::getCityByCoordMy($point['lng'],$point['lat']);
      if ($info) {
        if (!in_array($info[0]['id'], $pid)) {
          $pid[] = $info[0]['id'];
        }
      }


      if ($x0) {
        $query = "SELECT * FROM `geoname` WHERE st_touches((linestring(point($x0, $y0), point({$point['lat']}, {$point['lng']}))), area) ORDER BY st_distance(point($x0, $y0), coord);";
//        echo($query.'<br>');
        $res = Yii::app()->db->createCommand($query)->queryAll();
        foreach ( $res as $one ) {
          if (!in_array($one['id'], $pid)) {
            $this->setPoint( $one['latitude'], $one['longitude'], $one['dia'], '#00FF00' );
            echo( '<span style="color:#00FF00;">' . $one['id'] . ') ' . $one['name_ru'] . '</span><br>' );
            $pid[] = $one['id'];
          }
        }

      }

      $x0 = $point['lat'];
      $y0 = $point['lng'];

//      echo(' >> '.$x0.','.$y0.'<br>');

      if ($info) {
        echo($info[0]['id'].') '.$info[0]['name_ru'].'<br>');
        $y = str_replace(',','.',$info[0]['longitude']);
        $x = str_replace(',','.',$info[0]['latitude']);
        $this->setPoint($x,$y, $info[0]['dia']);
      } else {
//				  echo('-');
      }


      // SELECT round(111195*st_distance(point(43.91428, 57.94144), coord)/1000) distance_in_meters

//			  echo('<br>');
      echo("
<script type='text/javascript'>
  		  var myLatlng = new google.maps.LatLng({$point['lng']},{$point['lat']});
			  var marker = new google.maps.Marker({
				  position: myLatlng,
				  map: router.map,
				  title: '{$point['lat']},{$point['lng']}'
			  });
</script>");
    }
  }

  private function setPoint($x,$y, $dia, $color = '#FF0000'){
    echo("
<script type='text/javascript'>
	var image = '/images/del-icon.png';

  var myLatlng = new google.maps.LatLng({$x},{$y});
  var populationOptions = {
    strokeColor: '$color',
    strokeOpacity: 0.7,
    strokeWeight: 2,
    fillColor: '$color',
    fillOpacity: 0.25,
    map: router.map,
    center: myLatlng,
    radius: 1000*{$dia}/2
  };
  // Add the circle for this city to the map.
  var cityCircle = new google.maps.Circle(populationOptions);

</script>");
  }
*/


/*
SELECT *
FROM pp_route route
INNER JOIN geoname source ON st_touches(route.path_full, source.area) AND source.id = 709930
INNER JOIN geoname target ON st_touches(route.path_full, target.area) AND target.id = 524901
WHERE st_distance(route.from, source.coord) < st_distance(route.from, target.coord)

*/