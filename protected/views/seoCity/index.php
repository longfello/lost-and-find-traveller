<?php
/* @var $this SeoCityController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Seo Cities',
);

$this->menu=array(
	array('label'=>'Create SeoCity', 'url'=>array('create')),
	array('label'=>'Manage SeoCity', 'url'=>array('admin')),
);
?>

<h1>Seo Cities</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
