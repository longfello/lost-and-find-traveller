<?php
/* @var $this AreasController */
/* @var $model Areas */

$this->breadcrumbs=array(
	'Районы'=>array('index'),
	$model->name=>array('view','id'=>$model->id_area),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Список районов', 'url'=>array('index')),
	array('label'=>'Добавить район', 'url'=>array('create')),
	array('label'=>'Просмотреть район', 'url'=>array('view', 'id'=>$model->id_area)),
	array('label'=>'Управление районами', 'url'=>array('admin')),
);
?>

<h1>Изменить <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'regions'=>$regions, 'socrnames'=>$socrnames)); ?>