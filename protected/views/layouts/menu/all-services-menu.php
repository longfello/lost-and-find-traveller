<?php

  return '
<li>
  <a class="arrow_box" href="http://infotoway.kvk-dev.pp.ua/">'.Yii::t('poputchik', 'Все сервисы').'</a>
  <div class="sub-menu-wrapper">
    <div class="smw-inner">
      <ul class="sub-menu">
        <li>
          <a class="arrow_box" href="//'.SERVICE_POPUTCHIK.'.'.Yii::app()->params['domain'].'/">'.Yii::t('poputchik', 'Попутчик').'</a>
          <div class="sub-menu-wrapper">
            <div class="smw-inner">
              <ul class="sub-menu">
                <li><a href="//'.SERVICE_POPUTCHIK.'.'.Yii::app()->params['domain'].'/'.PpRoute::TYPE_ROUTE_SAME.'">'.Yii::t('poputchik', 'По городу').'</a></li>
                <li><a href="//'.SERVICE_POPUTCHIK.'.'.Yii::app()->params['domain'].'/'.PpRoute::TYPE_ROUTE_ANOTHER.'">'.Yii::t('poputchik', 'Межгород').'</a></li>
                <li><a href="//'.SERVICE_POPUTCHIK.'.'.Yii::app()->params['domain'].'/poputchikOrder/addAdvert/type/passenger">'.Yii::t('poputchik', 'Подать заявку пассажира').'</a></li>
                <li><a href="//'.SERVICE_POPUTCHIK.'.'.Yii::app()->params['domain'].'/poputchikOrder/addAdvert/type/driver">'.Yii::t('poputchik', 'Подать заявку водителя').'</a></li>
              </ul>
            </div>
          </div>
        </li>
        <li>
          <a class="arrow_box" href="//'.SERVICE_NEPOTERAYKA.'.'.Yii::app()->params['domain'].'/">'.Yii::t('nepoterayka', 'Непотеряйка').'</a>
          <div class="sub-menu-wrapper">
            <div class="smw-inner">
              <ul class="sub-menu">
                <li><a href="//'.SERVICE_NEPOTERAYKA.'.'.Yii::app()->params['domain'].'/LostService/start">'.Yii::t('nepoterayka', 'Получить ID').'</a></li>
                <li><a href="//'.SERVICE_NEPOTERAYKA.'.'.Yii::app()->params['domain'].'/LostService/ifind">'.Yii::t('nepoterayka', 'Сообщить о находке').'</a></li>
              </ul>
            </div>
          </div>
        </li>
        <li>
          <a class="arrow_box" href="//'.SERVICE_BURO_NAHODOK.'.'.Yii::app()->params['domain'].'">'.Yii::t('buronahodok', 'Бюро находок').'</a>
          <div class="sub-menu-wrapper">
            <div class="smw-inner">
              <ul class="sub-menu">
                <li><a href="//'.SERVICE_BURO_NAHODOK.'.'.Yii::app()->params['domain'].'/poteri">'.Yii::t('buronahodok', 'Потери').'</a></li>
                <li><a href="//'.SERVICE_BURO_NAHODOK.'.'.Yii::app()->params['domain'].'/nahodki">'.Yii::t('buronahodok', 'Находки').'</a></li>
              </ul>
            </div>
          </div>
        </li>
        <li>
          <a href="//'.SERVICE_NOCHLEG.'.'.Yii::app()->params['domain'].'">'.Yii::t('nochleg', 'Ночлег').'</a>
        </li>
      </ul>
    </div>
  </div>
</li>';
