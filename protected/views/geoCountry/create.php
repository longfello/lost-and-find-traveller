<?php
/* @var $this GeoCountryController */
/* @var $model GeoCountry */

$this->breadcrumbs=array(
    'Страны'=>array('index'),
    'Добавление',
);

$this->menu=array(
    array('label'=>'Добавить страну', 'url'=>array('create')),
    array('label'=>'Список стран', 'url'=>array('index')),
);
?>

<h1>Добавить страну</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>