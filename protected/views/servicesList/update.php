<?php
/* @var $this ServicesListController */
/* @var $model ServicesList */

$this->breadcrumbs=array(
	'Services Lists'=>array('index'),
	$model->name=>array('view','id'=>$model->id_sl),
	'Update',
);

$this->menu=array(
	array('label'=>'List ServicesList', 'url'=>array('index')),
	array('label'=>'Create ServicesList', 'url'=>array('create')),
	array('label'=>'View ServicesList', 'url'=>array('view', 'id'=>$model->id_sl)),
	array('label'=>'Manage ServicesList', 'url'=>array('admin')),
);
?>

<h1>Update ServicesList <?php echo $model->id_sl; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>