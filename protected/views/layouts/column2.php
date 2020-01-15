<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
 <div class="col-md-17 col-sm-16 col-xs-24 left-collum-wrapper">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>
 <div class="col-md-7 col-sm-8 col-xs-24 right-collum-wrapper">
	<div id="right-column">
		<?php echo $this->right_column; ?>
    <div id="sidebar">
      <?php
      if ($this->menu) {
        $this->beginWidget('zii.widgets.CPortlet', array(
            'title'=>'Операции',
        ));
        $this->widget('zii.widgets.CMenu', array(
            'items'=>$this->menu,
            'htmlOptions'=>array('class'=>'operations'),
        ));
        $this->endWidget();
      }
      ?>
    </div><!-- sidebar -->
	</div><!-- content -->
</div>

<?php $this->endContent(); ?>