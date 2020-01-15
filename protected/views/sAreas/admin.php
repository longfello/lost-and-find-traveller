<?php
/* @var $this SAreasController */
/* @var $model SAreas */

$this->breadcrumbs=array(
	'Внутригородские районы'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Список районов', 'url'=>array('index')),
	array('label'=>'Добавить район', 'url'=>array('create')),
);

?>

<h1>Управление районами</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'sareas-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id_sa',
		'name',
		array('name'=>'settlement_search', 'value'=>'$data->idSettlement->name'),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
