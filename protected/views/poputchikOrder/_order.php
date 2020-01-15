<?php
  /* @var $this PoputchikOrderController */
  /* @var $dataProvider CActiveDataProvider */
  /* @var $order PoputchikOrder */
  global $days_of_week;

  $cs = Yii::app()->clientScript;
//  $cs->registerCssFile('/css/poputchik.css');
  $language = Yii::app()->language;
  $profile = Profile::model()->find(array('condition' => 'user_id=:user_id', 'params' => array(':user_id' => $order->id_user)));
  $user = User::model()->find(array('condition' => 'id=:id', 'params' => array(':id' => $order->id_user)));
  $colorbox = $this->widget('application.extensions.colorpowered.JColorBox');
  //Call addInstance (chainable) from the widget generated.
  $colorbox->addInstance('.cbox');

  $override_time = $order->time_to_1 . ($order->time_to_1 == $order->time_to_2 ? '' : '&ndash;' . $order->time_to_2);
  $override_time = $override_time?" <span class='info-time'><i class='icon icon-time'></i> ".$override_time:"</span>";

  $place_from = new locationHelper($order->path->startSettlement->id_settlement);
  $place_to   = new locationHelper($order->path->endSettlement->id_settlement);
  $route = ($order->route)?$order->route:$order->path;

  if ($order->type_route == 1){
    $route_from  = $route_to = $order->idSettlement->name;
  } else {
    $route_from  = $order->direction ? $route->endSettlement->name : $route->startSettlement->name;
    $route_to    = $order->direction ? $route->startSettlement->name : $route->endSettlement->name;
  }
?>

