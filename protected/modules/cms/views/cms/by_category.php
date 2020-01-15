

<?php
/* @var $this LostFoundController */
/* @var $dataProvider CActiveDataProvider */

$text_not_results="<div style='text-align:center;'>По вашему запросу ничего не найдено.</div>";

?>



<?php if($_GET['ajax']): ?>

    <?php if($orders): ?>
        <?php foreach($orders as $order): ?>
           <?php // $this->renderPartial('_order', array('order'=>$order)); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <?= $text_not_results ?>
    <?php endif; ?>

<?php else: ?>

    <div class="wraper-for-marging">
        <div class="left_column" style="width:100%;">
			<div class="header" style="margin-bottom:20px;"><div id="anchor"></div><h1><?=$category->name?></h1></div>

            <div class="annotations container-clearfix">
                <?php  if($orders): ?>				
                    <?php foreach($orders as $order): ?>
                        <?php $this->renderPartial('_order', array('order'=>$order)); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?=$text_not_results ?>
                <?php endif; ?>
               
            </div></div><div class="block-clearfix"></div>

    </div>




   
<?php endif; ?>