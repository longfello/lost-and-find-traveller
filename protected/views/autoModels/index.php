<?php
/* @var $this AutoModelsController */
/* @var $model AutoModels */

$this->breadcrumbs=array(
	'Модели автомобилей',
);

$this->menu=array(
	array('label'=>'Добавить', 'url'=>array('create')),
);

?>

<h1>Модели автомобилей</h1>

<?php $this->widget('bootstrap.widgets.BsGridView', array(
	'id'=>'auto-models-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id_model',
		array(
        'name'=>'brand_search',
        'value'=>'$data->brand->brand',
        'filter' => CHtml::listData(AutoBrands::model()->findAll(array('order'=>'brand')), 'brand', 'brand')
    ),
		'model',
		array(
      'name'   => 'class',
      'value'  => 'Yii::t("enums", $data->class)',
        'filter' => EnumAutoClasses::getDataForDropDown()
    ),
		array(
			'class'=>'CButtonColumn',
        'template' => '<nobr>{update}{delete}</nobr>'
		),
	),
)); ?>
