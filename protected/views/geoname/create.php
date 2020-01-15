<?php
/* @var $this GeonameController */
/* @var $model Geoname */

$this->breadcrumbs=array(
    'Населенные пункты'=>array('index'),
    'Добавление',
);

$this->menu=array(
    array('label'=>'Список населенных пунктов', 'url'=>array('index')),
);
?>

<h1>Добавить населенный пункт</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>