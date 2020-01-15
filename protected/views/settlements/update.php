<?php
/* @var $this SettlementsController */
/* @var $model Settlements */

$this->breadcrumbs=array(
	'Населённые пункты'=>array('index'),
	$model->name=>array('view','id'=>$model->id_settlement),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Список населённых пунктов', 'url'=>array('index')),
	array('label'=>'Добавить населённый пункт', 'url'=>array('create')),
	array('label'=>'Просмотреть населённый пункт', 'url'=>array('view', 'id'=>$model->id_settlement)),
	array('label'=>'Управление населёнными пунктами', 'url'=>array('admin')),
);
?>

<h1>Редактировать <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'countries'=>$countries, 'regions'=>$regions, 'socrnames'=>$socrnames, 'areas'=>$areas)); ?>