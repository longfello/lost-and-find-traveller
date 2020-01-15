<?php
/* @var $this SeoCityController */
/* @var $model SeoCity */

$this->breadcrumbs=array(
	'Seo Cities'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List SeoCity', 'url'=>array('index')),
	array('label'=>'Create SeoCity', 'url'=>array('create')),
	array('label'=>'Update SeoCity', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SeoCity', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SeoCity', 'url'=>array('admin')),
);
?>

<h1>View SeoCity #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'city_id',
		'seo_text',
	),
)); ?>
