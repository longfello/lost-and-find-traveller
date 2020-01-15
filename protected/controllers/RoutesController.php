<?php

class RoutesController extends AController
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
				'actions'=>array('index', 'view', 'create','update','delete'),
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
		$model=new Routes;
		
		$countries=Countries::model()->findAll(array('order'=>'name'));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Routes']))
		{
			$model->attributes=$_POST['Routes'];
			if($model->save()) {
				if(!$model->saveRP()) $model->addError('id_route', 'Возникла непредвиденная ошибка сохранения. Попробуйте сохранить ещё раз.');
				else $this->redirect(array('view','id'=>$model->id_route));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'countries'=>$countries,
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

		$countries=Countries::model()->findAll(array('order'=>'name'));
		
		$id_country_1 = $model->startSettlement->idRegion->id_country;
		$regions_1 = Regions::model()->findAll(array('order'=>'name', 'condition'=>'id_country=:id_country', 
                  'params'=>array(':id_country'=>$id_country_1)));
		$id_region_1 = $model->startSettlement->id_region;
		
		$id_country_2 = $model->endSettlement->idRegion->id_country;
		$regions_2 = Regions::model()->findAll(array('order'=>'name', 'condition'=>'id_country=:id_country', 
                  'params'=>array(':id_country'=>$id_country_2)));
		$id_region_2 = $model->endSettlement->id_region;
		
		if(!$_POST) {
			$rims = array();
			$rims['rim_sid'] = array();
			$rims['rim_sname'] = array();
			for($i = 0; $i < count($model->routePaths)-1; $i++) {
				if($model->routePaths[$i]->direction) {
					$rims['rim_sid'][] = $model->routePaths[$i]->idPath->id_settlement_1;
					$rims['rim_sname'][] = urlencode($model->routePaths[$i]->idPath->startSettlement->getSettlementFullname());
				}
				else {
					$rims['rim_sid'][] = $model->routePaths[$i]->idPath->id_settlement_2;
					$rims['rim_sname'][] = urlencode($model->routePaths[$i]->idPath->endSettlement->getSettlementFullname());
				}
			}
		}
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Routes']))
		{
			$model->attributes=$_POST['Routes'];
			$model->is_active = 1;
			$model->is_moderate = 1;
			if($model->save()) {
				$model->deleteRP();
				if(!$model->saveRP()) $model->addError('id_route', 'Возникла непредвиденная ошибка сохранения. Попробуйте сохранить ещё раз.');
				else $this->redirect(array('index'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'countries'=>$countries,
			'regions_1'=>$regions_1,
			'regions_2'=>$regions_2,
			'id_country_1'=>$id_country_1,
			'id_region_1'=>$id_region_1,
			'id_country_2'=>$id_country_2,
			'id_region_2'=>$id_region_2,
			'rims'=>$rims,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$pos = PoputchikOrder::model()->findAll('id_route=:idr', array('idr'=>$id));
		$model = $this->loadModel($id);
		if($pos) { $model->is_active = 0; $model->is_moderate = 1; $model->save(false); }
		else $model->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Routes('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Routes']))
			$model->attributes=$_GET['Routes'];
		if(Yii::app()->request->isAjaxRequest) {
			$this->renderPartial('_routesGrid',array(
				'model'=>$model,
			));
			Yii::app()->end();
		}
		else
			$this->render('index',array(
				'model'=>$model,
			));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Routes the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Routes::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Routes $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='routes-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
