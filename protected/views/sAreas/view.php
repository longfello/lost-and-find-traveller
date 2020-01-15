<?php
/* @var $this SAreasController */
/* @var $model SAreas */

$this->breadcrumbs=array(
	'Внутригородские районы'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Список районов', 'url'=>array('index')),
	array('label'=>'Добавить район', 'url'=>array('create')),
	array('label'=>'Редактировать район', 'url'=>array('update', 'id'=>$model->id_sa)),
	array('label'=>'Удалить район', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_sa),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Управление районами', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_sa',
		'name',
		array(
		   'label'=>'Город',
		   'value'=>$model->idSettlement->name,
		),
	),
)); ?>
