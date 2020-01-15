<?php
/* @var $this LostFoundController */
/* @var $model LostFound */

$this->breadcrumbs=array(
	'Lost Founds'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List LostFound', 'url'=>array('index')),
	array('label'=>'Create LostFound', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#lost-found-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Lost Founds</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'lost-found-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id_lf',
		'id_user',
		'category',
		'lost_or_found',
		'city',
		'place_disc',
		/*
		'thing',
		'operator',
		'status',
		'date_filing',
		'date_lf',
		'comment',
		'phone',
		'active_code',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
