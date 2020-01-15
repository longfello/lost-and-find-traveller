<?php
/* @var $this poputchikOrder */
$cs = Yii::app()->clientScript;
$am = Yii::app()->assetManager;

$jsUrl = $am->publish(Yii::getPathOfAlias('webroot.js'), false, -1, YII_DEBUG);
$cssUrl = $am->publish(Yii::getPathOfAlias('webroot.css'), false, -1, YII_DEBUG);
?>

  <form action="" method="post" class="itw-multistep-form route-type-intercity">
    <div class="offerTrip">
      <p><?= Yii::t('poputchik', 'Предложить поездку')?></p>

      <div class="number-step-header">
        <div class="linePaginator"></div>
        <div class="step-circle step-circle-1"><a href="#" class="active">1</a></div>
        <div class="step-circle step-circle-2"><a href="#">2</a></div>
        <div class="linePaginator2"></div>
        <div class="step-circle step-circle-3"><a href="#">3</a></div>
      </div>
    </div>

    <div class="step">
      <h2><?= Yii::t('poputchik', 'Маршрут');?></h2>

      <div class="validate-el validate-el-overall"></div>

      <?php $this->widget('application.components.widgets.RouteTypeChooserWidget', array()); ?>
      <?php $this->widget('application.components.widgets.PeriodicityChooserWidget', array()); ?>

      <div class="row offerTrip_input one-city">
        <div class="searchDirections  route-type-el route-type-el-city route-type-el-intercity">
          <?php $this->widget('application.components.widgets.CityAcWidget', array('class' => 'route_start default validate-el validate-el-start-location', 'htmlOptions' => array(
              'size' => 50,
              'maxlength' => 255,
              'id' => "start_name",
              'placeholder' => Yii::t('poputchik', 'ГОРОД'),
              'autocomplete' => "off"
          ))); ?>
            <div class="validate-error validate-error-start-location"></div>
          <span class="example">
            <?= Yii::t('poputchik', 'Например:')?>
            <?php $this->widget('application.components.widgets.chooseCityByLinkWidget', array('selector' => '#start_name', 'name' => Yii::t('poputchik', 'Москва'))) ?>,
            <?php $this->widget('application.components.widgets.chooseCityByLinkWidget', array('selector' => '#start_name', 'name' => Yii::t('poputchik', 'Киров'))) ?>
          </span>
        </div>
        <div class="swap change-arrow  route-type-el route-type-el-intercity">
          <a href="#"><img src="/images/offerDriver/arrow-right-active.png"></a>
          <a href="#"><img src="/images/offerDriver/arrow-left.png"></a>
        </div>
        <div class="searchDirections route-type-el route-type-el-intercity last">
          <?php $this->widget('application.components.widgets.CityAcWidget', array('empty' => true, 'class' => 'route_end default validate-el validate-el-end-location', 'htmlOptions' => array(
              'size' => 50,
              'maxlength' => 255,
              'id' => "end_name",
              'placeholder' => Yii::t('poputchik', "ГОРОД"),
              'autocomplete' => "off"
          ))); ?>
            <div class="validate-error validate-error-end-location"></div>
          <span class="example">
            <?= Yii::t('poputchik', 'Например:')?>
            <?php $this->widget('application.components.widgets.chooseCityByLinkWidget', array('selector' => '#end_name', 'name' => Yii::t('poputchik', 'Москва'))) ?>,
            <?php $this->widget('application.components.widgets.chooseCityByLinkWidget', array('selector' => '#end_name', 'name' => Yii::t('poputchik', 'Киров'))) ?>
          </span>
        </div>
      </div>
      <div class="row offerTrip_input route-type-el route-type-el-city">
        <div class="searchDirections left">
          <?php $this->widget('application.components.widgets.AddressAcWidget', array(
              'layout'=>'index',
              'class'=>'route_start_address default',
              'parent' => 'route_start',
              'htmlOptions'=>array(
                  'id' => 'street_from',
                  'autocomplete' => "off"
              ))); ?>
        </div>
        <div class="swap"></div>
        <div class="searchDirections right">
          <?php $this->widget('application.components.widgets.AddressAcWidget', array(
              'layout'=>'index',
              'class'=>'route_end_address default',
              'parent' => 'route_end',
              'htmlOptions'=>array(
                  'id' => 'street_to',
                  'autocomplete' => "off"
              ))); ?>
        </div>
      </div>
      <div class="row map-block">
        <div class="intermediate col-xs-24 col-sm-24 col-md-12 points-wrapper">
          <ul class="points"></ul>
          <div class="add_sity_plus">
            <?php $this->widget('application.components.widgets.AddressAcWidget', array('class' => 'route_waypoint default', 'htmlOptions' => array(
                'size' => 50,
                'maxlength' => 255,
                'id' => "plus_sity",
                'placeholder' => Yii::t('poputchik', 'Промежуточный пункт'),
                'autocomplete' => "off"
            ))); ?>
            <a href="#" class="btn btn-default btn-add-point del-label"></a>
          </div>
          <label for="avoidHighways"><input type="checkbox" id="avoidHighways" checked><?= Yii::t('poputchik', 'Предпочитать главные дороги');?></label>
          <label for="optimizeWaypoints"><input type="checkbox" id="optimizeWaypoints" checked><?= Yii::t('poputchik', 'Оптимизировать маршрут');?></label>
          <div class="routes"></div>
        </div>

        <div class="route_map col-xs-24 col-sm-24 col-md-12">
          <span><?= Yii::t('poputchik', 'Маршрут на карте')?></span><div class="validate-error validate-error-path"></div>
          <div id="map_canvas"></div>
        </div>
      </div>
      <div class="row TimingAndDates">
        <div class="dateDeparture col-xs-24 col-sm-24 col-md-12">
          <p><?= Yii::t('poputchik', 'Дата отправления') ?></p>
          <?php $this->widget('application.components.widgets.DateTimeBlockWidget', array('htmlOptions'=>array('class' => 'validate-el validate-el-startAt'), 'namePrefix' => 'start', 'required' => true, 'date' => $search_info->when ? date('d.m.Y', $search_info->when) : false)); ?>
            <div class="validate-error validate-error-startAt"></div>
        </div>
        <div class="dateReverseDeparture col-xs-24 col-sm-24 col-md-12">
          <p><?= Yii::t('poputchik', 'Дата обратной поездки') ?></p>
          <?php $this->widget('application.components.widgets.DateTimeBlockWidget', array('htmlOptions'=>array('class' => 'validate-el validate-el-returnAt'), 'namePrefix' => 'return', 'required' => false, 'date' => $search_info->when ? date('d.m.Y', $search_info->when) : false)); ?>
            <div class="validate-error validate-error-returnAt"></div>
        </div>
      </div>
      <div class="row placeButtonResume placeButtonResume-one">
        <div class="buttonResume col-xs-24 col-sm-24 col-md-24">
          <a href="#" class="button button-large button-green next-step"><?= Yii::t('poputchik', 'Продолжить'); ?></a>
        </div>
      </div>
    </div>
    <div class="step">
      <h2><?= Yii::t('poputchik', 'Данные поездки');?></h2>
      <div class="row step2-row">
        <div class="rangeSlider col-xs-24 col-sm-24 col-md-12">
          <div class="firstRangeSlider amount">
            <p class="pricePlace">
              <span><?= Yii::t('poputchik', 'Цена')?>:</span>
              <a><input type="text" id="amount" ></a> <?= Yii::t('poputchik', 'за')?>
            </p>
            <select class="customSelect" id="price-for-place-quantity">
              <option value="1" selected><?= Yii::t('poputchik', 'одно место');?></option>
              <option value="2"><?= Yii::t('poputchik', 'два места');?></option>
              <option value="3"><?= Yii::t('poputchik', 'три места');?></option>
              <option value="4"><?= Yii::t('poputchik', 'четыре места');?></option>
            </select>

            <div class="clearfix"></div>

            <p class="label-block">
              <label class="min">1</label>
              <label class="max">1500</label>
            </p>

            <div class="clear"></div>
            <div id="slider-for-price">
              <img src="/images/circle.png" class="left-circle" alt=""/>
              <img src="/images/circle.png" class="right-circle" alt=""/>
                <div class="validate-error validate-error-cost"></div>
            </div>
          </div>
          <div class="firstRangeSlider freePlaces">
            <p class="pricePlace"><span><?= Yii::t('poputchik', 'Свободных мест')?>:</span></p>
            <select class="customSelect" id="pricePlaceQuantity">
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
            </select>

            <div class="clearfix"></div>
            <p class="label-block">
              <label class="min">1</label>
              <label class="max">4</label>
            </p>

            <div class="clear"></div>
            <div id="slider-for-place-quantity">
              <img src="/images/circle.png" class="left-circle" alt=""/>
              <img src="/images/circle.png" class="right-circle" alt=""/>
            </div>
              <div class="validate-error validate-error-places"></div>
          </div>
        </div>
        <div class="typeAuto col-xs-24 col-sm-24 col-md-12">
          <div id="type_auto-row" class="row">
            <div class="clearfix"><label for="PoputchikOrder_type_auto"><?= Yii::t('poputchik', 'Тип Авто')?></label></div>
            <?php $this->widget('application.components.widgets.AutoChooserWidget', array('class'=>'route_car')); ?>
          </div>
        </div>
      </div>
      <button type="button" class="btn btn-Param" data-toggle="collapse" data-target="#additionalParameters">
        <?= Yii::t('poputchik', 'Дополнительные параметры')?> <img src="/images/offerDriver/arro-mini-down-noactive.png" alt="" />
      </button>
      <div id="additionalParameters" class="collapse">
        <div class="row">
          <div class="additionalParam col-xs-24 col-sm-24 col-md-10">
            <div class="baggage">
              <span><?= Yii::t('poputchik', 'Размер багажа')?>:</span>
            </div>
            <div class="param-baggage param-baggage-one">
              <select class="customSelect" id="luggage">
                <option value="<?=PpRoute::LUGGAGE_SMALL?>"> <?= Yii::t('poputchik', 'маленький')?></option>
                <option value="<?=PpRoute::LUGGAGE_MEDIUM?>"><?= Yii::t('poputchik', 'средний')?></option>
                <option value="<?=PpRoute::LUGGAGE_LARGE?>"> <?= Yii::t('poputchik', 'большой')?></option>
              </select>
            </div>
          </div>
          <div class="additionalParam nonToggle deviation col-xs-24 col-sm-24 col-sm-offset-2 col-md-10 col-md-offset-2">
            <span><?= Yii::t('poputchik', 'Могу отклониться от курса')?>:</span>
            <div class="param-baggage param-baggage-two">
              <select class="customSelect" id="deviation">
                <option value="<?=PpRoute::DEVIATION_NONE?>">           <?= Yii::t('poputchik', 'Нет')?></option>
                <option value="<?=PpRoute::DEVIATION_WITHIN_15_MIN?>">  <?= Yii::t('poputchik', 'до 15 минут')?></option>
                <option value="<?=PpRoute::DEVIATION_WITHIN_30_MIN?>">  <?= Yii::t('poputchik', 'до 30 минут')?></option>
                <option value="<?=PpRoute::DEVIATION_WITHIN_1_HOUR?>">  <?= Yii::t('poputchik', 'до 1 часа')?></option>
                <option value="<?=PpRoute::DEVIATION_WITHIN_2_HOURS?>"> <?= Yii::t('poputchik', 'до 2 часов')?></option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="additionalParam  nonToggle col-xs-24 col-sm-12 col-md-12">
            <span><?= Yii::t('poputchik', 'Буду выезжать')?>:</span>

            <div class="param-baggage param-baggage-three">
              <select class="customSelect" id="punctuality">
                <option value="<?=PpRoute::PUNCTUALITY_EXACTLY?>">        <?= Yii::t('poputchik', 'В указанное время')?></option>
                <option value="<?=PpRoute::PUNCTUALITY_WITHIN_15_MIN?>">  <?= Yii::t('poputchik', '+/- 15 минут')?></option>
                <option value="<?=PpRoute::PUNCTUALITY_WITHIN_30_MIN?>">  <?= Yii::t('poputchik', '+/- 30 минут')?></option>
                <option value="<?=PpRoute::PUNCTUALITY_WITHIN_1_HOUR?>">  <?= Yii::t('poputchik', '+/- 1 час')?></option>
                <option value="<?=PpRoute::PUNCTUALITY_WITHIN_2_HOURS?>"> <?= Yii::t('poputchik', '+/- 2 часа')?></option>
              </select>
            </div>
          </div>

        </div>
      </div>
      <div class="row time-moveOut">
        <div class="dateDeparture valid_until validate-el validate-el-available_until">
          <p><?= Yii::t('poputchik', 'Обьявление дествительно до')?>:</p>
            <?php $this->widget('application.components.widgets.DateTimeBlockWidget', array('htmlOptions'=>array('class' => 'validate-el validate-el-available_until'), 'namePrefix' => 'valid', 'required' => true, 'date' => date('d.m.Y', time()+24*60*60*7))); ?>
            <div class="validate-error validate-error-available_until"></div>
        </div>
      </div>
      <div class="row">
        <div class="checkbox confirmRight">
          <input id="i_approve" type="checkbox" name="i_approve" checked hidden/>
          <label for="i_approve"><?= Yii::t('poputchik', 'Подтверждаю, что у меня есть действующие водительские права и действующая страховка')?></label>
            <div class="validate-error validate-error-i_approve"></div>
        </div>
      </div>
      <div class="row placeButtonResume placeButtonResume-two">
        <div class="buttonResume"><a href="#" class="button button-large button-green next-step"><?= Yii::t('poputchik', 'Опубликовать')?></a></div>
      </div>
    </div>
    <div class="step">
      <h2><?= Yii::t('poputchik', 'Мои данные')?></h2>
      <div class="row form_human">
        <div class="col-xs-24 col-sm-24 col-md-8">
            <div class="validate-error validate-error-name"></div>
          <input size="50" maxlength="20" id="user_name"   class="form_human_data default validate-el validate-el-name" placeholder="<?= Yii::t('poputchik', 'Имя *')?>" type="text" value="" name="name">
            <div class="validate-error validate-error-phone"></div>
          <input size="50" maxlength="20" id="user_phone"  class="form_human_data default validate-el validate-el-phone" placeholder="<?= Yii::t('poputchik', 'Телефон *')?>" type="text" value="" name="telephone">
            <div class="validate-error validate-error-email"></div>
          <input size="50" maxlength="20" id="user_email"  class="form_human_data default validate-el validate-el-email" placeholder="<?= Yii::t('poputchik', 'E-mail')?>" type="text" value="" name="e-mail">

            <p class="warning"><?= Yii::t('poputchik', 'Поля отмеченные звездочкой (*) обязательны для заполнения')?></p>
        </div>
        <div class="col-xs-24 col-sm-24 col-md-10 comments-wrapper">
            <div class="validate-error validate-error-comment"></div>
          <textarea id="advert_comment" class="validate-el validate-el-comment" cols="45" name="comments" placeholder="<?= Yii::t('poputchik', 'Коментарий')?>"></textarea>

        </div>
        <div class="col-xs-24 col-sm-24 col-md-6">
          <?php $this->widget('application.components.widgets.UploaderWidget', array('namePrefix' => 'userpic', 'layout' => 'avatar')); ?>
            <div class="validate-error validate-error-avatar"></div>
        </div>
      </div>
      <div class="row placeButtonResume ">
        <div class="buttonResume">
          <button type="submit" class="button button-large button-green"><?= Yii::t('poputchik', 'Готово')?></button>
        </div>
      </div>
    </div>
  </form>
  <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=true&region=<?=strtoupper(Yii::app()->language) ?>&language=<?= Yii::app()->language ?>"></script>

<?php
$cs = Yii::app()->clientScript;
$am = Yii::app()->assetManager;
$jsUrl = $am->publish(Yii::getPathOfAlias('webroot.js'), false, -1, YII_DEBUG);
$cs->registerScriptFile($jsUrl . '/itw-forms.js', CClientScript::POS_END);
$cs->registerScriptFile($jsUrl . '/poputchik/driver-form.js', CClientScript::POS_END);
$cs->registerScriptFile($jsUrl . '/poputchik/i18n/driver_form.'.Yii::app()->language.'.js', CClientScript::POS_END);
$cs->registerScript('router_init', "
    $(window).ready(function () {
      router.init(".Yii::app()->location->city->longitude.",".Yii::app()->location->city->latitude.");
    });
", CClientScript::POS_END);
