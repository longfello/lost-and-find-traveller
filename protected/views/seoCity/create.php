<?php
/* @var $this SeoCityController */
/* @var $model SeoCity */

$this->breadcrumbs=array(
	'Seo Cities'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SeoCity', 'url'=>array('index')),
	array('label'=>'Manage SeoCity', 'url'=>array('admin')),
);
?>

<h1>Create SeoCity</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>