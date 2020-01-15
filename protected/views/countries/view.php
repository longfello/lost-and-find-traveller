<?php
/* @var $this CountriesController */
/* @var $model Countries */

$this->breadcrumbs=array(
	'Страны'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Список стран', 'url'=>array('index')),
	array('label'=>'Добавить страну', 'url'=>array('create')),
	array('label'=>'Изменить страну', 'url'=>array('update', 'id'=>$model->id_country)),
	array('label'=>'Удалить страну', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_country),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Управление странами', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_country',
		'name',
	),
)); ?>
