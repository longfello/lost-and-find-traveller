<?php
/* @var $this ArticleCategoryController */
/* @var $model ArticleCategory */

$this->breadcrumbs=array(
	'Article Categories'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List ArticleCategory', 'url'=>array('index')),
	array('label'=>'Create ArticleCategory', 'url'=>array('create')),
	array('label'=>'Update ArticleCategory', 'url'=>array('update', 'id'=>$model->id_cat)),
	array('label'=>'Delete ArticleCategory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_cat),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ArticleCategory', 'url'=>array('admin')),
);
?>

<h1>View ArticleCategory #<?php echo $model->id_cat; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_cat',
		'name',
	),
)); ?>
