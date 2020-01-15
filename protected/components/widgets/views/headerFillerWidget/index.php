<?php
switch (Yii::app()->controller->service_id) {
  case SERVICE_POPUTCHIK:
    print '<div class="phone_small">8 800 55 05 155<div class="text">' . Yii::t('main', "Телефон горячей линии") . '</div></div>';
    print '<div class="poput"><a href="/">' . Yii::t('poputchik', "Попутчик") . '</a></div>';
    break;
  case SERVICE_BURO_NAHODOK:
    print '<div class="phone_small">8 800 55 05 155<div class="text">' . Yii::t('main', "Телефон горячей линии") . '</div></div>';
    print '<div class="lffd"><a href="/">' . Yii::t('lostfound', "Бюро находок") . '</div>';
    break;
  case SERVICE_NOCHLEG:
    print '<div class="phone_small">8 800 55 05 155<div class="text">' . Yii::t('main', "Телефон горячей линии") . '</div></div>';
    print '<div class="hsfd"><a href="/">' . Yii::t('hotelservice', "Ночлег") . '</div>';
    break;
  case SERVICE_NEPOTERAYKA:
    print '<div class="phone_small">8 800 55 05 155<div class="text">' . Yii::t('main', "Телефон горячей линии") . '</div></div>';
    print '<div class="ltfd"><a href="/">' . Yii::t('hotelservice', "Непотеряйка") . '</div>';
    break;
  default:
    print '<div class="phone">8 800 55 05 155<div class="text">' . Yii::t('main', "Телефон горячей линии") . '</div></div>';
    break;
}
?>
<?= Yii::app()->controller->headerTotal ?>
