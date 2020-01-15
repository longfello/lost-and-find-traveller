<?php
/* @var $this PpRouteController */
/* @var $model PpRoute */

$this->breadcrumbs=array(
	'Маршруты'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Добавить маршрут водителя', 'url'=>array('//poputchikOrder/addAdvert/type/driver')),
	array('label'=>'Добавить маршрут пассажира', 'url'=>array('//poputchikOrder/addAdvert/type/passenger')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#pp-route-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Список маршрутов</h1>

<?php $this->widget('bootstrap.widgets.BsGridView', array(
	'id'=>'pp-route-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
    array(
      'name'=>'uid',
      'value'=>'$data->user->profile->first_name',
      'filter'=>false,
    ),
    array(
      'name'=>'from_id',
      'value'=>'$data->fromLocation->name',
      'filter'=>false,
    ),
    array(
      'name'=>'to_id',
      'value'=>'$data->toLocation->name',
      'filter'=>false,
    ),
    'departure',
    'available_until',
    array(
        'name'=>'enabled',
        'value'=>'$data->enabled?"Да":"Нет"',
        'filter'=>array(false=>'Все', '1' => 'Да', 0 => 'Нет'),
    ),
    /*
    'path',
    'path_full',
    'return',
    'cost',
    'car_id',
    'free_seats',
    'luggage',
    'punctuality',
    'deviation_from_route',
    'comment',
    'type',
    'enabled',
    */
		array(
			'class'=>'CButtonColumn',
      'template'=>'<nobr>{delete}{update}</nobr>',
		),
	),
)); ?>
