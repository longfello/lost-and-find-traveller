<?php
/* @var $this ServicesListController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Services Lists',
);

$this->menu=array(
	array('label'=>'Create ServicesList', 'url'=>array('create')),
	array('label'=>'Manage ServicesList', 'url'=>array('admin')),
);
?>

<h1>Services Lists</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
