<?php
/* @var $this CountriesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Страны',
);

$this->menu=array(
	array('label'=>'Добавить страну', 'url'=>array('create')),
	array('label'=>'Управление странами', 'url'=>array('admin')),
);
?>

<h1>Страны</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
