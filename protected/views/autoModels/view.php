<?php
/* @var $this AutoModelsController */
/* @var $model AutoModels */

$this->breadcrumbs=array(
	'Модели автомобилей'=>array('index'),
	$model->id_model,
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Добавить', 'url'=>array('create')),
	array('label'=>'Изменить', 'url'=>array('update', 'id'=>$model->id_model)),
	array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_model),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>Просмотр модели #<?php echo $model->id_model; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_model',
		array(
		   'label'=>'Бренд',
		   'value'=>$model->brand->brand,
		 ),
		'model',
	),
)); ?>
