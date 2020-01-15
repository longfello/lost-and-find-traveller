<?php
/* @var $this PathsController */
/* @var $model Paths */

$this->breadcrumbs=array(
	'Пути'=>array('index'),
	$model->id_path,
);

$this->menu=array(
	array('label'=>'Список путей', 'url'=>array('index')),
	array('label'=>'Добавить путь', 'url'=>array('create')),
	array('label'=>'Редактировать путь', 'url'=>array('update', 'id'=>$model->id_path)),
	array('label'=>'Удалить путь', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_path),'confirm'=>'Are you sure you want to delete this item?')),
);

$s1 = $model->startSettlement->name.'<br /><span class="desc">';
$s1 .= $model->startSettlement->kodTSt->socrname.", ";
if($model->startSettlement->idArea) $s1 .= $model->startSettlement->idArea->name . ' ' . mb_strtolower($model->startSettlement->idArea->kodTSt->socrname,'UTF-8') .', ';
$s1 .= $model->startSettlement->idRegion->name . ' ' . mb_strtolower($model->startSettlement->idRegion->kodTSt->socrname,'UTF-8');

$s2 = $model->endSettlement->name.'<br /><span class="desc">';
$s2 .= $model->endSettlement->kodTSt->socrname.", ";
if($model->endSettlement->idArea) $s2 .= $model->endSettlement->idArea->name . ' ' . mb_strtolower($model->endSettlement->idArea->kodTSt->socrname,'UTF-8') .', ';
$s2 .= $model->endSettlement->idRegion->name . ' ' . mb_strtolower($model->endSettlement->idRegion->kodTSt->socrname,'UTF-8');

?>

<h1>Путь #<?php echo $model->id_path; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_path',
		array(
		   'label'=>'Пункт 1',
		   'value'=>$s1,
		   'type' => 'raw',
		 ),
		array(
		   'label'=>'Пункт 2',
		   'value'=>$s2,
		   'type' => 'raw',
		 ),
		'distance',
		'time',
	),
)); ?>
