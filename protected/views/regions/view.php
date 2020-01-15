<?php
/* @var $this RegionsController */
/* @var $model Regions */

$this->breadcrumbs=array(
	'Регионы'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Список регионов', 'url'=>array('index')),
	array('label'=>'Добавить регион', 'url'=>array('create')),
	array('label'=>'Изменить регион', 'url'=>array('update', 'id'=>$model->id_region)),
	array('label'=>'Удалить регион', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_region),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Управление регионами', 'url'=>array('admin')),
);
?>

<h1><?php echo CHtml::encode($model->name).', '.CHtml::encode($model->kodTSt->socrname); ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_region',
		'aoid',
		array(
		   'label'=>'Страна',
		   'value'=>$model->idCountry->name,
		 ),
	),
)); ?>
