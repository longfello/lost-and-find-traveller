<?php
/* @var $this AreasController */
/* @var $model Areas */

$this->breadcrumbs=array(
	'Районы'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Список районов', 'url'=>array('index')),
	array('label'=>'Добавить район', 'url'=>array('create')),
	array('label'=>'Изменить район', 'url'=>array('update', 'id'=>$model->id_area)),
	array('label'=>'Удалить район', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_area),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Управление районами', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->name.', '.$model->kodTSt->socrname; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_area',
		'aoid',
		array(
		   'label'=>'Регион',
		   'value'=>$model->idRegion->name.', '.$model->idRegion->kodTSt->socrname,
		),
		array(
		   'label'=>'Тип',
		   'value'=>$model->kodTSt->socrname,
		),
	),
)); ?>
