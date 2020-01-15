<?php
/* @var $this HotelServiceController */
/* @var $model HotelService */

$this->breadcrumbs=array(
	'Hotel Services'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List HotelService', 'url'=>array('index')),
	array('label'=>'Create HotelService', 'url'=>array('create')),
	array('label'=>'Update HotelService', 'url'=>array('update', 'id'=>$model->id_hs)),
	array('label'=>'Delete HotelService', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_hs),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage HotelService', 'url'=>array('admin')),
);
?>

<h1>View HotelService #<?php echo $model->id_hs; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_hs',
		'title',
		'address',
		'place_desc',
		'description',
		'phones',
		'site_link',
		'coord_x',
		'coord_y',
		'type',
		'guest_rating',
		'stars_rating',
		'square',
		'price_from',
		'price_to',
	),
)); ?>
