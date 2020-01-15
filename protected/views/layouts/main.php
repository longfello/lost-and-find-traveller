<?php
/* @var $this Controller */

$cs = Yii::app()->clientScript;
$am = Yii::app()->assetManager;

$assetsUrl    = $am->publish(Yii::app()->getBasePath() . '/../css/fonts', false, -1, YII_DEBUG);
$bootstrapUrl = $am->publish(Yii::getPathOfAlias('ext.bootstrap.assets'), false, -1, YII_DEBUG);
$jsUrl        = $am->publish(Yii::getPathOfAlias('webroot.js'), false, -1, YII_DEBUG);
$cssUrl       = $am->publish(Yii::getPathOfAlias('webroot.css'), false, -1, YII_DEBUG);

$cs->registerCssFile($assetsUrl . '/aleksandrac.css');
$cs->registerCssFile($cssUrl.'/jquery.selectbox.css');
$cs->registerCssFile($cs->getCoreScriptUrl() . '/jui/css/base/jquery-ui.css');
$cs->registerCssFile('http://fonts.googleapis.com/css?family=Roboto:300,300italic,400,400italic,700,700italic&subset=latin,cyrillic');
$cs->registerCssFile($bootstrapUrl . '/css/bootstrap.css');
$cs->registerCssFile($cssUrl.'/bootstrap/style.css');
$cs->registerCssFile($cssUrl.'/bootstrap/style2.css');
$cs->registerCssFile($bootstrapUrl . '/css/bootstrap-theme.css');

$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.migrate');
$cs->registerCoreScript('jquery.ui');
$cs->registerScriptFile($jsUrl.'/jquery.maskedinput.js', CClientScript::POS_HEAD);
$cs->registerScriptFile($jsUrl.'/jquery.form.min.js', CClientScript::POS_HEAD);
$cs->registerScriptFile($jsUrl.'/jquery.selectbox-0.2.js', CClientScript::POS_HEAD);
$cs->registerScriptFile($jsUrl.'/share42/share42.js', CClientScript::POS_HEAD);

$cs->registerScriptFile($jsUrl.'/common.js', CClientScript::POS_END);
$cs->registerScriptFile($jsUrl.'/messages.js.php', CClientScript::POS_END);
$cs->registerScriptFile($jsUrl.'/main.js', CClientScript::POS_END);
$cs->registerScriptFile($jsUrl.'/jquery.scrollTo.js', CClientScript::POS_END);
$cs->registerScriptFile($jsUrl.'/jquery.inputfit.js', CClientScript::POS_HEAD);


$cs->registerScriptFile($bootstrapUrl . '/js/bootstrap.min.js', CClientScript::POS_END);
$cs->registerScript('tooltip', "$('[data-toggle=\"tooltip\"]').tooltip();$('[data-toggle=\"popover\"]').tooltip()", CClientScript::POS_READY);

$language = Yii::app()->language;
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=Yii::app()->language?>" lang="<?=Yii::app()->language?>">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="language" content="en"/>
  <link rel="image_src" href="logo.png">
  <meta property="og:image" content="/images/ilogo.png"/>
  <link rel="icon" href="/favicon.ico" type="image/x-icon"/>
  <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body class="<?= (Yii::app()->user->checkAccess('admin')) ? "admin" : "" ?> c-<?=Yii::app()->controller->id ?> r-<?= Yii::app()->controller->action->id ?>">
  <div id="ajax-error-message" style="display: none"></div>
  <?php include('menu/admin.php') ?>
  <div id="header" class="jumbotron header-wrapper">
    <div class="container">
        <div class="row container-header">
            <div class="col-md-16 col-xs-14" id="logo">
              <a href="//<?=Yii::app()->params['domain']?>"><img src="/images/logo.png"/>
                <div class="text"><?= Yii::t('main', "Единая информационная служба") ?></div>
              </a>
            </div>
            <div class="col-md-8 col-xs-10" id="phone-layout">
              <?php $this->widget('application.components.widgets.headerFillerWidget', array()); ?>
            </div>
        </div>

        <div class="row navigate">
            <div class="col-md-18 col-xs-20 dropped-menu" id="logo">
                <?php include('menu/main.php') ?>
            </div>
            <div class="col-md-6 col-xs-4 login-btn" id="logo">
                <?php include('menu/account.php') ?>
            </div>

        </div>
    </div>
  </div>
  <div id="page"   class="container main-container">
    <div class="main-wrapper">
      <div class="main-content">
        <div id="content-wrapper" class="row">
          <?php if (Yii::app()->user->hasFlash('ifindmessage')): ?>
            <div class="message">
              <div class="flash-success">
                <?php echo Yii::app()->user->getFlash('ifindmessage'); ?>
              </div>
            </div>
          <?php endif; ?>
          <?php if (!empty($this->breadcrumbs)) { ?>
            <div class="breadcrumb"><?php
              $this->widget('zii.widgets.CBreadcrumbs', array(
                  'links' => $this->breadcrumbs,
                  'separator' => ' / ',
                  'htmlOptions' => array('class' => 'breadcrumbs')
              ));
              ?> </div>
          <? } ?>
          <?php echo $content; ?>
        </div>
      </div>
    </div>
    <?php $this->widget('application.components.widgets.reviewWidget', array()); ?>

    <!-- footer -->
  </div>
  <div id="footer" class="jumbotron">
      <div class="container">
          <?php if (isset(Yii::app()->controller->footer_html) && Yii::app()->controller->footer_html) { ?>
            <div class="row wrapp-foot"><?=Yii::app()->controller->footer_html?></div>
          <?php } ?>
      </div>
      <div class="second-footer">
          <div class="container">
              <div class="row wrapp-foot">
                  <div class="col-lg-12 col-xs-12 col-sm-12 col-md-12 logo"><span>© 2013-<?=date('Y')?></span>
                      <div class="text"><?= Yii::t('main', "Единая информационная служба") ?></div>
                  </div>
                  <div class="center-menu-footer">
                      <?php include('menu/footer.php') ?>
                  </div>
                  <div class="col-lg-12 col-xs-12 col-sm-12 col-md-12 phone">8 800 55 05 155
                      <div class="text"><?= Yii::t('main', "Телефон горячей линии") ?>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  </div>
  <!-- page -->
  <?php /* $this->widget('application.components.widgets.CountersWidget', array('layout' => 'YandexMetrika', 'id' => 25402388)); */?>
  <?php /* $this->widget('application.components.widgets.CountersWidget', array('layout' => 'YandexMetrika', 'id' => 24462377)); */ ?>
  <?php /* $this->widget('application.components.widgets.CountersWidget', array('layout' => 'LiveInternet')); */ ?>
  <?php /* $this->widget('application.components.widgets.CountersWidget', array('layout' => 'GoogleAnalytics', 'id' => 'UA-52410305-1')); */ ?>

  <!--[if lt IE 9]>
  <script type="text/javascript" src="<?= $jsUrl ?>/html5shiv.min.js"></script>
  <script type="text/javascript" src="<?= $jsUrl ?>/respond.min.js"></script>
  <![endif]-->

</body>
</html>