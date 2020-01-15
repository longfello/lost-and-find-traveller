<?php
/* @var $this SAreasController */
/* @var $model SAreas */

$this->breadcrumbs=array(
	'Внутригородские районы'=>array('index'),
	'Добавление',
);

$this->menu=array(
	array('label'=>'Список районов', 'url'=>array('index')),
	array('label'=>'MУправление районами', 'url'=>array('admin')),
);
?>

<h1>Добавить район</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>