<?php
/* @var $this PoputchikOrderController */
/* @var $dataProvider CActiveDataProvider */
/* @var $model PpRoute */
/* @var $user User */
/* @var $engine PoputchikSearcher_basic */
$language = Yii::app()->language;
$user     = $model->user;
$profile  = $model->user->profile;
?>

  <div id="poput-order-<?= $model->id ?>" class="poput-order poput-order-meg clearfix  with-cost">

    <div class="item-row row">

      <div class="left-wrap left-wrap-img">
        <div class="photo">
          <?php if ($profile->photo && is_file(Yii::getpathOfAlias('webroot') . $profile->photo)): ?>
            <?php
            echo Yii::app()->easyImage->thumbOf(Yii::getpathOfAlias('webroot') . $profile->photo, array('resize' => array('width' => 100, 'height' => 100, 'master' => EasyImage::RESIZE_INVERSE), 'crop' => array('width' => 100, 'height' => 100), 'type' => 'jpg', 'quality' => 60,));
            ?>
          <?php else: ?>
              <img src='/images/no-photo.jpg' />
          <?php endif; ?>
          <div class="status-client">
            <?php if ($model->type == PpRoute::TYPE_DRIVER) {
              echo "<img src='/images/poputchik/driver3.png' />";
            } elseif ($model->type == PpRoute::TYPE_PASSENGER) {
              echo "<img src='/images/icons/car-bg-avatar.png' />";
            } ?>
          </div>
        </div>
        <div class="name"><?= $profile->first_name ?></div>
      </div>
      <div class="right-wrap">
        <?php if (!$model->enabled): ?><p class="filled"><?=Yii::t('poputchik', 'Поездка состоялась'); ?></p><?php endif; ?>

          <div class="date_info">
          <span>
            <?php
            if (!$model->departure_periodicity) {
                if ($model->departure) {
                    echo Yii::app()->dateFormatter->format("EEEE, d MMMM y", $model->departure);
                }
            } elseif ($model->departure_periodicity) {
                $days = explode(',', $model->departure_periodicity);
                $days_locale = array();
                foreach($days as $day){
                    $days_locale[] = Yii::t('poputchik', $day);
                }
                $days_locale = implode(', ', $days_locale);
                echo Yii::t('poputchik', 'По определенным дням недели: ').$days_locale;
            }

            if ($model->departure) {
                $dep = Yii::app()->dateFormatter->format("HH:mm", $model->departure);
                if ($dep !== '00:00') {
                    echo ', ';
                    echo $dep;
                }
            }

            ?>
          </span>
          </div>

        <?php
          $another_source = ($engine->form->cityFrom && $engine->form->cityFrom->id && $model->from_id != $engine->form->cityFrom->id);
          $another_target = ($engine->form->cityTo   && $engine->form->cityTo->id   && $model->to_id != $engine->form->cityTo->id && $model->from_id != $engine->form->cityTo->id);
        ?>

        <?php if ($model->from_id == $model->to_id) { ?>
          <div class="route-details row">
            <div class="route-detail route-detail-from">
              <div class="route-detail-wrapper">
                <div class="route-row route-settlement"><span><?= $model->from_address ?></span></div>
                <div class="adress-hide">
                  <div class="route-row route-region"><?=$model->fromLocation->name?></div>
                  <div class="route-row route-region"><?=$model->fromLocation->zoneModel->name?></div>
                  <div class="route-row route-country"><?= $model->fromLocation->countryModel->name ?></div>
                </div>
              </div>
            </div>
            <div class="route-detail s-delim-new black-arrow">
              <div class="route-detail-wrapper">
                <span class="arrow-route">→</span>
              </div>
            </div>
            <div class="route-detail route-detail-to">
              <div class="route-detail-wrapper">
                <div class="route-row route-settlement"><span><?= $model->to_address ?></span></div>
                <div class="adress-hide">
                  <div class="route-row route-region"><?= $model->toLocation->name ?></div>
                  <div class="route-row route-region"><?= $model->toLocation->zoneModel->name ?></div>
                  <div class="route-row route-country"><?= $model->toLocation->countryModel->name ?></div>
                </div>
              </div>
            </div>
          </div>
        <?php } else { ?>
          <div class="route-details row">
            <div class="route-detail route-detail-from <?=$another_source?"muted":""?>">
              <div class="route-detail-wrapper">
                <div class="route-row route-settlement"><span><?= $model->fromLocation->name ?></span></div>
                <div class="adress-hide">
                  <div class="route-row route-region"><?=$model->fromLocation->zoneModel->name?></div>
                  <div class="route-row route-country"><?= $model->fromLocation->countryModel->name ?></div>

                </div>
              </div>
            </div>
            <?php if ($another_source) { ?>
              <div class="route-detail s-delim-new no-black">
                <div class="route-detail-wrapper">
                  <span class="arrow-route">→</span>
                </div>
              </div>

              <div class="route-detail route-detail-from">
                <div class="route-detail-wrapper">
                  <div class="route-row route-settlement"><span><?=  $engine->form->cityFrom->name ?></span></div>
                  <div class="adress-hide">
                    <div class="route-row route-region"><?= $engine->form->cityFrom->zoneModel->name?></div>
                    <div class="route-row route-country"><?=  $engine->form->cityFrom->countryModel->name ?></div>

                  </div>
                </div>
              </div>
            <?php } ?>
            <div class="route-detail s-delim-new black-arrow">
              <div class="route-detail-wrapper">
                <span class="arrow-route">→</span>
              </div>
            </div>
            <?php if ($another_target) { ?>
              <div class="route-detail route-detail-to">
                <div class="route-detail-wrapper">
                  <div class="route-row route-settlement"><span><?=  $engine->form->cityTo->name ?></span></div>
                  <div class="adress-hide">
                    <div class="route-row route-region"><?= $engine->form->cityTo->zoneModel->name?></div>
                    <div class="route-row route-country"><?=  $engine->form->cityTo->countryModel->name ?></div>
                  </div>
                </div>
              </div>
              <div class="route-detail s-delim-new no-black">
                <div class="route-detail-wrapper">
                  <span class="arrow-route">→</span>
                </div>
              </div>
            <?php } ?>
            <div class="route-detail route-detail-to <?= $another_target?"muted":"" ?>">
              <div class="route-detail-wrapper">
                <div class="route-row route-settlement"><span><?= $model->toLocation->name ?></span></div>
                <div class="adress-hide">
                  <div class="route-row route-region"><?= $model->toLocation->zoneModel->name ?></div>
                  <div class="route-row route-country"><?= $model->toLocation->countryModel->name ?></div>
                </div>
              </div>
            </div>
          </div>
        <?php }?>


        <div class="clearfix"></div>
        <span class='show-the-map' data-id="<?= $model->id ?>"><?= Yii::t('poputchik', 'Показать маршрут на карте');?></span>
      </div>
      <div class="cost-wrapper">
        <div class="sum">
          <?php if ($model->cost){ ?>
            <div class="sum_total"><?= $model->cost ?><span
                    class="rouble">Р</span></div>
          <?php } else { ?>
            <div class="cost-type"></div>
          <?php } ?>
        </div>


      </div>

    </div>

    <div class="the-map"></div>

      <a class="more_info" href="/poputchikOrder/details/id/<?= $model->id ?>">
          <?= $model->type == PpRoute::TYPE_PASSENGER ? Yii::t('poputchik', 'Связаться с пассажиром') : Yii::t('poputchik', 'Связаться с водителем') ?>
      </a>

  </div>

<?php

Yii::app()->clientScript->registerScript('ajust-city-name-font-size', "
$('.route-settlement').textfill(15);
", CClientScript::POS_READY);