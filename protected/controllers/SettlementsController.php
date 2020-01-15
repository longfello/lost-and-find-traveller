<?php

class SettlementsController extends AController
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
				'actions'=>array('autocomplete', 'GetCityId'),
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
		$model=new Settlements;
		$countries=Countries::model()->findAll(array('order'=>'name'));
		$regions=Regions::model()->findAll(array('order'=>'name'));
		$socrnames=DFiasSocrbase::model()->findAll(array('condition'=>'level IN (4, 6)', 'order'=>'socrname'));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Settlements']))
		{
			$model->attributes=$_POST['Settlements'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id_settlement));
		}

		$this->render('create',array(
			'model'=>$model,
			'countries'=>$countries,
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
		$countries=Countries::model()->findAll(array('order'=>'name'));
		$regions=Regions::model()->findAll(array('order'=>'name'));
		$socrnames=DFiasSocrbase::model()->findAll(array('condition'=>'level=:l', 'order'=>'socrname', 'params'=>array(':l'=>$model->kodTSt->level) ));
		$areas=Areas::model()->findAll(array('order'=>'name', 'condition'=>'id_region=:id_region', 
                  'params'=>array(':id_region'=>$model->id_region)));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Settlements']))
		{
			$model->attributes=$_POST['Settlements'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id_settlement));
		}

		$this->render('update',array(
			'model'=>$model,
			'countries'=>$countries,
			'regions'=>$regions,
			'socrnames'=>$socrnames,
			'areas'=>$areas,
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
		$dataProvider=new CActiveDataProvider('Settlements');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Settlements('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Settlements']))
			$model->attributes=$_GET['Settlements'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

  public function actionGetCityId (){
      $name    = Yii::app()->getRequest()->getParam('name');
			$region  = Yii::app()->getRequest()->getParam('region');
			$country = Yii::app()->getRequest()->getParam('country');

      $country = Countries::normalizeName($country);
			
			$country_exist = Countries::model()->find('name=:name', array(':name'=>$country));
			if(empty($country_exist) && $country!=""){
                $country_exist=new Countries;
                $country_exist->name = $country;
                $country_exist->save();
            }
			$country_id = $country_exist->id_country;
		
			$region_exist = Regions::model()->find("name=:name AND id_country=$country_id", array(':name'=>$region));
			
			if(empty($region_exist)&& $region!=""){
                $region_exist=new Regions;
                $region_exist->name = $region;
                $region_exist->id_country = $country_id;
                $region_exist->save();
            }
			$region_id = $region_exist->id_region;
		
      $item = Settlements::model()->find("name=:name AND id_region=$region_id", array(':name'=>$name));
			
      if(empty($item) && $name!=""){
          $item=new Settlements;
          $item->name = $name;
          $item->id_region = $region_id;
          $item->save();
      }
      echo CJSON::encode(array('id'=>$item->id_settlement, 'name'=>$item->name));
      Yii::app()->end();
    }
        
        
	public function actionAutocomplete()
	{
		$term = Yii::app()->getRequest()->getParam('term');
		$region_id = (int)Yii::app()->getRequest()->getParam('region_id');
		$country_id = (int)Yii::app()->getRequest()->getParam('country_id');

		if(Yii::app()->request->isAjaxRequest && $term) {

			$cond = "t.name LIKE :term";
			if($region_id) $cond .= " AND t.id_region = $region_id ";
			if($country_id) $cond.= " AND t.id_region IN ( SELECT id_region FROM regions WHERE id_country = $country_id )";
	

	
			//$command = Yii::app()->db->createCommand("SELECT settlements.name, settlements.kod_t_st AS kodTSt, settlements.id_region AS idRegion, settlements.id_area AS idArea, regions.kod_t_st AS ktsr , 	areas.kod_t_st AS ktsa FROM settlements LEFT JOIN regions ON settlements.id_region = regions.id_region  	LEFT JOIN areas ON settlements.id_region = areas.id_region  WHERE ($cond)");
			//$command = Yii::app()->db->createCommand("SELECT settlements.name, settlements.kod_t_st AS kodTSt, settlements.id_region AS idRegion, settlements.id_area AS idArea FROM settlements WHERE ($cond)");
		//print_r($command);
			//$items = $command->queryAll();
				
		
			
			$items = Settlements::model()->with(array('kodTSt', 'idRegion', 'idArea', 'idRegion.kodTSt'=>array('alias'=>'ktsr'), 'idArea.kodTSt'=>array('alias'=>'ktsa')))->findAll(array('condition' => $cond, 'limit' => 5, 'params' => array(':term' => $term.'%')) );
		
			$result = array();
			foreach($items as $item) {
			
				$label = $item['name'];
				$desc = $item['kodTSt']->socrname.", ";
				if($item['idArea']) $desc .= $item['idArea']->name . ' ' . mb_strtolower($item['idArea']->kodTSt->socrname,'UTF-8') .', ';
				$desc .= $item['idRegion']->name . ' ' . mb_strtolower($item['idRegion']->kodTSt->socrname,'UTF-8');
				$result[] = array('id'=>$item['id_settlement'], 'label'=>$label, 'desc' => $desc, 'value'=>$label);
			}
			echo CJSON::encode($result);
			Yii::app()->end();
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Settlements the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Settlements::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Settlements $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='settlements-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
