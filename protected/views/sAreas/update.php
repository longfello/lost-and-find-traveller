<?php
/* @var $this SAreasController */
/* @var $model SAreas */

$this->breadcrumbs=array(
	'Внутригородские районы'=>array('index'),
	$model->name=>array('view','id'=>$model->id_sa),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Список районов', 'url'=>array('index')),
	array('label'=>'Добавить район', 'url'=>array('create')),
	array('label'=>'Просмотреть район', 'url'=>array('view', 'id'=>$model->id_sa)),
	array('label'=>'Управление районами', 'url'=>array('admin')),
);
?>

<h1>Редактировать <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>