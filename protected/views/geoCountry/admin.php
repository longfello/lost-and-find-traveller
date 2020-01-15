<?php
/* @var $this GeoCountryController */
/* @var $model GeoCountry */

$this->breadcrumbs=array(
	'Страны'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Добавить страну', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#geo-country-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Список стран</h1>

<?php $this->widget('bootstrap.widgets.BsGridView', array(
	'id'=>'geo-country-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'iso_alpha2',
		'iso_alpha3',
		'iso_numeric',
		'fips_code',
		'name_ru',
		/*
		'name_en',
		'capital',
		'areainsqkm',
		'population',
		'continent',
		'tld',
		'currency',
		'currencyName',
		'Phone',
		'postalCodeFormat',
		'postalCodeRegex',
		'geonameId',
		'languages',
		'neighbours',
		'equivalentFipsCode',
		*/
		array(
			'class'=>'CButtonColumn',
      'template' => '<nobr>{update}{delete}</nobr>'
		),
	),
)); ?>
