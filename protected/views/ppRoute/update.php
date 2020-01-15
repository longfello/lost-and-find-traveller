<?php
/* @var $this PpRouteController */
/* @var $model PpRoute */

$this->breadcrumbs=array(
	'Маршруты'=>array('index'),
	$model->id=>array('update','id'=>$model->id),
	'Редактирование',
);

$this->menu=array(
  	array('label'=>'Список маршрутов', 'url'=>array('index')),
    array('label'=>'Добавить маршрут водителя', 'url'=>array('//poputchikOrder/addAdvert/type/driver')),
    array('label'=>'Добавить маршрут пассажира', 'url'=>array('//poputchikOrder/addAdvert/type/passenger')),
);
?>

<h1>Редактирование маршрута #<?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>