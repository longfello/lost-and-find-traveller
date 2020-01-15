<?php
/* @var $this RoutesController */
/* @var $model Routes */

$this->breadcrumbs=array(
	'Маршруты'=>array('index'),
	$model->id_route,
);

$this->menu=array(
	array('label'=>'Маршруты', 'url'=>array('index')),
	array('label'=>'Добавить маршрут', 'url'=>array('create')),
	array('label'=>'Редактировать маршрут', 'url'=>array('update', 'id'=>$model->id_route)),
	array('label'=>'Удалить маршрут', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_route),'confirm'=>'Are you sure you want to delete this item?')),
);
$rim = "";
for($i=0; $i<count($model->routePaths)-1; $i++) {
	if($i>0) $rim .= '<br /><br />';
	if($model->routePaths[$i]->direction) $rim .= $model->routePaths[$i]->idPath->startSettlement->getSettlementFullname();
	else $rim .= $model->routePaths[$i]->idPath->endSettlement->getSettlementFullname();
}
?>

<h1>Маршрут #<?php echo $model->id_route; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
		   'label'=>'Начальный пункт',
		   'value'=>$model->startSettlement->getSettlementFullname(),
		   'type' => 'raw',
		 ),
		array(
		   'label'=>'Конечный пункт',
		   'value'=>$model->endSettlement->getSettlementFullname(),
		   'type' => 'raw',
		 ),
		 array(
		   'label'=>'Промежуточные пункты',
		   'value'=>$rim,
		   'type' => 'raw',
		 ),
	),
)); ?>
