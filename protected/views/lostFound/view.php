<?php
/* @var $this LostFoundController */
/* @var $model LostFound */

$this->breadcrumbs=array(
	'Lost Founds'=>array('index'),
	$model->id_lf,
);

$this->menu=array(
	array('label'=>'List LostFound', 'url'=>array('index')),
	array('label'=>'Create LostFound', 'url'=>array('create')),
	array('label'=>'Update LostFound', 'url'=>array('update', 'id'=>$model->id_lf)),
	array('label'=>'Delete LostFound', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_lf),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage LostFound', 'url'=>array('admin')),
);
?>

<h1>View LostFound #<?php echo $model->id_lf; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_lf',
		'id_user',
		'category',
		'lost_or_found',
		'city',
		'place_disc',
		'thing',
		'operator',
		'status',
		'date_filing',
		'date_lf',
		'comment',
		'phone',
		'active_code',
	),
)); ?>
