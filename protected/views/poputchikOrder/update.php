<?php
/* @var $this PoputchikOrderController */
/* @var $model PoputchikOrder */

$this->breadcrumbs=array(
	'Poputchik Orders'=>array('index'),
	$model->name=>array('view','id'=>$model->id_order),
	'Update',
);

$this->menu=array(
	array('label'=>'List PoputchikOrder', 'url'=>array('index')),
	array('label'=>'Create PoputchikOrder', 'url'=>array('create')),
	array('label'=>'View PoputchikOrder', 'url'=>array('view', 'id'=>$model->id_order)),
	array('label'=>'Manage PoputchikOrder', 'url'=>array('admin')),
);
?>

<h1>Редактирование заявки попутчик №<?php echo $model->id_order; ?></h1>

<?php $this->renderPartial('_form', array_merge(array('model'=>$model,	'user_data'=>$user_data), $this->getLists($model))); ?>