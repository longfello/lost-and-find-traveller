<?php
/* @var $this BlogController */
/* @var $dataProvider CActiveDataProvider */



            Yii::app()->clientScript->registerMetaTag('', 'keywords');
            Yii::app()->clientScript->registerMetaTag('', 'description');
            $this->pageTitle = 'Полезные советы | INFOtoway.ru';
            
            
if (Yii::app()->user->checkAccess('admin') || Yii::app()->user->checkAccess('moderator'))
$this->menu=array(
	array('label'=>'Create Blog', 'url'=>array('create')),
	array('label'=>'Manage Blog', 'url'=>array('admin')),
);
?>

<h1>Полезные советы</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
       // 'summaryText' => 'Показаны {start}-{end} советов из {count}'
    'summaryText' => ''
)); ?>
