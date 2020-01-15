<?php
/* @var $this ArticleCategoryController */
/* @var $model ArticleCategory */

$this->breadcrumbs=array(
	'Article Categories'=>array('index'),
	$model->name=>array('view','id'=>$model->id_cat),
	'Update',
);

$this->menu=array(
	array('label'=>'List ArticleCategory', 'url'=>array('index')),
	array('label'=>'Create ArticleCategory', 'url'=>array('create')),
	array('label'=>'View ArticleCategory', 'url'=>array('view', 'id'=>$model->id_cat)),
	array('label'=>'Manage ArticleCategory', 'url'=>array('admin')),
);
?>

<h1>Update ArticleCategory <?php echo $model->id_cat; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>