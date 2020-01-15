<?php
/* @var $this CountriesController */
/* @var $model Countries */

$this->breadcrumbs=array(
	'Страны'=>array('index'),
	$model->name=>array('view','id'=>$model->id_country),
	'Редактировать страну',
);

$this->menu=array(
	array('label'=>'Список стран', 'url'=>array('index')),
	array('label'=>'Добавить страну', 'url'=>array('create')),
	array('label'=>'Просмотреть страну', 'url'=>array('view', 'id'=>$model->id_country)),
	array('label'=>'Управление странами', 'url'=>array('admin')),
);
?>

<h1>Редактировать <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>