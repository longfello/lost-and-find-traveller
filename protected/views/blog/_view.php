<?php
/* @var $this BlogController */
/* @var $data Blog */
?>

<div class="view">

	
	<h1><?php echo CHtml::encode($data->name); ?></h1>
	<br />
	
	<?php echo StringHelper::cut($data->content, 700, $data->url); ?>

</div>