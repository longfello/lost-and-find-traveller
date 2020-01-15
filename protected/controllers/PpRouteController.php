<?php

class PpRouteController extends AController
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
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
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

		if(isset($_POST['PpRoute']))
		{
			$model->attributes=$_POST['PpRoute'];
			if($model->save())
				$this->redirect(array('index'));
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
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model=new PpRoute('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PpRoute']))
			$model->attributes=$_GET['PpRoute'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

  public function actionImport(){
    $min_id = Core::getGet('min_id', 0);
    if (Yii::app()->request->isAjaxRequest) {
      $action = Core::getPost('action', 'get');
      switch($action){
        case 'get':
          $min_id = Core::getPost('id', $min_id, type_int);
          $criteria = New CDbCriteria();
          $criteria->addCondition("type_route = 2");
          $criteria->addCondition("id_order > $min_id");
          $criteria->order = "id_order ASC";
          $criteria->limit = 1;
          $order = PoputchikOrder::model()->find($criteria);
          if ($order) {
            if ($route = PpRoute::model()->countByAttributes(array('old_id'=>$order->id_order)) == 0) {
              $path = Paths::model()->findByPk($order->id_path);
              $from = Geoname::model()->findByAttributes(array('old_id' => $path->id_settlement_1));
              $to   = Geoname::model()->findByAttributes(array('old_id' => $path->id_settlement_2));
              if ($from && $to) {
                echo(json_encode(array(
                    'result' => 'process',
                    'id'     => $order->id_order,
                    'start'  => array($from->latitude, $from->longitude),
                    'stop'   => array($to->latitude, $to->longitude),
                )));
              } else {
                echo(json_encode(array(
                    'result' => 'skip',
                    'id'     => $order->id_order
                )));
              }
            } else {
              echo(json_encode(array(
                  'result' => 'skip',
                  'id'     => $order->id_order
              )));
            }
          } else {
            echo(json_encode(array(
                'result' => 'end',
            )));
          }
          break;
        case 'save':
          $id = Core::getPost('id', 0, type_int);
          $order = PoputchikOrder::model()->findByPk($id);
          if ($order) {
            $path = Paths::model()->findByPk($order->id_path);
            $from = Geoname::model()->findByAttributes(array('old_id' => $path->id_settlement_1));
            $to   = Geoname::model()->findByAttributes(array('old_id' => $path->id_settlement_2));
            if ($from && $to) {
              if (PpRoute::model()->findByAttributes(array('old_id'=>$order->id_order))) return;

              $path0 = Core::getPost('path', array());

              $path = array();
              foreach($path0 as $p){
                $path[] = array(
                  'lng' => $p['lng'],
                  'lat' => $p['lat'],
                );
              }
              $path[] = array('lng' => $to->longitude, 'lat' => $to->latitude);
              array_unshift($path, array('lng' => $from->longitude, 'lat' => $from->latitude));

              $short_path = array(
                array('lng' => $from->longitude, 'lat' => $from->latitude),
                array('lng' => $to->longitude,   'lat' => $to->latitude)
              );

              $route = new PpRoute();
              $route->old_id = $order->id_order;

              $data_post = array(
                  'available_until' => array(
                      'date' => date('d.m.Y', time()+30*24*60*60),
                      'time' => array(
                          'H' => 0,
                          'm' => 0,
                      )
                  ),
                  'periodicity-type' => 'once',
                  'periodicity-weekly-days' => null,
                  'returnAt' => array(
                      'date' => 0,
                      'time' => array(
                          'H' => 0,
                          'm' => 0,
                      )
                  ),
                  'startAt' => array(
                      'date' => date('d.m.Y', strtotime($order->date_to)),
                      'time' => array(
                          'H' => 0,
                          'm' => 0,
                      )
                  ),
                  'avatar' => '',
                  'cost' => array(
                      'forPlace' => 1,
                      'price' => intval($order->sum),
                  ),
                  'email' => '',
                  'end-location' => array(
                      'id'   => $to->id,
                      'lat'  => $to->latitude,
                      'lng'  => $to->longitude
                  ),
                  'start-location' => array(
                      'id'   => $from->id,
                      'lat'  => $from->latitude,
                      'lng'  => $from->longitude
                  ),
                  'i_approve' => true,
                  'name' => $order->name,
                  'path' => $path,
                  'short-path' => $short_path,
                  'phone' => $order->phone,
                  'places' => 1,
                  'comment' => $order->comment
              );

              $data = Core::setArrayDefaults($data_post, array(
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
                  'comment' => ''
              ));

              $periodicity = null;
              $departure = PpRouteHelper::convertDate($data['startAt']);


              // print_r($data);

              $attributes = array(
                  'uid'                  => PpRouteHelper::getOrCreateUserID($data),
                  'from_id'              => intval($data['start-location']['id']),
                  'to_id'                => intval($data['end-location']['id']),
                  'from'                 => array(floatval($data['start-location']['lng']), floatval($data['start-location']['lat'])),
                  'to'                   => array(floatval($data['end-location']['lng']), floatval($data['end-location']['lat'])),
                  'path'                 => PpRouteHelper::array2path($data['short-path']),
                  'path_full'            => PpRouteHelper::array2path($data['path']),
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
                  'enabled'              => 0,
              );

              // print_r($attributes);

              // die();

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
                print_r($result['messages']['overall'] = $errors);
              } else {
                $result['result'] = 'ok';
              }
            }
          }
          break;
      }
      Yii::app()->end();

      // $criteria->params[':type_route'] = 2;
    } else {
      $criteria = New CDbCriteria();
      $criteria->addCondition("id_order > $min_id");
      $criteria->addCondition("type_route = 2");
      $this->render('import', array(
        'count' => PoputchikOrder::model()->count($criteria),
        'min_id' => $min_id
      ));
    }
  }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return PpRoute the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=PpRoute::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param PpRoute $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='pp-route-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
