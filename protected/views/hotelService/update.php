<?php
/* @var $this HotelServiceController */
/* @var $model HotelService */

$this->breadcrumbs=array(
	'Hotel Services'=>array('index'),
	$model->title=>array('view','id'=>$model->id_hs),
	'Update',
);


$this->menu=array(
	array('label'=>'List HotelService', 'url'=>array('index')),
	array('label'=>'Create HotelService', 'url'=>array('create')),
	array('label'=>'View HotelService', 'url'=>array('view', 'id'=>$model->id_hs)),
	array('label'=>'Manage HotelService', 'url'=>array('admin')),
);
?>

<h1>Редактирование карточки <?php echo $model->title; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'userdata'=> $userdata,)); ?>