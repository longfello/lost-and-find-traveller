<?php

class HotelServiceController extends AController
{
  public $service_id = SERVICE_NOCHLEG;
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';
	public $pageTitle = "Единая информационная служба - Ночлег";
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
			'DomainControl', 
		);
	}
	public function filterDomainControl( $filterChain )
	{
		if(  Yii::app()->params['subdomain']  !=  SERVICE_NOCHLEG )
			throw new CHttpException(404, 'Страница не найдена');
		$filterChain->run();
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
				'actions'=>array('GuestVote','index','view','create','AddPhoto','getPhone','details'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
					'actions'=>array('update','Del_my'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','toModerate','moderate'),
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
	public function actionGuestVote(){
	
		if(isset($_POST)){
			$arr ['key']=0;
			$model=HotelService::model()->findByPk($_POST['id']);
			if($_POST['sign'] == 'positive' ){
				$model->guest_rating_positive++;			
			}else{			
				$model->guest_rating_negative++;				
			}
				$arr ['positive']=	 (int) $model->guest_rating_positive;	
				$arr ['negative']=	 (int) $model->guest_rating_negative;	
				$sum= ($model->guest_rating_positive+$model->guest_rating_negative);
				$arr ['total']=   ($sum? round((10*$model->guest_rating_positive/$sum),1 ):0);
			if(!$model->save(false) )
				$arr ['key']=1;			
		
			print json_encode($arr);
		}
	
	
	}
	public function actionGetPhone() {
        if ($_POST) {
            $o = $this->loadModel($_POST['id_order']);
            print json_encode('<div class="phone  phone-ready">' . $o->phones . '</div>');
            Yii::app()->end();
        }
    }
	 
    public function actionAddPhoto()
    {
        // HTTP headers for no cache etc
        header('Content-type: text/plain; charset=UTF-8');
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        // Settings

        $targetDir = $_SERVER['DOCUMENT_ROOT']. DIRECTORY_SEPARATOR ."files". DIRECTORY_SEPARATOR ."hotelservice". DIRECTORY_SEPARATOR ."tmp";
        $cleanupTargetDir = false; // Remove old files
        $maxFileAge = 60 * 60; // Temp file age in seconds
     
        // 5 minutes execution time
        @set_time_limit(5 * 60);



        $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';
        $fileName = preg_replace('/[^\w\._\s]+/', '', $fileName);
        $file = explode('.', $fileName);
        $fileName =  $file[0].date('YmdHis').".". $file[1];

        // Create target dir
        if (!file_exists($targetDir))
            @mkdir($targetDir);

        // Remove old temp files
        if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
            while (($file = readdir($dir)) !== false) {
                $filePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // Remove temp files if they are older than the max age
                if ( (filemtime($filePath) < time() - $maxFileAge))
                    @unlink($filePath);
            }

            closedir($dir);
        } else{
            print '{"OK":0, "error":"Cant open temporary directory."}';
        }
        // Look for the content type header
        if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
            $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

        if (isset($_SERVER["CONTENT_TYPE"]))
            $contentType = $_SERVER["CONTENT_TYPE"];

        if (strpos($contentType, "multipart") !== false) {
            if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                // Open temp file
                $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName,  "wb" );
                if ($out) {
                    // Read binary input stream and append it to temp file
                    $in = fopen($_FILES['file']['tmp_name'], "rb");

                    if ($in) {
                        while ($buff = fread($in, 4096))
                            fwrite($out, $buff);
                    } else
                        print '{"OK":0, "error":"Cant open input stream."}';

                    fclose($out);
                    unlink($_FILES['file']['tmp_name']);
                } else
                    print '{"OK":0, "error":"Cant open output stream."}';
            } else
                print '{"OK":0, "error":"Cant move uploaded file."}';

        }

        // After last chunk is received, process the file



            $originalname = $fileName;
            if (isset($_SERVER['HTTP_CONTENT_DISPOSITION'])) {
                $arr = array();
                preg_match('@^attachment; filename="([^"]+)"@',$_SERVER['HTTP_CONTENT_DISPOSITION'],$arr);
                if (  isset($arr[1])  )
                    $originalname = $arr[1];
            }
            $photoLink= "http://" . $_SERVER['HTTP_HOST'].DIRECTORY_SEPARATOR ."files". DIRECTORY_SEPARATOR ."hotelservice". DIRECTORY_SEPARATOR ."tmp";

            list($width, $height, $type, $attr) = getimagesize ( $targetDir. DIRECTORY_SEPARATOR . $fileName );
            if($width > 1024 || $height > 1024) {
                $image = new EasyImage($targetDir. DIRECTORY_SEPARATOR . $fileName);
                $image->resize(1024, 1024);
                $image->save($targetDir . DIRECTORY_SEPARATOR . $fileName);
            }
            // **********************************************************************************************
            // Do whatever you need with the uploaded file, which has $originalname as the original file name
            // and is located at $targetDir . DIRECTORY_SEPARATOR . $fileName
            // **********************************************************************************************


        print '{"OK": 1, "id": "'.$originalname.'", "name": "'.$originalname.'", "path": "'.$photoLink . DIRECTORY_SEPARATOR . $fileName.'"}';
    }



	public function actionCreate()
	{
		$model=new HotelService;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
        $userdata =  array();
        $userdata['services']=ServicesList::model()->findAll(array('order'=>'name'));
        $userdata['rooms']=RoomType::model()->findAll(array('condition' => 'title!="NULL"','order'=>'title') );
	    $userdata['places']= CHtml::listData(RoomType::model()->findAll(array('condition' => 'title = "NULL"') ),  'id_rt', 'places', '')  ;

        $cu = Yii::app()->user;
      

        if ($cu->id ) {
            $user = User::model()->find( array('condition' => 'id=:id', 'params' => array(':id' => $cu->id)) );
			$userdata['phone']=  $user->username;
        }


		if(isset($_POST['HotelService']))
		{

			$model->attributes=$_POST['HotelService'];
           
			if($model->save()){
				if($model->activecode)
                    $this->redirect(array('general/activate_order','module'=>'hotelservice', 'id'=>$model->id_hs));
                else
                    $this->redirect(array('ru/order/complete'));
			}
		}

		$this->render('create',array(
			'model'=>$model,'userdata'=> $userdata,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$userdata =  array();
        $userdata['services']=ServicesList::model()->findAll();
        $userdata['rooms']=RoomType::model()->findAll(array('condition' => 'title!="NULL"') );
	    $userdata['places']= CHtml::listData(RoomType::model()->findAll(array('condition' => 'title = "NULL"') ),  'id_rt', 'places', '')  ;
	   
		$model=HotelService::model()->with( array('idSettlement','hotelServiceLists','hotelRoomsLists','hotelPhotoLists') )->findByPk($id);
	
	

		
		
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$cu = Yii::app()->user;
		
		 if ($cu->id ) {
            $user = User::model()->find( array('condition' => 'id=:id', 'params' => array(':id' => $cu->id)) );
			$userdata['phone']=  $user->username;
        }
		
		if($model->id_user != 	$cu->id && !$cu->checkAccess('operator') )
			  $this->redirect(array('/general/cabinet'));
		
		
		if(isset($_POST['HotelService']))
		{
			$model->attributes=$_POST['HotelService'];
			if($model->save())
				$this->redirect(array('details','id'=>$model->id_hs));
		}

		$this->render('update',array(
			'model'=>$model,'userdata'=> $userdata,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$Model = HotelService::model()->findByPk($id);
		$Model->delete();

			
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	 
	public function actionDel_my($id)
	{
		$order = $this->loadModel($id);
	
		$order->status=0;
		$order->save();
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	public function actionModerate($id)
	{
		$cu = Yii::app()->user;
		
		$o = $this->loadModel($id);
		$o->status = 1;
		$o->activecode = 1;

		$o->save(false);
		$this->redirect(array('toModerate'));
	}
	
	public function actionToModerate()
	{
		$orders=HotelService::model()->findAll(array('order'=>'id_hs ASC', 'condition'=>"status=0 AND activecode = 0"));
		$this->render('moderate',array(			'orders'=>$orders,	));
	}
	
	 
	 
	public function actionDetails($id){
	
	
		
	
		$order = HotelService::model()->with( array('idSettlement','hotelServiceLists','hotelRoomsLists','hotelPhotoLists') )->findAll(array('condition'=>"t.id_hs IN ($id) ") );

		$this->render('_details', array('order'=>$order[0])); 
		
		
		
	}
	 
	public function actionIndex()
	{
        $search = 0;
        $where = array('hs.status <> 0');
        $orders_ids = array();
		
		if( $_GET['id']) 
				$where[] = " id_hs = ".$_GET['id'];
		else if($_POST || $_GET['search'] || $_GET['id_city'] ) {

            $search = 1;
			if( isset( $_GET['id_city'] ) )
				$Settlement=Settlements::model()->findByPk( $_GET['id_city'] );
			
				$_SESSION['hs_filter']['settlement_id'] =    isset($_POST['settlement_id'])?( ($_POST['settlement_name'] == Yii::t('poputchik', "Город")) ? "" : $_POST['settlement_id']): 	$_SESSION['hs_filter']['settlement_id'];
				$_SESSION['hs_filter']['settlement_name'] =  isset($_POST['settlement_name'])?( ($_POST['settlement_name'] == Yii::t('poputchik', "Город")) ? "" : $_POST['settlement_name']): 	$_SESSION['hs_filter']['settlement_name'];
				$_SESSION['hs_filter']['type'] =   isset($_POST['type'])?   $_POST['type']:$_SESSION['hs_filter']['type'] ;
				$_SESSION['hs_filter']['price_from'] = isset($_POST['price_from'])? $_POST['price_from'] :	$_SESSION['hs_filter']['price_from'];
				$_SESSION['hs_filter']['price_to'] =  isset($_POST['price_to'])? $_POST['price_to'] :	$_SESSION['hs_filter']['price_to'];  
				if( isset($_SESSION['hs_filter']['price_from'] ) && isset($_SESSION['hs_filter']['price_to'] )  )				
					$where[]= " !( hs.price_from > ".$_SESSION['hs_filter']['price_to']." OR  hs.price_to <  " .$_SESSION['hs_filter']['price_from'].") ";
		
			if( isset( $Settlement ) ){	
		
				unset($_SESSION['hs_filter']);		
				$_SESSION['hs_filter']['settlement_id'] =   	$Settlement->id_settlement;
				$_SESSION['hs_filter']['settlement_name'] =   $Settlement->name;
			
			}
			
			if(isset($_SESSION['hs_filter']['type'] ))				
				$where[] = " hs.type IN(". implode(' , ', $_SESSION['hs_filter']['type'] ).")";				
				

			if( $_SESSION['hs_filter']['settlement_id']!="" )
				$where[] = " hs.id_settlement = ".$_SESSION['hs_filter']['settlement_id'];			
			
			
			
        }else unset($_SESSION['hs_filter']);

		
        $where_str = ' WHERE '.implode(' AND ', $where);
        $command = 'SELECT DISTINCT hs.id_hs FROM hotel_service hs '.$where_str.' ORDER BY id_hs DESC';

        $command = Yii::app()->db->createCommand($command);
        $orders = $command->queryAll();


        $count_orders = count($orders);
        if($count_orders > 10)  {
            $pages = (int)(($count_orders+9) / 10);
            $current_page = $_GET['page'] ? $_GET['page'] : 1;
            $orders = array_slice($orders, 10*($current_page-1), 10);
        }


        foreach( $orders as $order ) $orders_ids[] = $order['id_hs'];
        $orders_ids = implode(', ', $orders_ids );
       
        if($orders_ids) {
            $orders = HotelService::model()->with( array('idSettlement','hotelServiceLists','hotelRoomsLists','hotelPhotoLists_cover') )->findAll(array('condition'=>"t.id_hs IN ($orders_ids) ", 'order'=>'t.id_hs DESC' ) );
	
        }
        else $orders = array();

     
        $this->render('index',array(
            'orders'=>$orders,
            'search'=>$search,
            'pages'=>$pages,
        ));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new HotelService('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['HotelService']))
			$model->attributes=$_GET['HotelService'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return HotelService the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=HotelService::model()->with( array('idSettlement','hotelServiceLists','hotelRoomsLists','hotelPhotoLists_cover') )->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param HotelService $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='hotel-service-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
