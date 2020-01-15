<?php
/* @var $this AutoBrandsController */
/* @var $model AutoBrands */

$this->breadcrumbs=array(
	'Auto Brands'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Список марок', 'url'=>array('index')),
);
?>

<h1>Добавить марку авто</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>