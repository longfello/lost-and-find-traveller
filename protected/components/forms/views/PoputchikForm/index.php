<?php
/* @var $this PoputchikForm */
?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
<?php
  $am    = Yii::app()->assetManager;
  $cs    = Yii::app()->clientScript;
  $jsUrl = $am->publish(Yii::getPathOfAlias('webroot.js'), false, -1, YII_DEBUG);
  $cs->registerScriptFile($jsUrl.'/poputchik/router.js', CClientScript::POS_END);
  $cs->registerScriptFile($jsUrl.'/poputchik/search_form.js', CClientScript::POS_END);
?>

<div id="poput-orders-filter" class="container poput-orders-filter container-clearfix form ext top_banner_position">
  <div class="row">
    <form method="post" id="poputchik-search-form">
      <div class="row">
        <div class="type-order col-md-12 col-sm-12 col-xs-12  group-orderType">
          <div class="col-md-24 col-sm-24 col-xs-24">
            <div class="r-item first">
              <?php
                echo CHtml::radioButton('type_order', $this->type == PpRoute::TYPE_DRIVER, array('value' => PpRoute::TYPE_DRIVER, 'id' => PpRoute::TYPE_DRIVER));
                echo CHtml::label(Yii::t('poputchik', 'Найти водителя'), PpRoute::TYPE_DRIVER);
              ?>
            </div>
            <div class="r-item">
              <?php
                echo CHtml::radioButton('type_order', $this->type == PpRoute::TYPE_PASSENGER, array('value' => PpRoute::TYPE_PASSENGER, 'id' => PpRoute::TYPE_PASSENGER));
                echo CHtml::label(Yii::t('poputchik', 'Найти пассажира'), PpRoute::TYPE_PASSENGER);
              ?>
            </div>
          </div>
          <div class="block-clearfix"></div>
        </div>

        <div class="type-route col-md-12 col-sm-12 col-xs-12 group-routeType">
          <div class="col-md-24 col-sm-24 col-xs-24">
            <div class="r-item first">
              <?php
                echo CHtml::radioButton('type_route', $this->typeRoute == PpRoute::TYPE_ROUTE_SAME, array('value' => PpRoute::TYPE_ROUTE_SAME, 'id' => 'type-route_1'));
                echo CHtml::label(Yii::t('poputchik', 'По городу'), 'type-route_1');
              ?>
            </div>
            <div class="r-item last">
              <?php
                echo CHtml::radioButton('type_route', $this->typeRoute == PpRoute::TYPE_ROUTE_ANOTHER, array('value' => PpRoute::TYPE_ROUTE_ANOTHER, 'id' => 'type-route_2'));
                echo CHtml::label(Yii::t('poputchik', 'Межгород'), 'type-route_2');
              ?>
            </div>

          </div>
          <div class="block-clearfix"></div>
        </div>
      </div>


      <div class="dates-row route-1">
        <div class="sity-row row">
          <div id="start_settlement-wrapper">
            <?php $this->widget('application.components.widgets.CityAcWidget', array('locate' => false, 'id' => $this->cityFrom?$this->cityFrom->id:false, 'class' => 'route_start g_search_au', 'htmlOptions' => array(
                'size' => 50,
                'maxlength' => 255,
                'id' => "start_name",
                'placeholder' => Yii::t('poputchik', "ГОРОД"),
                'autocomplete' => "off"
            ))); ?>
            <a href='#start_name' class="clear-input glyphicon glyphicon-remove"></a>
            <span class="example">
              <?= Yii::t('poputchik', 'Например:')?>
              <?php $this->widget('application.components.widgets.chooseCityByLinkWidget', array('selector' => '#start_name', 'name' => Yii::t('poputchik', 'Москва'))) ?>,
              <?php $this->widget('application.components.widgets.chooseCityByLinkWidget', array('selector' => '#start_name', 'name' => Yii::t('poputchik', 'Киров'))) ?>
            </span>
          </div>

          <div class="for-route-2 route-direction"><a href="#"><img src="/images/arrow-search.png" alt=""></a></div>

          <div id="end_settlement-wrapper" class="row for-route-2">
            <?php $this->widget('application.components.widgets.CityAcWidget', array('locate' => false, 'id' => $this->cityTo?$this->cityTo->id:false, 'class' => 'route_end g_search_au', 'htmlOptions' => array(
                'size' => 50,
                'maxlength' => 255,
                'id' => "end_name",
                'placeholder' => Yii::t('poputchik', "ГОРОД"),
                'autocomplete' => "off"
            ))); ?>
            <a href='#end_name' class="clear-input glyphicon glyphicon-remove"></a>
            <span class="example">
              <?= Yii::t('poputchik', 'Например:')?>
              <?php $this->widget('application.components.widgets.chooseCityByLinkWidget', array('selector' => '#end_name', 'name' => Yii::t('poputchik', 'Москва'))) ?>,
              <?php $this->widget('application.components.widgets.chooseCityByLinkWidget', array('selector' => '#end_name', 'name' => Yii::t('poputchik', 'Киров'))) ?>
            </span>
          </div>
        </div>
        <div class="address-row row">
          <div class="f-block1">
            <?php $this->widget('application.components.widgets.AddressAcWidget', array(
                'layout'=>'filter',
                'class'=>'route_start_address default',
                'parent' => 'route_start',
                'defaults' => $this->from,
                'htmlOptions'=>array(
                    'id' => 'street_from',
                    'autocomplete' => "off"
                ))); ?>
          </div>
          <div class="f-block2 route-street" style="text-align:center;">
            <a href="#"><img src="/images/arrow-search.png" alt=""></a>
          </div>
          <div class="f-block3">
            <?php $this->widget('application.components.widgets.AddressAcWidget', array(
                'layout'=>'filter',
                'class'=>'route_end_address default',
                'parent' => 'route_start',
                'defaults' => $this->to,
                'htmlOptions'=>array(
                    'id' => 'street_to',
                    'autocomplete' => "off"
                ))); ?>
          </div>
        </div>

        <?php /*
        <a class="btn btn-primary btn-more collapsed" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
          <span class="collapsed <?=$this->isAdditionalFilled?"in":""?>">Свернуть</span>
          <span class="collapsed  <?=$this->isAdditionalFilled?"":"in"?>">Еще</span>
        </a>
        */ ?>

        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'date_from',
            'language' => Yii::app()->language,
            'value' => $this->date?date('d.m.Y', $this->date):false,
            'options'=>array(
                'numberOfMonths'=>2,
                'showButtonPanel'=>true,
            ), 'htmlOptions' => array(
                'class' => 'tripDate', // textField size
                'size' => '10', // textField size
                'maxlength' => '10', // textField maxlength
                'defaultValue' => Yii::t('poputchik', "Когда"),
            ),
        ));
        ?>
        <p class="departureDate"><?=Yii::t('poputchik', 'Дата отправления')?></p>

        <div class="clearfix"></div>
        <?php /*
        <div class="collapse <?=$this->isAdditionalFilled?"in":""?>" id="collapseExample">
          <div class="well">
            <div id="date_from-row" class="row col-md-12 col-sm-12">
              <select class="customSelect tripAutoClass">
                <option value="any"><?=Yii::t('poputchik', 'auto-class-any')?></option>
                <?php foreach(AutoModels::getClassesList() as $class){ ?>
                  <option value="<?=$class?>" <?=($class == $this->class)?"selected":""?>><?=Yii::t('poputchik', 'auto-class-'.$class)?></option>
                <?php } ?>
              </select>
              <p class="trip"><?=Yii::t('poputchik', 'Класс поездки')?></p>
            </div>
            <div class="right-column col-md-12 col-sm-12">
              <div>
                <ul class="amount-block-man">
                  <?php for($i=1; $i<5; $i++){ ?>
                    <li>
                      <input type="radio" id="man-<?=$i?>" value="<?=$i?>" <?=($i == $this->seats)?'checked="checked"':''?> name="seats">
                      <label for="man-<?=$i?>" class="man man-<?=$i?> radio"></label>
                    </li>
                  <?php } ?>
                </ul>
                <p><?=Yii::t('poputchik', 'Количество пассажиров');?></p>
              </div>
            </div>
          </div>
        </div>
        */ ?>
      </div>

  </div>
  <div id="search-button">
    <button type="button"><i class="iSearch"></i>&nbsp;&nbsp;<?=Yii::t('poputchik', 'Найти')?></button>
  </div>
  <a id="clear-button" href="<?= Yii::app()->homeUrl ?><?=$this->typeRoute?>">
    <?= Yii::t('poputchik', 'Сбросить') ?>
  </a>
  <div class="clear">
    <div class="header-fix">
      <div class="container">
        <div class="type-order col-md-24 col-sm-24 col-xs-24">
          <div class="col-md-24 col-sm-24 col-xs-24">
	          <div id="search-button-fixed">
		          <div class="search-button-inner">
			          <a href="/"><i class="iSearch"></i>&nbsp;&nbsp;<?=Yii::t('poputchik', 'Новый поиск');?></a>
		          </div>
	          </div>
            <div class="r-item first">
              <a href="/poputchikOrder/addAdvert/type/passenger">Подать заявку пассажира</a>
            </div>
            <div class="r-item last">
              <a href="/poputchikOrder/addAdvert/type/driver">Подать заявку водителя</a>
            </div>
          </div>
          <div class="block-clearfix"></div>
        </div>
      </div>
    </div>
  </div>
  </form>
</div>
<div class="clear"></div>