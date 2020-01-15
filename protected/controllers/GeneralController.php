<?php
function getBodyAndSub($rel){
  switch( $rel ){
    case 1:
      $text = Yii::t('main', "Мне помог ваш сервис!") ;
      break;
    case 2:
      $text = Yii::t('main', "Ваш серивис не помог!") ;
      break;
    case 3:
      $text = Yii::t('main', "Отчасти помог...") ;
      break;

  }
  return $text;
}

class GeneralController extends Controller
{



  public function actionSendReview()
  {
    $Subject="";
    $Body="";

    if(isset($_POST['text']))
    {


      if($_POST['text']<=3 ){

        $Body = $Subject = getBodyAndSub($_POST['text']);
      } else {
        $Subject =  getBodyAndSub($_POST['frame']);
        $Body =		$_POST['review'];
      }



      Yii::import('application.extensions.phpmailer.JPhpMailer');
      $mail = new JPhpMailer;
      $mail->Mailer = "smtp";
      $mail->IsSMTP();
      $mail->Host = 'smtp.yandex.ru';
      $mail->SMTPSecure = "ssl";
      $mail->SMTPKeepAlive = true;
      $mail->Port = '465';
      $mail->SMTPAuth = true;

      $mail->Username = 'infotoway.review@infotoway.ru';
      $mail->Password = 'c34fg73ferh34';
      $mail->SetFrom('infotoway.review@infotoway.ru','infotoway.review@infotoway.ru' );



      $mail->Subject = $Subject;
      $mail->MsgHTML( $Body);
      $mail->AddAddress('infotoway.review@infotoway.ru', 'John Doe');



      if( $mail->Send() )
        print json_encode(1);
      else
        print json_encode(0);
    }else
      print json_encode(0);

    Yii::app()->end();
  }




  public function actionActivate_order($id)
  {

    switch($_GET['module']) {
      case 'poputchik':
        $order = PoputchikOrder::model()->findByPk($id);
        break;
      case 'lostfound':
        $order = LostFound::model()->findByPk($id);
        break;
      case 'hotelservice':
        $order = HotelService::model()->findByPk($id);
        break;
    }


    $user = User::model()->findbyPk($order->id_user);
    $sendcode = false;
    if(isset(	$user->username ) )
      $sendcode = true;




    if($_POST['phone']) {

      $user->username = eregi_replace("([^0-9])", "", $_POST['phone']);
      if(!$user->save()) $error = Yii::t('common', 'Произошла внутренняя ошибка сервера. Пожалуйста попробуйте ещё раз.');
    }
    $order->activecode = '0';
    if(Yii::app()->general->activateUser($user)) {
      if($order->save(false)) $this->redirect(array('/ru/order/complete'));
    }

    /* else if($_POST['activecode']) {

      if($_POST['activecode'] == $order->activecode) {
        $order->activecode = '0';
        if(Yii::app()->general->activateUser($user)) {
          if($order->save(false)) $this->redirect(array('/ru/order/complete'));
        }
        $error = Yii::t('common', 'Произошла внутренняя ошибка сервера. Пожалуйста попробуйте ещё раз.');
      }
      else $error = Yii::t('general', 'Указан неверный код активации.');
    }



    if($sendcode) {
      $result = Yii::app()->smsgate->send($user->username, Yii::t('general', 'Код активации: '.$order->activecode));
      if($result == 1) $error_phone = 1;
    }

    $this->render('activate_order', array('error'=>$error, 'phone'=>$user->username, 'error_phone' => $error_phone) );
    */
  }


  public function actionCabinet()
  {
    $orders = array();
    if (!Yii::app()->user->isGuest){
      $id = Yii::app()->user->id;


      $orders = PoputchikOrder::model()->findAll(array('condition'=>"id_user = $id", 'order'=>'id_order DESC'));

      $count_orders = count($orders);
      if($count_orders > 10)  {
        $pages = (int)(($count_orders+9) / 10);
        $current_page = $_GET['page'] ? $_GET['page'] : 1;
        $orders = array_slice($orders, 10*($current_page-1), 10);
      }
    }

    $this->render('/poputchikOrder/my',array(
        'title'=>"Личный кабинет",
        'orders'=>$orders,
        'pages'=>$pages
    ));
  }

  /* public function actionCabinet()
  {
    if (Yii::app()->user->isGuest) $this->redirect("site/index");

    else  {
      $this->render('cabinet', array());
    //list($controller) = Yii::app()->createController('poputchikOrder');
  //	$url=$controller->actionMy();

  //	echo($url);
    }


  } */

  public function actionAjax_login()
  {
    if (Yii::app()->user->isGuest) {
      $model=new UserLogin;
      // collect user input data
      if(isset($_POST['UserLogin']))
      {
        $model->attributes=$_POST['UserLogin'];
        // validate user input

        if($model->validate()) {
          $this->lastViset();
          print json_encode(1);
          Yii::app()->end();
        }
      }
    }
    //print json_encode(0);
    Yii::app()->end();
  }
  public function actionAjax_sendpass()
  {

    if (Yii::app()->user->isGuest) {
      if(isset($_POST['UserLogin']))
      {

        $user = User::model()->find(array('condition'=>'username=:username', 'params'=>array(':username'=>$_POST['UserLogin']['phone'] )));
        if($user){
          $pwd = Yii::app()->epassgen->generate(7, 0, 3, 0);

          $result = Yii::app()->smsgate->send($user->username, Yii::t('general', 'Ваш пароль на сайте INFOtoway.ru : '.$pwd));
          if($result == 0) {
            $user->password = UserModule::encrypting($pwd);
            $user->save(false);

            print json_encode(0);
          }

          else print json_encode(2);
          Yii::app()->end();
        }

      }
    }
    print json_encode(1);
    Yii::app()->end();
  }
  private function lastViset() {
    $lastVisit = User::model()->notsafe()->findByPk(Yii::app()->user->id);
    $lastVisit->lastvisit = time();
    $lastVisit->save();
  }
  // Uncomment the following methods and override them if needed
  /*
  public function filters()
  {
    // return the filter configuration for this controller, e.g.:
    return array(
      'inlineFilterName',
      array(
        'class'=>'path.to.FilterClass',
        'propertyName'=>'propertyValue',
      ),
    );
  }

  public function actions()
  {
    // return external action classes, e.g.:
    return array(
      'action1'=>'path.to.ActionClass',
      'action2'=>array(
        'class'=>'path.to.AnotherActionClass',
        'propertyName'=>'propertyValue',
      ),
    );
  }
  */
}