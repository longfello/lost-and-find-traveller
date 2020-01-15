<?php

class UloginController extends Controller
{
  public function actions() {
    return array(
      // captcha action renders the CAPTCHA image displayed on the contact page
        'captcha' => array(
            'class' => 'CCaptchaAction',
            'backColor' => 0xFFFFFF,
        ),
      // page action renders "static" pages stored under 'protected/views/site/pages'
      // They can be accessed via: index.php?r=site/page&view=FileName
        'page' => array(
            'class' => 'CViewAction',
        ),
    );
  }

    public function actionLogin() {
        if (isset($_POST['token'])) {
            $ulogin = new UloginModel();
            $ulogin->setAttributes($_POST);
            $ulogin->getAuthData();
//            if ($ulogin->validate() && $ulogin->login()) {
            if ($ulogin->login()) {
              $this->redirect(Yii::app()->user->returnUrl);
            } else {
              if (!empty($ulogin->identity)) {
                $nested_fields = array(
                  'phone'     => array('label' => 'Телефон','val' => Core::translit($ulogin->phone), 'error' => isset($ulogin->errors['phone'])?array_pop($ulogin->errors['phone']):''),
                  'email'     => array('label' => 'Email','val' => $ulogin->email, 'error' => isset($ulogin->errors['email'])?array_pop($ulogin->errors['email']):''),
                );
                $network_param = "
<input type='hidden' name=\"network-identity\"  value='$ulogin->identity'>
<input type='hidden' name=\"network-network\"   value='$ulogin->network'>
<input type='hidden' name=\"network-email\"     value='$ulogin->email'>
<input type='hidden' name=\"network-full_name\" value='$ulogin->full_name'>
";
                $this->render('assoc', array(
                  'data'          => $ulogin,
                  'nested_fields' => $nested_fields,
                  'network_param' => $network_param
                ));
              } else $this->render('error');
            }
        }
        else {

            $this->redirect(Yii::app()->homeUrl, true);
        }
    }

    public function actionValidate(){
      $type    = Core::getPost('type', '');
      $network = $this->get_network_params();
      $res = array(
        'result' => false,
        'errors' => array()
      );
      switch($type) {
        case 'assoc':
          $model=new LoginForm;
          $model->attributes= array(
            'username' => Core::getPost('phone', ''),
            'password' => Core::getPost('password', ''),
            'rememberMe' => 0);
          if($model->validate() && $model->login()) {
            $user = new UsersIdentity();
            $user->id = Yii::app()->user->id;
            $user->identity  = $network['identity'];
            $user->network   = $network['network'];
            $user->email     = $network['email'];
            $user->full_name = $network['full_name'];
            $user->save();
          } else {
            $res['errors']['associate'] = "Пользователь с указанным телефоном и паролем не существует";
          }
          break;
        case 'create':
          $phone     = Core::getPost('phone', '');
          $present   = User::model()->find("username = '$phone'");
          if (is_null($present)) {
            $sUser = new User();
            /* @var $sUser User */
            $sUser->scenario = 'uLogin';
            $sUser->email    = Core::GetPost('email', $network['email']);
            $sUser->username = $login = Core::translit(Core::getPost('phone', ''));
            $password = Core::genCode(8);
            $sUser->activkey = UserModule::encrypting(microtime().$password);
            $sUser->password = UserModule::encrypting($password);
            $sUser->superuser= 0;
            $sUser->status = User::STATUS_ACTIVE;
            if ($sUser->save()) {
              $profile = new Profile();
              /* @var $profile Profile */
              $profile->user_id = $sUser->id;
              $profile->first_name = $network['full_name'];

              if ($profile->save()) {
                $user = new UsersIdentity();
                $user->id = $sUser->id;
                $user->identity = $network['identity'];
                $user->network = $network['network'];
                $user->email = $network['email'];
                $user->full_name = $network['full_name'];
                if ($user->save()) {
                  $model=new UserLogin;
                  $model->attributes= array(
                      'username' => $login,
                      'password' => $password,
                      'rememberMe' => 0);
                  if($model->validate()) {
                    // All ok!
                    $user = User::model()->findByPk(Yii::app()->user->id);
                  } else  $res['errors']['create'] = $this->pop_error($model->getErrors());
                } else $res['errors']['create'] = $this->pop_error($user->getErrors());
              } else $res['errors']['create'] = $this->pop_error($sUser->getErrors());
            } else $res['errors']['create'] =  $this->pop_error($sUser->getErrors()); //"Пользователь с указанным телефоном уже зарегистрирован на сайте 1";
          } else $res['errors']['create'] = "Пользователь с указанным телефоном уже зарегистрирован на сайте";
          break;
        default: $res['errors'] = "No route.";
      }
      $res['result'] = (count($res['errors']) == 0);
      echo(json_encode($res));
      Yii::app()->end();
    }

    private function pop_error($errors_array){
      while (is_array($errors_array)) {
        $errors_array = array_pop($errors_array);
      }
      return $errors_array;
    }

    private function get_network_params(){
      return array(
        'identity'  => Core::getPost('network-identity'),
        'network'   => Core::getPost('network-network'),
        'email'     => Core::getPost('network-email'),
        'full_name' => Core::getPost('network-full_name'),
      );
    }

}