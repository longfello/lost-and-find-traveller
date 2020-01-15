<?php
/* @var $this CategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Categories',
);

$this->menu=array(
	array('label'=>'Добавить категорию', 'url'=>array('create')),

);
?>

<h1>Категории объектов бюро находок</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'category-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id_cat',
		'title',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>