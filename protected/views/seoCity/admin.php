<?php
/* @var $this SeoCityController */
/* @var $model SeoCity */

$this->breadcrumbs=array(
	'Seo Cities'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List SeoCity', 'url'=>array('index')),
	array('label'=>'Create SeoCity', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#seo-city-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Seo Cities</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'seo-city-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
                array(
                        'name'=>'settlementName',
                        'type'=>'raw',
                        'value'=>'$data->settlements->name',
                ),
		'url',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