<div id="poput-order-<?= $order->id_order ?>" class="poput-order poput-order-meg clearfix  with-cost">
    <div class="item-row row">
        <div class="left-wrap col-md-4 col-sm-4 left-wrap-img">
        <div class="photo">
          <?php if ($profile->photo && is_file(Yii::getpathOfAlias('webroot') . $profile->photo)): ?>
            <?php
            echo Yii::app()->easyImage->thumbOf($profile->photo, array('resize' => array('width' => 100, 'height' => 100, 'master' => EasyImage::RESIZE_INVERSE), 'crop' => array('width' => 100, 'height' => 100), 'type' => 'jpg', 'quality' => 60,));
            ?>
          <?php else: ?>
            <?php if ($order->type_order == 1) {
              echo "<img src='/images/poputchik/driver.png' />";
            } else {
              echo "<img src='/images/icons/car-bg-avatar.png' />";
            }
            ?>
          <?php endif; ?>
            <div class="status-client"></div>
        </div>
        <div class="name"><?= $order->name ?></div>
        </div>
        <div class="right-wrap col-md-16 col-sm-16">
            <?php if (!$order->isActive()): ?><p class="filled">Поездка состоялась</p><?php endif; ?>
        <div class="route-details row">
          <div class="route-detail route-detail-from col-md-10 col-sm-24 col-xs-24">
            <div class="route-detail-wrapper">
              <div class="route-row route-settlement"><span><?= $route_from ?></span></div>
              <div class="route-row route-place"><?= $order->from_place?"Район: ".$order->from_place:'' ?></div>
              <div class="route-row route-region"><?= $place_from->region->name ?></div>
              <div class="route-row route-country"><?= $place_from->country->name ?></div>

            </div>
          </div>
          <div class="route-detail s-delim-new col-md-2 col-sm-24 col-xs-24">
            <div class="route-detail-wrapper">
              <span class="arrow-route"></span>
            </div>
          </div>
          <div class="route-detail route-detail-to col-md-10 col-sm-24 col-xs-24">

            <div class="route-detail-wrapper">
              <div class="route-row route-settlement"><span><?= $route_to ?></span></div>
              <div class="route-row route-place"><?= $order->to_place?"Район: ".$order->to_place:'' ?></div>
              <div class="route-row route-region"><?= $place_to->region->name ?></div>
              <div class="route-row route-country"><?= $place_to->country->name ?></div>
            </div>
          </div>
        </div>
        <?php if ($order->type_time == 1): ?><div class="date_info"><?php echo Yii::app()->dateFormatter->format("EEEE, d MMMM", $order->date_from); ?> <?= $override_time ?></div><?php endif; ?>
        <?php if ($order->type_time == 2): ?><div class="date_info no_capitalize">По определенным дням недели</div><?php endif; ?>
        <?php if ($order->type_time == 3): ?><div class="date_info no_capitalize">По определенным дням месяца</div><?php endif; ?>

        <?php if (Yii::app()->user->id == $order->id_user): ?>
          <div class="del-icon">
            <form method="post" action="/<?= $language ?>/poputchikOrder/del_my/<?= $order->id_order ?>">
              <a class="del" href="/<?= $language ?>/poputchikOrder/del_my/<?= $order->id_order ?>" onclick="if(confirm('Вы уверены, что хотите удалить данный элемент?')) $(this).parent().submit(); return false;"></a>
              <input type="hidden" name="returnUrl" value="<?= $_SERVER['REQUEST_URI'] ?>"/>
            </form>
            <div class="del-label" id="<?= $order->id_order ?>">Удалить</div>
          </div>
          <?php if ($order->status == 1) { ?>
            <div class="update-icon">
              <form method="post" action="/<?= $language ?>/poputchikOrder/update/<?= $order->id_order ?>">
                <a class="update" href="/<?= $language ?>/poputchikOrder/update/<?= $order->id_order ?>" onclick="if(confirm('При изменении, объявление будет повторно отпревлено на модерацию и временно пропадет из публикации.')) $(this).parent().submit(); return false;"></a>
              </form>
              <div class="update-label" id="up<?= $order->id_order ?>">Редактировать</div>
            </div>
          <?php } else { ?>
            <div class="update-icon" style="opacity:0.3">
              <a class="update" href="#" onclick="alert('Объявление находится на модерации - редактирование запрещено'); return false;"></a>
              <div class="update-label" id="up<?= $order->id_order ?>">Редактировать</div>
            </div>
          <?php } ?>
        <?php endif; ?>

        <div class="clearfix"></div>
        <?php if (Yii::app()->user->checkAccess('PoputchikOrder.moderator')): ?>
          <div class="control-icons">
            <?php if (!$order->status): ?>
              <a class="moderate" href="/<?= $language ?>/poputchikOrder/moderate/<?= $order->id_order ?>"></a>
            <?php endif; ?>
            <a class="edit" href="/<?= $language ?>/poputchikOrder/update/<?= $order->id_order ?>"></a>
            <form method="post" action="/<?= $language ?>/poputchikOrder/delete/<?= $order->id_order ?>">
              <a class="del" href="/<?= $language ?>/poputchikOrder/delete/<?= $order->id_order ?>" onclick="if(confirm('Вы уверены, что хотите удалить данный элемент?')) $(this).parent().submit(); return false;"></a>
              <input type="hidden" name="returnUrl" value="<?= $_SERVER['REQUEST_URI'] ?>"/>
            </form>
          </div>
        <?php endif; ?>
        <div class="block-clearfix"></div>
        </div>
        <div class="cost-wrapper col-md-4 col-sm-4">
        <div class="sum">
          <?php if ($order->sum){ ?>
           <!-- <div class="sum_label"><?= Yii::t('poputchik', 'Цена') ?> </div > -->
            <div class="sum_total"><?= $order->sum ?> </div>
            <?php
              if (isset($order->type_sum)) {
                $type = CHtml::listData(Referens::getReferensByAlias('poput_type_sum'), 'IdRef', 'NameRef');
                print "<div class='cost-type'>{$type[$order->type_sum]}</div>";
              }
            ?>
          <?php } else { ?>
          <!--  <div class="sum_label"><?= Yii::t('poputchik', 'Цена') ?>  </div> -->
            <div class="cost-type">не указана</div>
          <?php } ?>
        </div>
        </div>
    </div>
  <a class="more_info" href="/<?= $language ?>/poputchikOrder/details/id/<?= $order->id_order ?>"><?= $order->type_order == 2 ? Yii::t('poputchik', 'Связаться с пассажиром') : Yii::t('poputchik', 'Связаться с водителем') ?></a>
</div>

<?php

  Yii::app()->clientScript->registerScript('ajust-city-name-font-size', "
$('.route-settlement').textfill(15);
", CClientScript::POS_READY);