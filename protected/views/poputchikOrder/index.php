<?php
  /* @var $this PoputchikOrderController */
  /* @var $dataProvider CActiveDataProvider */
  /* @var $form PoputchikForm */

  $am    = Yii::app()->assetManager;
  $cs    = Yii::app()->clientScript;
  $jsUrl = $am->publish(Yii::getPathOfAlias('webroot.js'), false, -1, YII_DEBUG);
  $cs->registerScriptFile($jsUrl.'/poputchik.js', CClientScript::POS_END);
  $cs->registerScriptFile($jsUrl.'/button_down.js', CClientScript::POS_END);

  Yii::app()->clientScript->registerMetaTag(Yii::t('poputchik', $form->typeRoute.'_keywords'), 'keywords', null, array(), 'keywords');
  Yii::app()->clientScript->registerMetaTag(Yii::t('poputchik', $form->typeRoute.'_description'), 'description', null, array(), 'description');
  $this->pageTitle = Yii::t('poputchik', $form->typeRoute.'_pageTitle');
  $this->breadcrumbs = array(
      Yii::t('poputchik', 'Поиск попутчика') => '/'.$form->typeRoute
  );
  if ($form->cityFrom) $this->breadcrumbs[] = $form->cityFrom->name_ru;

  if (!empty($seoCity)) {
    if (!empty($seoCity->title)) {
      $this->pageTitle = $seoCity->title;
    }
    if (!empty($seoCity->keywords)) {
      Yii::app()->clientScript->registerMetaTag($seoCity->keywords, 'keywords');
    }
    if (!empty($seoCity->description)) {
      Yii::app()->clientScript->registerMetaTag($seoCity->description, 'description');
    }
  }
  $this->headerTotal = "<div class='total_count'>" . (1100 + PpRoute::model()->cache(60*60)->count()) . " - заявок на " . date('d.m.Y') . "</div>";
  $title = '';
  if ($form->typeRoute == PpRoute::TYPE_ROUTE_ANOTHER)   $title = Yii::t('poputchik', 'Поиск попутчиков в другой город');
  if ($form->typeRoute == PpRoute::TYPE_ROUTE_SAME) $title = Yii::t('poputchik', 'Поиск попутчиков по городу');

  $this->footer_html = $this->widget('application.components.widgets.ModernRoutesWidget', array('form'=>$form), true);
  $this->footer_html = '<div class="links-to-modern-routes">'.$this->footer_html.'</div>';

?>

  <div class="wraper-for-marging">
    <div class="left_column col-md-24">
      <div class="header-search col-md-24 row">
        <div style="" id="anchor"></div>
        <div class="header"><h1><?= $title ?></h1></div>
        <?php
          if (!empty($seoCity->seo_text_top)) { print '<div id="city_seo_top">' . $seoCity->seo_text_top . '</div>'; }
          if ((Yii::app()->user->checkAccess('admin') || Yii::app()->user->checkAccess('moderator')) && $form->cityFrom) {
            echo ('<div>[<a href="'.$this->createUrl('Geoname/update', array('id' => $form->cityFrom->id)). '">'.Yii::t('poputchik', 'Редактировать').'</a>]</div>');
          }
        ?>
        <div><?=Yii::t('poputchik', $form->typeRoute.'_seoText'.(($form->cityFrom)?"ForCity":"ForNoCity"));?></div>
      </div>
      <div class="search-filter">
        <?= $form->fetch() ?>
        <?php if (!$form->isIndex) { ?>
          <?= $adverts ?>
        <?php } ?>
        <?php /* $this->renderPartial('result', array('pages' => $pages, 'orders' => $orders)); */ ?>
      </div>
      <div id="bottom_banner_position"></div>

      <div class="afterorder annotation"><?php
        if ($form->isIndex) {
          $page = Cms::model()->findByAttributes(array('url' => 'seo-text-on-poputchik-index'));
          if ($page){
            $this->pageTitle = $page->title;
            Yii::app()->clientScript->registerMetaTag($page->keywords, 'keywords', null, array(), 'keywords');
            Yii::app()->clientScript->registerMetaTag($page->description, 'description', null, array(), 'description');
            echo($page->content);
          }
        } else {
          if (!empty($seoCity->seo_text)) { print '<div id="city_seo">' . $seoCity->seo_text; }
          if ((Yii::app()->user->checkAccess('admin') || Yii::app()->user->checkAccess('moderator')) && $form->cityFrom) {
            print '<div>[<a href="' . $this->createUrl('Geoname/update', array('id' => $form->cityFrom->id)) . '">Редактировать</a>]</div>';
          }
          if (!empty($seoCity->seo_text)) { print '</div>'; }
          if (!$form->cityFrom || empty($seoCity) || empty($seoCity->seo_text)){
            print(Yii::t('poputchik', $form->typeRoute.'_seoAfter'));
          }
        }
        ?>
      </div>
    </div>
	  <div id="top-link">
		  <div href="#top"><?=Yii::t('poputchik','наверх');?></div>
	  </div>
    <div class="block-clearfix"></div>
  </div>
