<?php
/* @var $this Controller */
/*
Yii::app()->getClientScript()->registerCoreScript('jquery');
Yii::app()->getClientScript()->registerCoreScript('jquery.ui');
Yii::app()->getClientScript()->registerScriptFile('/js/jquery.form.min.js');
Yii::app()->getClientScript()->registerScriptFile('/js/jquery.selectbox-0.2.js');
Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin.css" />

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/fonts/aleksandrac.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.selectbox.css" />
        <link href='http://fonts.googleapis.com/css?family=Roboto:300,300italic,400,400italic,700,700italic&subset=latin,cyrillic' rel='stylesheet' type='text/css'>

            <title><?php echo CHtml::encode($this->pageTitle); ?></title>
            <script type="text/javascript" src="/js/common.js"></script>
            <script type="text/javascript" src="/js/messages.js.php"></script>
    </head>

    <body>
        <div id="ajax-error-message" style="display: none"></div>
        <div class="container" id="page">
            <div id="mainmenu">
                <?php
                $this->widget('zii.widgets.CMenu', array(
                    'items' => array(
                        array('label' => 'Справочники', 'url' => array('#'),
                            'items' => array(
                                array('label' => 'Категории объектов', 'url' => array('/category/index')),
                                array('label' => 'Марки автомобилей', 'url' => array('/autoBrands/index')),
                                array('label' => 'Модели автомобилей', 'url' => array('/autoModels/index')),
                            ),
                        ),
                        array('label' => 'Местоположения', 'url' => array('#'),
                            'items' => array(
                                array('label' => 'Страны', 'url' => array('/countries/index')),
                                array('label' => 'Регионы', 'url' => array('/regions/index')),
                                array('label' => 'Районы', 'url' => array('/areas/index')),
                                array('label' => 'Населённые пункты', 'url' => array('/settlements/index')),
                                array('label' => 'Внутригородские районы', 'url' => array('/sAreas/index')),
                            ),
                        ),
                        array('label' => 'Маршруты', 'url' => array('#'),
                            'items' => array(
                                array('label' => 'Пути', 'url' => array('/paths/index')),
                                array('label' => 'Маршруты', 'url' => array('/routes/index')),
                            ),
                        ),
                        array('label' => 'Попутчик', 'url' => array('#'),
                            'items' => array(
                                array('label' => 'Добавить заявку', 'url' => array('/poputchikOrder/create')),
                                array('label' => 'Модерировать заявки', 'url' => array('/poputchikOrder/toModerate')),
                            ),
                        ),
                        array('label' => 'Бюро находок', 'url' => array('#'),
                            'items' => array(
                                array('label' => 'Добавить заявку Бюро', 'url' => array('/LostFound/create')),
                                array('label' => 'Модерировать заявки Бюро', 'url' => array('/LostFound/toModerate')),
                                array('label' => 'Непотеряйка', 'url' => array('/LostService/toModerate')),
                            ),
                        ),
                        array('label' => 'About', 'url' => array('/site/page', 'view' => 'about')),
                        array('label' => 'Contact', 'url' => array('/site/contact')),
                        array('label' => 'Страницы', 'url' => array('/cms')),
                        array('label' => 'Блог', 'url' => array('/blog/admin')),
                        array('label' => 'Сео города', 'url' => array('/seoCity')),
                        array('label' => 'Статистика', 'url' => array('#'),
                            'items' => array(
                                array('label' => 'По городам', 'url' => array('/stats/city')),
                                array('label' => 'Межгород', 'url' => array('/stats/intercity')),
                            ),
                        ),
                        
                        array('url' => Yii::app()->getModule('user')->loginUrl, 'label' => Yii::app()->getModule('user')->t("Login"), 'visible' => Yii::app()->user->isGuest),
                        array('url' => Yii::app()->getModule('user')->registrationUrl, 'label' => Yii::app()->getModule('user')->t("Register"), 'visible' => Yii::app()->user->isGuest),
                        array('url' => Yii::app()->getModule('user')->profileUrl, 'label' => Yii::app()->getModule('user')->t("Profile"), 'visible' => !Yii::app()->user->isGuest),
                        array('url' => Yii::app()->getModule('user')->logoutUrl, 'label' => Yii::app()->getModule('user')->t("Logout") . ' (' . Yii::app()->user->name . ')', 'visible' => !Yii::app()->user->isGuest),
                    ),
                ));
                ?>
            </div><!-- mainmenu -->
            <?php if (isset($this->breadcrumbs)): ?>
                <?php
                $this->widget('zii.widgets.CBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                ));
                ?><!-- breadcrumbs -->
<?php endif ?>
            <div id="content-wrapper">
<?php echo $content; ?>

                <div class="clear"></div>


            </div>
        </div><!-- page -->

    </body>
</html>
<?php
*/