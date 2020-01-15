<?php
/* @var $this LostFoundController */
/* @var $model LostFound */

$this->breadcrumbs=array(
	'Lost Founds'=>array('index'),
	$model->id_lf=>array('view','id'=>$model->id_lf),
	'Update',
);

$this->menu=array(
	array('label'=>'List LostFound', 'url'=>array('index')),
	array('label'=>'Create LostFound', 'url'=>array('create')),
	array('label'=>'View LostFound', 'url'=>array('view', 'id'=>$model->id_lf)),
	array('label'=>'Manage LostFound', 'url'=>array('admin')),
);
?>

<h1>Update LostFound <?php echo $model->id_lf; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,	'categories'=>$categories,	'user_data'=>$user_data)); ?>