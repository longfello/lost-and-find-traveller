<?php
/* @var $this CountriesController */
/* @var $model Countries */

$this->breadcrumbs=array(
	'Страны'=>array('index'),
	'Добавить страну',
);

$this->menu=array(
	array('label'=>'Список стран', 'url'=>array('index')),
	array('label'=>'Управление странами', 'url'=>array('admin')),
);
?>

<h1>Добавить страну</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>