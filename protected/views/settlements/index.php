<?php
/* @var $this SettlementsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Населённые пункты',
);

$this->menu=array(
	array('label'=>'Добавить населённый пункт', 'url'=>array('create')),
	array('label'=>'Управление населёнными пунктами', 'url'=>array('admin')),
);
?>

<h1>Населённые пункты</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
