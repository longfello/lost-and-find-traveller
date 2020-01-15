<?php
/* @var $this AreasController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Районы',
);

$this->menu=array(
	array('label'=>'Добавить район', 'url'=>array('create')),
	array('label'=>'Управление районами', 'url'=>array('admin')),
);
?>

<h1>Районы</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
