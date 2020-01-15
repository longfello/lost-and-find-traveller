<?php
/* @var $this GeoZoneController */
/* @var $model GeoZone */

$this->breadcrumbs=array(
	'Области'=>array('index'),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Список областей', 'url'=>array('index')),
	array('label'=>'Добавить область', 'url'=>array('create')),
);
?>

<h1>Редактирование области #<?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>