<?php if (Yii::app()->user->checkAccess('admin')): ?>
  <?php
  $this->widget('bootstrap.widgets.BsNavbar', array(
      'color'   =>  BsHtml::NAVBAR_COLOR_INVERSE,
      'position' => BsHtml::NAVBAR_POSITION_STATIC_TOP,
      'collapse' => true,
      'brandLabel' => BsHtml::icon(BsHtml::GLYPHICON_HOME),
      'brandUrl' => Yii::app()->homeUrl,
      'items' => array(
          array(
              'class' => 'bootstrap.widgets.BsNav',
              'type' => 'navbar',
              'activateParents' => true,
              'items' => array(
                  array(
                      'label' => 'Попутчик',
                      'items' => array(
                          array('label' => 'Маршруты', 'url' => 'http://'.SERVICE_POPUTCHIK.'.'.Yii::app()->params['domain'].'/ppRoute'),
                          BsHtml::menuDivider(),
                          array('label' => 'Добавить заявку пассажира', 'url' => 'http://'.SERVICE_POPUTCHIK.'.'.Yii::app()->params['domain'].'/poputchikOrder/addAdvert/type/passenger'),
                          array('label' => 'Добавить заявку водителя', 'url' => 'http://'.SERVICE_POPUTCHIK.'.'.Yii::app()->params['domain'].'/poputchikOrder/addAdvert/type/driver'),
                      ),
                  ),
              )
          ),
          array(
              'class' => 'bootstrap.widgets.BsNav',
              'type' => 'navbar',
              'activateParents' => true,
              'items' => array(
                  array(
                      'label' => 'Бюро находок',
                      'items' => array(
                          array('label' => 'Добавить заявку Бюро', 'url' => 'http://'.SERVICE_BURO_NAHODOK.'.'.Yii::app()->params['domain'].'/LostFound/create?type_order=1'),
                          array('label' => 'Модерировать заявки Бюро', 'url' => 'http://'.SERVICE_BURO_NAHODOK.'.'.Yii::app()->params['domain'].'/LostFound/toModerate'),
                          array('label' => 'Непотеряйка', 'url' => 'http://'.SERVICE_NEPOTERAYKA.'.'.Yii::app()->params['domain'].'/LostService/toModerate'),
                      ),
                  ),
              )
          ),
          array(
              'class' => 'bootstrap.widgets.BsNav',
              'type' => 'navbar',
              'activateParents' => true,
              'items' => array(
                  array(
                      'label' => 'Ночлег',
                      'items' => array(
                          array('label' => 'Добавить заявку Ночлег', 'url' =>'http://'.SERVICE_NOCHLEG.'.'.Yii::app()->params['domain'].'/hotelService/create'),
                          array('label' => 'Модерировать заявки Ночлег', 'url' => 'http://'.SERVICE_NOCHLEG.'.'.Yii::app()->params['domain'].'/hotelService/toModerate'),
                      ),
                  ),
              )
          ),
          array(
              'class' => 'bootstrap.widgets.BsNav',
              'type' => 'navbar',
              'activateParents' => true,
              'items' => array(
                  array(
                      'label' => 'Справочники',
                      'items' => array(
                          array('label' => 'Страны', 'url' => array('/countries/index')),
                          array('label' => 'Регионы', 'url' => array('/regions/index')),
                          array('label' => 'Районы', 'url' => array('/areas/index')),
                          array('label' => 'Населённые пункты', 'url' => array('/settlements/index')),
                          array('label' => 'Внутригородские районы', 'url' => array('/sAreas/index')),
                          BsHtml::menuDivider(),
                          array('label' => 'Страны (новые)', 'url' => array('/geoCountry')),
                          array('label' => 'Области (новые)', 'url' => array('/geoZone')),
                          array('label' => 'Населённые пункты (новые)', 'url' => array('/geoname')),
                          BsHtml::menuDivider(),
                          array('label' => 'Категории объектов', 'url' => array('/category/index')),
                          array('label' => 'Марки автомобилей', 'url' => array('/autoBrands/index')),
                          array('label' => 'Модели автомобилей', 'url' => array('/autoModels/index')),
                          array('label' => 'Баннера', 'url' => array('/banners/index')),
                      )
                  ),
              )
          ),
          array(
              'class' => 'bootstrap.widgets.BsNav',
              'type' => 'navbar',
              'activateParents' => true,
              'items' => array(
                  array(
                      'label' => 'CMS',
                      'items' => array(
                          array('label' => 'Страницы', 'url' => array('/cms')),
                          array('label' => 'Блог', 'url' => array('/blog/admin')),
                          array('label' => 'Переводы', 'url' => array('/admin2/translate')),
//                          array('label' => 'Сео города', 'url' => array('/seoCity/admin')),
                      ),
                  ),
              )
          ),
          array(
              'class' => 'bootstrap.widgets.BsNav',
              'type' => 'navbar',
              'activateParents' => true,
              'items' => array(
                  array(
                      'label' => 'Статистика',
                      'items' => array(
                          array('label' => 'По городам', 'url' => array('/stats/city')),
                          array('label' => 'Межгород', 'url' => array('/stats/intercity')),
                      ),
                  ),
              )
          ),
          array(
              'class' => 'bootstrap.widgets.BsNav',
              'type' => 'navbar',
              'activateParents' => true,
              'htmlOptions' => array(
                  'pull' => BsHtml::NAVBAR_NAV_PULL_RIGHT
              ),
              'items' => array(
                  array(
                      'label' => Yii::app()->user->name,
                      'url' => array(
                          '/site/index'
                      ),
                      'items' => array(
                          array('icon' => BsHtml::GLYPHICON_EDIT,'url' => Yii::app()->getModule('user')->profileUrl, 'label' => Yii::app()->getModule('user')->t("Profile"), 'visible' => !Yii::app()->user->isGuest),
                          array('icon' => BsHtml::GLYPHICON_OFF,'url' => Yii::app()->getModule('user')->logoutUrl, 'label' => Yii::app()->getModule('user')->t("Logout") . ' (' . Yii::app()->user->name . ')', 'visible' => !Yii::app()->user->isGuest),
                      )
                  ),
              )
          ),
      )
  ));
  ?>
<?php endif; ?>
