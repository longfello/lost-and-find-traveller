<?php
/* @var $this CategoryController */
/* @var $model Category */

$this->breadcrumbs=array(
	'Categories'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'Список категорий', 'url'=>array('index')),
	array('label'=>'Создать Категорию', 'url'=>array('create')),
	array('label'=>'Изменить Категорию', 'url'=>array('update', 'id'=>$model->id_cat)),
	array('label'=>'Удалить Категорию', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_cat),'confirm'=>'Are you sure you want to delete this item?')),

);
?>

<h1>Просмотр категории #<?php echo $model->id_cat; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_cat',
		'title',
	),
)); ?>
