<?php
/* @var $this PoputchikOrderController */
/* @var $order PpRoute */
$cs = Yii::app()->clientScript;
$cs->registerScriptFile('/js/service.js', CClientScript::POS_END);
$cs->registerScriptFile('/js/poputchik.js', CClientScript::POS_END);

$am    = Yii::app()->assetManager;
$jsUrl = $am->publish(Yii::getPathOfAlias('webroot.js'), false, -1, YII_DEBUG);
$cs->registerScriptFile($jsUrl.'/poputchik/results.js', CClientScript::POS_END);

$user = $order->user;
$profile = $order->user->profile;
$like_url = 'http://' . $_SERVER['SERVER_NAME'] . '/poputchikOrder/details/id/' . $order->id;
$photo_url = 'http://' . $_SERVER['SERVER_NAME'] . $profile->photo;

$order->viewed++; $order->save();

?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
<div class="wraper-for-marging trip-page">
    <div class="trip-page-buttons clearfix">
<!--        <div class="container">-->
            <div class="type-order col-md-24 col-sm-24 col-xs-24">
                <div class="col-md-24 col-sm-24 col-xs-24">
                    <div id="trip-page-search-button">
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
<!--        </div>-->
    </div>
  <?
  $this->breadcrumbs = array();
  if ($order->from_id == $order->to_id)
    $this->breadcrumbs[Yii::t('poputchik', 'Попутчики по городу')] = '/'.PpRoute::TYPE_ROUTE_SAME;
  else
    $this->breadcrumbs[Yii::t('poputchik', 'Попутчик в другой город')] = '/'.PpRoute::TYPE_ROUTE_ANOTHER;
  $this->breadcrumbs[] = $profile->first_name;
  ?>
  <div class="left_column">
    <div id="poput-order-<?= $order->id ?>" class="poput-order clearfix poput-order-details">
      <!--<div class="date_info">
        <?php
