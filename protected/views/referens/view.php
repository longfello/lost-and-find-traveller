<?php
/* @var $this ReferensController */
/* @var $model Referens */

$this->breadcrumbs=array(
	'Referens'=>array('index'),
	$model->IdRef,
);

$this->menu=array(
	array('label'=>'List Referens', 'url'=>array('index')),
	array('label'=>'Create Referens', 'url'=>array('create')),
	array('label'=>'Update Referens', 'url'=>array('update', 'id'=>$model->IdRef)),
	array('label'=>'Delete Referens', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->IdRef),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Referens', 'url'=>array('admin')),
);
?>

<h1>View Referens #<?php echo $model->IdRef; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'IdRef',
		'AliasRef',
		'NameRef',
		'TurnRef',
		'OwnerRef',
	),
)); ?>
