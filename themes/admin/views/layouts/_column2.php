<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
  <div class="col-md-16">
    <div id="content">
      <?php echo $content; ?>
    </div><!-- content -->
  </div>
  <div class="col-md-8">
    <div id="right-column">
      <?php echo $this->right_column; ?>
      <div id="sidebar">
        <?php
        $this->beginWidget('zii.widgets.CPortlet', array(
            'title'=>'Операции',
        ));
        $this->widget('zii.widgets.CMenu', array(
            'items'=>$this->menu,
            'htmlOptions'=>array('class'=>'operations'),
        ));
        $this->endWidget();
        ?>
      </div><!-- sidebar -->
    </div><!-- content -->
  </div>

<?php $this->endContent(); ?>