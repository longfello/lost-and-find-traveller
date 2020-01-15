<?php
/* @var $this CategoryController */
/* @var $model Category */

$this->breadcrumbs=array(
	'Categories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Список категорий', 'url'=>array('index')),

);
?>

<h1>Добавление новой категории</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>