<?php
/* @var $this ReferensController */
/* @var $model Referens */

$this->breadcrumbs=array(
	'Referens'=>array('index'),
	$model->IdRef=>array('view','id'=>$model->IdRef),
	'Update',
);

$this->menu=array(
	array('label'=>'List Referens', 'url'=>array('index')),
	array('label'=>'Create Referens', 'url'=>array('create')),
	array('label'=>'View Referens', 'url'=>array('view', 'id'=>$model->IdRef)),
	array('label'=>'Manage Referens', 'url'=>array('admin')),
);
?>

<h1>Update Referens <?php echo $model->IdRef; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>