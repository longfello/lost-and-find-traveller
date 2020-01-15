<?php
/* @var $this PathsController */
/* @var $model Paths */

$this->breadcrumbs=array(
	'Пути'
);

$this->menu=array(
	array('label'=>'Добавить путь', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#paths-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Пути</h1>

<div class="search-form">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'paths-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		'id_path',
		array('name'=>'settlement_search_1', 'value'=>'$data->startSettlement->name'),
		array('name'=>'settlement_search_2', 'value'=>'$data->endSettlement->name'),
		'distance',
		'time',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
