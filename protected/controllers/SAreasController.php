<?php

class SAreasController extends AController
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
			array('allow',
				'actions'=>array('getSAreasBySettlement'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index', 'view', 'create','update','admin','delete'),
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
		$model=new SAreas;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SAreas']))
		{
			$model->attributes=$_POST['SAreas'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id_sa));
		}

		$this->render('create',array(
			'model'=>$model,
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SAreas']))
		{
			$model->attributes=$_POST['SAreas'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id_sa));
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
		$dataProvider=new CActiveDataProvider('SAreas');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new SAreas('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SAreas']))
			$model->attributes=$_GET['SAreas'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionGetSAreasBySettlement()
	{
		$data=SAreas::model()->findAll(array('order'=>'name', 'condition'=>'id_settlement=:id_settlement', 
                  'params'=>array(':id_settlement'=>(int) $_POST['id_settlement'])));
		$ret = "";
		if($data) {
			$data=CHtml::listData($data,'id_sa','name');
			foreach($data as $value=>$name)
			{
				$ret .= CHtml::tag('option', array('value'=>$value),CHtml::encode($name),true);
			}
		}
		print json_encode($ret);
		Yii::app()->end();
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return SAreas the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=SAreas::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param SAreas $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sareas-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
