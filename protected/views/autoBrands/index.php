<?php
/* @var $this AutoBrandsController */
/* @var $model AutoBrands */

$this->breadcrumbs=array(
	'Марки автомобилей',
);

$this->menu=array(
	array('label'=>'Добавить', 'url'=>array('create')),
);
?>

<h1>Марки автомобилей</h1>

<?php $this->widget('bootstrap.widgets.BsGridView', array(
	'id'=>'auto-brands-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id_brand',
      array(
          'name'=>'brand',
          'value'=>'$data->brand',
          'filter' => CHtml::listData(AutoBrands::model()->findAll(array('order'=>'brand')), 'brand', 'brand')
      ),
		array(
			'class'=>'CButtonColumn',
        'template' => '<nobr>{update}{delete}</nobr>'
		),
	),
)); ?>
