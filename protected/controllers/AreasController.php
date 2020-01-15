<?php

class AreasController extends AController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index', 'view', 'create','update','admin','delete', 'getAreasByRegion'),
				'users'=>array('admin'),
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
		$model=new Areas;
		$regions=Regions::model()->findAll(array('order'=>'name'));
		$socrnames=DFiasSocrbase::model()->findAll(array('condition'=>'level=:l', 'order'=>'socrname', 'params'=>array(':l'=>3) ));
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Areas']))
		{
			$model->attributes=$_POST['Areas'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id_area));
		}

		$this->render('create',array(
			'model'=>$model,
			'regions'=>$regions,
			'socrnames'=>$socrnames,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$regions=Regions::model()->findAll(array('order'=>'name'));
		$socrnames=DFiasSocrbase::model()->findAll(array('condition'=>'level=:l', 'order'=>'socrname', 'params'=>array(':l'=>3) ));
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Areas']))
		{
			$model->attributes=$_POST['Areas'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id_area));
		}

		$this->render('update',array(
			'model'=>$model,
			'regions'=>$regions,
			'socrnames'=>$socrnames,
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
		$dataProvider=new CActiveDataProvider('Areas');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Areas('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Areas']))
			$model->attributes=$_GET['Areas'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionGetAreasByRegion()
	{
		$data=Areas::model()->findAll(array('order'=>'name', 'condition'=>'id_region=:id_region', 
                  'params'=>array(':id_region'=>(int) $_POST['Settlements']['id_region'])));
 
		$data=CHtml::listData($data,'id_area','name');
		print CHtml::tag('option', array('value'=>''),'Без района',true);
		foreach($data as $value=>$name)
		{
			print CHtml::tag('option', array('value'=>$value),CHtml::encode($name),true);
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Areas the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Areas::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Areas $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='areas-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
