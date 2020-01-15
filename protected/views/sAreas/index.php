<?php
/* @var $this SAreasController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Внутригородские районы',
);

$this->menu=array(
	array('label'=>'Добавить район', 'url'=>array('create')),
	array('label'=>'Управление районами', 'url'=>array('admin')),
);
?>

<h1>Внутригородские районы</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
