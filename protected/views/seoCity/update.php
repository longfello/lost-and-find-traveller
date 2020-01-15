<?php
/* @var $this SeoCityController */
/* @var $model SeoCity */

$this->breadcrumbs=array(
	'Seo Cities'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SeoCity', 'url'=>array('index')),
	array('label'=>'Create SeoCity', 'url'=>array('create')),
	array('label'=>'View SeoCity', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SeoCity', 'url'=>array('admin')),
);
?>

<h1>Update SeoCity <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>