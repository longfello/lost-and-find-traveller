<?php
/* @var $this BlogController */
/* @var $model Blog */

$this->breadcrumbs=array(
	'Полезные советы'=>array('/poleznye-sovety'),
	$model->name,
);

?>

<h1><?php echo $model->name; ?></h1>
<?=$model->content?>

<?php 
if (Yii::app()->user->checkAccess('admin') || Yii::app()->user->checkAccess('moderator'))
                    print '<div>[<a href="/blog/update/id/' . $model->id . '">Редактировать</a>]</div>';
?>
