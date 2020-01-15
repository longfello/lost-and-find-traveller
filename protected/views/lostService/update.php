<?php
/* @var $this LostServiceController */
/* @var $model LostService */

$this->breadcrumbs=array(
	'Lost Services'=>array('index'),
	$model->name=>array('view','id'=>$model->id_ls),
	'Update',
);

$this->menu=array(
	array('label'=>'List LostService', 'url'=>array('index')),
	array('label'=>'Create LostService', 'url'=>array('create')),
	array('label'=>'View LostService', 'url'=>array('view', 'id'=>$model->id_ls)),
	array('label'=>'Manage LostService', 'url'=>array('admin')),
);
?>

<h1>Update LostService <?php echo $model->id_ls; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>