/*          if(is_null($order->departure_periodicity)) {
            echo Yii::app()->dateFormatter->format("EEEE d MMMM", $order->departure);
          } else {
            $days = explode(',', $order->departure_periodicity);
            $days_locale = array();
            foreach($days as $day){
              $days_locale[] = Yii::t('poputchik', $day);
            }
            $days_locale = implode(', ', $days_locale);
            echo $days_locale;
          }
        */?>
      </div>-->

      <?php if (!$order->isActive()): ?>
        <p class="filled">Поездка состоялась</p>
      <?php endif; ?>

      <div class="left">
        <div class="photo">
          <?php if ($profile->photo && is_file(Yii::getpathOfAlias('webroot') . $profile->photo)): ?>
            <?php /*<a href="<?=$profile->photo?>" class="cbox">?> */ ?>
            <?php
            echo Yii::app()->easyImage->thumbOf($profile->photo,
              array(
                'resize' => array('width' => 100, 'height' => 100, 'master' => EasyImage::RESIZE_INVERSE),
                'crop' => array('width' => 100, 'height' => 100),
                'type' => 'jpg',
                'quality' => 60,
              ));
            ?>
            <?php /* </a> */ ?>
          <?php else: ?>
            <?php if ($order->type == PpRoute::TYPE_DRIVER)
              echo "<img src='/images/poputchik/driver.png' />";
            else
              echo "<img src='/images/poputchik/sheep.png' />";
            ?>
          <?php endif; ?>
        </div>
        <div class="name"><?= $profile->first_name ?></div>
        <?php if ($order->type == PpRoute::TYPE_DRIVER): ?>
          <div class="auto type-<?= $order->auto->type->id ?>">
            <div class="auto-brand"><?= $order->auto->brand?$order->auto->brand->brand:'' ?></div>
            <div class="auto-model"><?= $order->auto->model ?></div>
          </div>
        <?php endif; ?>
        <?php if ($order->isActive()): ?>
          <a href="#" class="ajax phone" rel="<?= $order->id ?>"><?= Yii::t('general', 'Номер телефона') ?></a>
          <?php if ($user->email != ""): ?>
            <a href="#" class="ajax email" rel="<?= $order->uid ?>"><?= Yii::t('general', 'Написать автору') ?></a>
          <?php endif; ?>
        <?php endif; ?>
      </div>

      <div class="right">
        <div class="fromto">
          <?php if ($order->from_id == $order->to_id){ ?>
            <h2>
              <div class="s-header"><?=Yii::t('poputchik','Попутчик по городу')?></div>
              <div class="settlement"><?= $order->fromLocation->name ?></div>
              <!--<div class="route"><?= $order->from_address ?> &rarr; <?= $order->to_address ?></div>-->

              <div class="route-details row">
                <div class="route-detail route-detail-from">
                  <div class="route-detail-wrapper">
                    <div class="route-row route-settlement"><span><?= $order->from_address ?></span></div>
                  </div>
                </div>
                <div class="route-detail s-delim-new black-arrow">
                  <div class="route-detail-wrapper">
                    <span class="arrow-route">→</span>
                  </div>
                </div>
                <div class="route-detail route-detail-to">
                  <div class="route-detail-wrapper">
                    <div class="route-row route-settlement"><span><?= $order->to_address ?></span></div>
                  </div>
                </div>
              </div>

            </h2>
          <?php } else { ?>
            <div class="s-header"><?= Yii::t('poputchik', 'Попутчики из')?></div>
            <div class="s1">
              <div class="settlement"><?= $order->fromLocation->name ?></div>
            </div>
            <div class="s-delim-new">→</div>
            <div class="s2">
              <div class="settlement"><?= $order->toLocation->name ?></div>
            </div>
            <div class="clearfix"></div>
          <?php } ?>
        </div>

        <div class="datetimes normal">
          <div class="datetime from">
            <div class="label"><?= Yii::t('poputchik', 'Отправление') ?></div>
              <div class="date">
                <div class="icon"></div>
                <?php
                if (!$order->departure_periodicity) {
                  if ($order->departure) {
                    echo Yii::app()->dateFormatter->format("EEEE, d MMMM y", $order->departure);
                  }
                }

                if ($order->departure) {
                  echo ', ';
                  echo Yii::app()->dateFormatter->format("HH:mm", $order->departure);
                }

                ?>
              </div>
            <div class="clearfix"></div>
          </div>
        </div>
        <div class="clearfix"></div>

          <?php if ($order->free_seats): ?>
              <div class="free_places_count">
                  <?=Yii::t('poputchik','Свободных мест')?>: <?= $order->free_seats ?>
              </div>
          <?php endif; ?>
          <?php if ($order->cost): ?>
              <div class="sum"><?= $order->cost ?><span class="rouble">Р</span></div>
          <?php endif; ?>

        <div class="r-settlements">
        <?php if ($route_detailed_path): ?>
            <div>
              <span class="label"><?= Yii::t('poputchik', 'Через') ?>: </span>
              <ul>
              <?php foreach($route_detailed_path as $one) { ?>
                  <li><?=$one['name']?></li>
              <?php } ?>
              </ul>
            </div>
        <?php endif; ?>

        <?php if ($order->comment): ?>
          <div class="comment">
            <?= $order->comment ?>
          </div>
        <?php endif; ?>

        <?php /* if (Yii::app()->user->checkAccess('PoputchikOrder.moderator')): ?>
          <div class="control-icons">
            <?php if (!$order->status): ?><a class="moderate"
                                             href="/<?= $language ?>/poputchikOrder/moderate/<?= $order->id_order ?>"></a><?php endif; ?>
            <a class="edit" href="/<?= $language ?>/poputchikOrder/update/<?= $order->id_order ?>"></a>

            <form method="post" action="/<?= $language ?>/poputchikOrder/delete/<?= $order->id_order ?>"><a class="del"
                                                                                                            href="/<?= $language ?>/poputchikOrder/delete/<?= $order->id_order ?>"
                                                                                                            onclick="if(confirm('Вы уверены, что хотите удалить данный элемент?')) $(this).parent().submit(); return false;"></a><input
                  type="hidden" name="returnUrl" value="<?= $_SERVER['REQUEST_URI'] ?>"/></form>
          </div>
        <?php endif; */?>
      </div>
    </div>

      <div class="clear"></div>

      <div class="poput-order-details-map">
          <a class='show-the-map' href="#" data-id="<?= $order->id ?>"><?= Yii::t('poputchik', 'Показать маршрут на карте');?></a>
          <div class="the-map-wrapper">
              <div class="the-map-route-info"></div>
              <div class="the-map"></div>
          </div>

          <div class="advert-footer">
            <div class="additional-advert-info">
              <div class="info-item">
                <?= Yii::t('poputchik', 'Дата публикации: '); ?>
                <?= Yii::app()->dateFormatter->format("d MMMM y", $order->created) ?>
              </div>
              <div class="info-item">
                <?= Yii::t('poputchik', 'Просмотров: '); ?>
                <?= $order->viewed ?>
              </div>
              <div class="info-item like-buttons">
                <div class="save_info"><?= Yii::t('poputchik', 'Cохрани поездку чтобы не забыть о ней') ?></div>
                <div class="share42init" data-image="<?= $photo_url ?>" data-url="<?= $like_url ?>"
                     data-title="<? $order->type == PpRoute::TYPE_PASSENGER ? print(Yii::t('poputchik', 'Найти пассажира: ')) : print(Yii::t('poputchik', 'Найти водителя: '));
                     ($order->from_id == $order->to_id) ? print("из города " . $order->fromLocation->name) : print("из города " . $order->fromLocation->name . " в " . $order->toLocation->name) ?>"></div>
              </div>
            </div>
          </div>
      </div>
  </div>
  <div class="clearfix">

  </div>


</div>

</div>
<div class="overlay"></div>
<div class="my_popup">
  <div class="form">
    <?php
    $ContactForm = new ContactForm;
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'contact-form',
        'enableClientValidation' => true,
        'action' => CHtml::normalizeUrl(Yii::app()->request->baseUrl . '/poputchikOrder/sendemail'),
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>
    <?php echo CHtml::hiddenField('user'); ?>
    <div class="title">Письмо автору</div>

    <div class='left'>
      <?php echo CHtml::label('Ваше имя', 'text'); ?>
      <?php echo CHtml::textField('name'); ?>

      <?php echo CHtml::label('Ваш E-mail', 'user'); ?>
      <?php echo CHtml::emailField('sender'); ?>
    </div>
    <div class='right'>
      <?php echo CHtml::label('Текст', 'text'); ?>
      <?php echo CHtml::textArea('text', '', array('rows' => 8, 'cols' => 50, 'style' => 'resize:none')); ?>
    </div>
    <div class="block-clearfix"></div>
    <div style="width:100%;height: 60px;position: relative;text-align: right;">
      <?php $this->widget('CCaptcha'); ?>
      <?php echo CHtml::textField('captcha'); ?>
      <?php echo CHtml::button('Отправить', array('id' => 'button')); ?>
    </div>


    <div id="contact_message"></div>
    <?php $this->endWidget(); ?>
  </div>
  <!-- form -->
</div>
