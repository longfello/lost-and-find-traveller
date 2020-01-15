<?php
/* @var $this RegionsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Регионы',
);

$this->menu=array(
	array('label'=>'Добавить регион', 'url'=>array('create')),
	array('label'=>'Управление регионами', 'url'=>array('admin')),
);
?>

<h1>Регионы</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
