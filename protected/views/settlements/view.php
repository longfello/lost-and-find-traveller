<?php
/* @var $this SettlementsController */
/* @var $model Settlements */

$this->breadcrumbs=array(
	'Населённые пункты'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Список населённых пунктов', 'url'=>array('index')),
	array('label'=>'Добавить населённый пункт', 'url'=>array('create')),
	array('label'=>'Редактировать населённый пункт', 'url'=>array('update', 'id'=>$model->id_settlement)),
	array('label'=>'Удалить населённый пункт', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_settlement),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Управление населёнными пунктами', 'url'=>array('admin')),
);
?>

<h1><?php echo CHtml::encode($model->name).', '.CHtml::encode($model->kodTSt->socrname); ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_settlement',
		'name',
		'aoid',
		array(
		   'label'=>'Регион',
		   'value'=>$model->idRegion->name.', '.$model->idRegion->kodTSt->socrname,
		 ),
		'id_area',
	),
)); ?>
