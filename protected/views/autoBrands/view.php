<?php
/* @var $this AutoBrandsController */
/* @var $model AutoBrands */

$this->breadcrumbs=array(
	'Марки автомобилей'=>array('index'),
	$model->id_brand,
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Добавить', 'url'=>array('create')),
	array('label'=>'Изменить', 'url'=>array('update', 'id'=>$model->id_brand)),
	array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_brand),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>Марка #<?php echo $model->id_brand; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_brand',
		'brand',
	),
)); ?>
