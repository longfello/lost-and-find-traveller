<?php
/* @var $this ReferensController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Referens',
);

$this->menu=array(
	array('label'=>'Create Referens', 'url'=>array('create')),
	array('label'=>'Manage Referens', 'url'=>array('admin')),
);
?>

<h1>Referens</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
