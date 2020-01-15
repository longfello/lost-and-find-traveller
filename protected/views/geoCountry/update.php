<?php
/* @var $this GeoCountryController */
/* @var $model GeoCountry */

$this->breadcrumbs=array(
	'Страны'=>array('index'),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Добавить страну', 'url'=>array('create')),
	array('label'=>'Список стран', 'url'=>array('index')),
);
?>

<?php echo BsHtml::pageHeader('Страны',"редактирование #{$model->id} ({$model->name_ru})"); ?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>