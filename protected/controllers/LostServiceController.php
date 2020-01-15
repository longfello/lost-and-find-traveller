<?php
class LostServiceController extends AController
{
  public $service_id = SERVICE_NEPOTERAYKA;
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	 public $pageTitle = "Единая информационная служба - Непотеряйка";
	 
	 
	public function filterDomainControl( $filterChain )
	{
		if( Yii::app()->params['subdomain']  !=  SERVICE_NEPOTERAYKA )
			throw new CHttpException(404, 'Страница не найдена');
		$filterChain->run();
	}
	
	 public function actions()
	{
	  return array(
		'captcha'=>array(
		'class'=>'MyCCaptchaAction',
		),
	  );
	}
	public $layout='//layouts/column1';

	/**
	 * @return array action filters
	 */
	public function actionIFind()
	{
		$model=new LostService;
		
		if(isset($_POST['LostService']))
		{
			$_POST['LostService']['id_thing'] =  Yii::app()->general->idthing_to_userid($_POST['LostService']['id_thing']);
			$model->attributes=$_POST['LostService'];
		
			if($model->save()) {
				Yii::app()->user->setFlash('ifindmessage',UserModule::t("Ваше обращение принято!"));
				$this->redirect(array('site/index'));
			}
		}
	
		$this->render('ifind', array('model'=>$model));
	}
	
	
	public function actionStart()
	{
	
		$params = array('isCaptcha'=>1,'isReadonly'=>0,'showID'=>0);		
		$cu = Yii::app()->user;	
		if(!$cu->isGuest && !$cu->checkAccess('operator'))
			$params['isReadonly']=1;
			
			
	
			
		if($cu->isGuest || $cu->checkAccess('operator') ){
			$user = new User;		
			$profile = new Profile;			
			if($cu->checkAccess('operator'))
				$params['isCaptcha']=0;
		}else{	
			$user = User::model()->find(array('condition'=>'id=:id', 'params'=>array(':id'=>$cu->id)));
			$profile= $user->profile;
			if($user->lost_service_active!=0)
				$params['showID']=1;
		}		
	
		if(isset($_POST['Profile'])){
			$user->attributes=$_POST['User'];
			$profile->attributes=$_POST['Profile'];
			
			
			$sms="";
			if($user->status==0){		
				$pwd = Yii::app()->epassgen->generate(7, 0, 3, 0);
				$sms.=Yii::t('general', 'Ваш пароль на сайте ').$_SERVER['HTTP_HOST'].': '.$pwd;
			}
			$user->lost_service_active = 1;
		
			if( $user->save() ) { 						
				$profile->user_id = $user->id;				
				$profile->save();
				$sms.=Yii::t('general', '\nВаш ID в сервисе Непотеряка: ').Yii::app()->general->userid_to_idthing($user->id);
				$result = Yii::app()->smsgate->send($user->username,$sms);
				if($result == 0) {
						$user->password = UserModule::encrypting($pwd);
						$user->status = 1;		
					}	
				$params['showID']=1;
				Yii::app()->user->setFlash('ifindmessage',UserModule::t("Вы зарегестрировались в сервисе!"));
				$this->render('start', array('user'=>$user,'params'=>$params));	
				return;				
			}
		}	
		
		$this->render('start', array('user'=>$user,'profile'=>$profile,'params'=>$params));
	
	}
	
	
	public function actionToModerate()
	{
		
		$filters = array();
	
		if(isset($_POST['filters'])){
			if(isset($_POST['filters']['status0']) )$filters['status0']=0;
			if(isset($_POST['filters']['status1']) )$filters['status1']=1;
			if(isset($_POST['filters']['status2']) )$filters['status2']=2;
			$params[':idss'] = $idss;
		}
		if( count($filters)==0){
			$filters['status0']=0;
			$filters['status1']=1;			
		}
		
		$uid_condition="";
		if(isset($_POST['filters']['id']) && $_POST['filters']['id']!=""){
			$uid = Yii::app()->general->idthing_to_userid($_POST['filters']['id']);
			$uid_condition.="t.id_thing=".$uid." AND ";		
		}
		if(isset($_POST['filters']['phone']) && $_POST['filters']['phone']!=""){		
			$uid_condition.="User.username=".$_POST['filters']['phone']." AND ";		
		}
		$requests=LostService::model()->with(array('user'))->findAll(array('order'=>'t.id_ls ASC', 'condition'=>" $uid_condition t.status IN (".implode(', ',$filters).")"));	
		$filters['id']=$_POST['filters']['id'];
		$filters['phone']=$_POST['filters']['phone'];
		$this->render('moderate',array('requests'=>$requests,	'filters'=>$filters	));
	}
	
	
	public function actionCheckUser(){
	
		if(isset($_POST)){
			$arr ['key']=1;
		
			$user = User::model()->find(array('condition'=>'lost_service_active=1 AND username=:username', 'params'=>array(':username'=>$_POST['phone'])));
		
			if( !isset($user) ){
				$arr ['key']=0;			
			}
			print json_encode($arr);
		}
	
	
	}
	public function actionSendsms()
	{
		if(isset($_POST)) {
			$pwd = Yii::app()->epassgen->generate(6, 0, 6, 0);
			$model = LostService::model()->findbyPk($_POST['id']);	
			$arr = array( 'value' => $pwd);
			$model->confirm_code = $pwd;	
			if(!$model->save(false)) {
				$error = Yii::t('common', 'Произошла внутренняя ошибка сервера. Пожалуйста попробуйте ещё раз.');
				$arr ['key']=0;
				print json_encode($arr);
				return;
			}
			$result =Yii::app()->smsgate->send($_POST['phone'], Yii::t('general', 'Ваш код для возрата утерянного объекта в сервисе ').$_SERVER['HTTP_HOST'].': '.$pwd);
			
			if($result==0)	{
				$arr ['key']=2;
				print json_encode($arr);
			}else{
				$arr ['key']=1;
				print json_encode($arr);
			}
			Yii::app()->end();
		}
	}
	public function actionSendIDsms()
	{
		if(isset($_POST)) {
		
			$model = User::model()->find(array('condition'=>'username=:username', 'params'=>array(':username'=>$_POST['phone'])));
		
			$result =Yii::app()->smsgate->send($_POST['phone'], Yii::t('general', 'Ваш ID в сервисе Непотеряйка').Yii::app()->general->userid_to_idthing($model->id));
			
			if($result==0)	{	
				Yii::app()->user->setFlash('ifindmessage',UserModule::t("Ваш ID выслан по SMS на номер ".$_POST['phone']));
										
				 print json_encode(0);
				}
			else				
				print json_encode(1);
			
			Yii::app()->end();
		}
	}
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
				'DomainControl', 
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('info','index','ifind','captcha','start','CheckUser','sendIDsms','faq', 'how_its_work', 'lost'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('view', 'admin','delete','toModerate','update','Sendsms'),
				'users'=>array('admin', 'moderator'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new LostService;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['LostService']))
		{
			$model->attributes=$_POST['LostService'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id_ls));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
		public function actionHow_its_work()
		{		
			$this->render('how_its_work');
		}
		public function actionFaq()
		{		
			$this->render('faq');
		}
		public function actionLost()
		{		
			$this->render('lost');
		}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
	
		if(isset($_POST['LostService']))
		{	
	
			$model->attributes=$_POST['LostService'];
		
		
			if($model->save(false))
				$this->redirect(array('toModerate'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		
			
		$dataProvider=new CActiveDataProvider('LostService');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	public function actionInfo()
	{
		
			
		$dataProvider=new CActiveDataProvider('LostService');
		$this->render('info',array(
			'dataProvider'=>$dataProvider,
		));
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new LostService('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['LostService']))
			$model->attributes=$_GET['LostService'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return LostService the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=LostService::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param LostService $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='lost-service-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
