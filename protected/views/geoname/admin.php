<?php
/* @var $this GeonameController */
/* @var $model Geoname */

$this->breadcrumbs=array(
	'Geonames'=>array('index'),
	'Manage',
);

$this->menu=array(
//	array('label'=>'Добавить населенный пункт', 'url'=>array('create')),
);
Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#geoname-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>Список населенных пунктов</h1>

<?php $this->widget('bootstrap.widgets.BsGridView', array(
  // $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'geoname-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
    'id',
    'name_ru',
      array(
          'name' => 'zone',
          'value' => '$data->zoneModel->name_ru',
          'filter' => CHtml::listData(GeoZone::model()->findAll(array('order'=>'name_ru')), 'id', 'name_ru'),
      ),
      array(
          'name' => 'country',
          'value' => '$data->countryModel->name_ru',
          'filter' => CHtml::listData(GeoCountry::model()->findAll(array('order'=>'name_ru')), 'id', 'name_ru'),
      ),
      'name_en',
      'latitude',
      'longitude',
      'slug',
		/*
		'population',
		'timezone',
		'dia',
		'coord',
		'area',
		't',
		*/
		array(
			'class'=>'CButtonColumn',
      'template' => '<nobr>{update}{delete}</nobr>'
		),
	),
)); ?>
