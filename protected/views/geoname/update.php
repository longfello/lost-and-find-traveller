<?php
/* @var $this GeonameController */
/* @var $model Geoname */

$this->breadcrumbs=array(
	'Населенные пункты'=>array('index'),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Список населенных пунктов', 'url'=>array('index')),
//	array('label'=>'Добавить населенный пункт', 'url'=>array('create')),
);
?>

<h1>Редактирование населенного пункта #<?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'seoModel' => $seoModel)); ?>