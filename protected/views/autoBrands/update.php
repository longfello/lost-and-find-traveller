<?php
/* @var $this AutoBrandsController */
/* @var $model AutoBrands */

$this->breadcrumbs=array(
	'Марки автомобилей'=>array('index'),
	$model->id_brand=>array('view','id'=>$model->id_brand),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Добавить', 'url'=>array('create')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->id_brand)),
);
?>

<h1>Редактирование</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>