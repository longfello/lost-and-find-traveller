<?php
/* @var $this RoutesController */
/* @var $model Routes */

$this->breadcrumbs=array(
	'Маршруты'
);

$this->menu=array(
	array('label'=>'Добавить маршрут', 'url'=>array('create')),
);

Yii::app()->getClientScript()->registerCssFile(Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('zii.widgets.assets')).'/gridview/styles.css'); 

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	var options = {
	  dataType: 'html',
	  type: 'GET',
	  async: false,
	  url: '/index.php/routes/index?ajax=1',
	  error: function (data) { ajax_server_error(data); },
	  success: function (data) { 
			$('#routes-grid').html(data);
		}
	};
	ajax_server_error_hide();
	$('#yw0').ajaxSubmit(options);
	return false;
});
");
?>

<h1>Управление маршрутами</h1>

<div class="search-form">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<div id="routes-grid" class="grid-view">
<?php $this->renderPartial('_routesGrid',array(
	'model'=>$model,
)); ?>
</div>
