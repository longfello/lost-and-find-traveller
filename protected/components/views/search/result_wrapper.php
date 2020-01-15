<?php
  $am    = Yii::app()->assetManager;
  $cs    = Yii::app()->clientScript;
  $jsUrl = $am->publish(Yii::getPathOfAlias('webroot.js'), false, -1, YII_DEBUG);
  $cs->registerScriptFile($jsUrl.'/poputchik/results.js', CClientScript::POS_END);
?>
<div class="poput-orders container-clearfix">

  <div class="application">
    <h2>Подать заявку</h2>
    <a href="/poputchikOrder/addAdvert/type/passenger" class="applicationOfPassengers"><?=Yii::t('poputchik', 'Подать заявку пассажира'); ?></a>
    <a href="/poputchikOrder/addAdvert/type/driver" class="applicationOfDriver"><?=Yii::t('poputchik', 'Подать заявку водителя'); ?></a>
    <div class="clear"></div>
  </div>

  <br />

  <div class="orders-content">
    <div role="tabpanel" id="routes-paths-search-results-tabs">
      <ul class="nav nav-tabs" role="tablist">
        <?php $first = true; ?>
        <?php foreach($searcher->engines as $engine){ ?>
          <li role="presentation" <?= $first?'class="active"':''?>><a href="#se-<?=$engine->name?>"><?=$engine->title?></a></li>
          <?php $first = false; ?>
        <?php } ?>
      </ul>
      <div class="tab-content">
        <?php $first = true; ?>
        <?php foreach($searcher->engines as $engine){ ?>
          <div role="tabpanel" class="tab-pane fade <?= $first?'active fade in':''?>" id="se-<?=$engine->name?>">
            <?php if ($engine->results) { ?>
              <?php foreach($engine->results as $model) { ?>
                <?= $this->renderFile(Yii::getPathOfAlias('application.components.views.search').'/result.php', array('model' => $model, 'engine' => $engine)) ?>
              <?php } ?>
              <?$this->widget('RLinkPager', array(
                  'pages' => $engine->pages,
              ))?>
            <?php } else { ?>
              <div class="not-results">
                <?= Yii::t('poputchik', 'routes-not-founded'); ?>
              </div>
            <? } ?>
          </div>
          <?php $first = false; ?>
        <?php } ?>
      </div>
    </div>
  </div>
</div>