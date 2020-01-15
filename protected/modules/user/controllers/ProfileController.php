<?php

class ProfileController extends AController
{
	public $defaultAction = 'profile';
	public $layout='//layouts/column2';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;
	/**
	 * Shows a particular model.
	 */
	public function actionProfile()
	{
		$model = $this->loadUser();
	    $this->render('profile',array(
	    	'model'=>$model,
			'profile'=>$model->profile,
	    ));
	}


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
    public function actionSetConfirm()
    {




        if(  !  User::model()->find(array('condition'=>'username=:username', 'params'=>array(':username'=>$_POST['phone']   )))  ){
            Yii::app()->session["code"]  = Yii::app()->epassgen->generate(4, 0, 4, 0);
            Yii::app()->session["phone"]  = $_POST['phone'];
            $result = Yii::app()->smsgate->send( $_POST['phone']  ,Yii::t('general', 'Код подверждения смены логина на сайте INFOtoway.ru : '). Yii::app()->session["code"]);
            if($result == 0) {
                echo json_encode(1);
            } else echo json_encode(0);
            Yii::app()->end();
        }else   echo json_encode(2);
    }

    public function actionGetConfirm()
    {



        if( Yii::app()->session["code"] == $_POST['code'] ) {

            $model = $this->loadUser();
            $model->setAttributes( array("username"=> Yii::app()->session["phone"] ) );

                 if( $model->save(true  )){

                     print json_encode(1);
                 }else
                    print json_encode(2);
        } else print json_encode(0);


        Yii::app()->end();

    }



    public function actionEdit()
	{
           
		$model = $this->loadUser();
		$profile=$model->profile;
		$modelPass = new UserChangePassword;
		// ajax validator
		if(isset($_POST['ajax']) && $_POST['ajax']==='profile-form')
		{	
			echo UActiveForm::validate(array($model,$profile,$modelPass) );				
			Yii::app()->end();
		}
		
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			$profile->attributes=$_POST['Profile'];

			if($model->validate()&&$profile->validate()) {
				if($model->save() and $profile->save()) {
					Yii::app()->user->updateSession();
					Yii::app()->user->setFlash('profileMessage',UserModule::t("Changes is saved."));
					$this->redirect(array('/general/cabinet'));
				}
			} else $profile->validate();
		}

		$this->render('edit',array(
			'model'=>$model,
			'modelPass'=>$modelPass,
			'profile'=>$profile,
		));
	}
	
	/**
	 * Change password
	 */
	public function actionChangepassword() {
		$model = new UserChangePassword;

		if (Yii::app()->user->id) {
			
			// ajax validator
			if(isset($_POST['ajax']) && $_POST['ajax']==='changepassword-form')
			{
				echo UActiveForm::validate($model);
				Yii::app()->end();
			}

			if(isset($_POST['UserChangePassword'])) {
		
					$model->attributes=$_POST['UserChangePassword'];
					if($model->validate()) {
						$new_password = User::model()->notsafe()->findbyPk(Yii::app()->user->id);
						$new_password -> password = UserModule::encrypting($model->password);
						$new_password -> activkey=UserModule::encrypting(microtime().$model->password);
						$new_password -> save();
						Yii::app()->user->setFlash('profileMessage',UserModule::t("New password is saved."));
						$this->redirect(array("profile"));
					}
			}
			$this->render('changepassword',array('model'=>$model));
	    }
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadUser()
	{
		if($this->_model===null)
		{
			if(Yii::app()->user->id)
				$this->_model=Yii::app()->controller->module->user();
			if($this->_model===null)
				$this->redirect(Yii::app()->controller->module->loginUrl);
		}
		return $this->_model;
	}
}