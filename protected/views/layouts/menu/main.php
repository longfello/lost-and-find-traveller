<nav class="navbar-default">
    <div class="container-fluid1">
        <button type="button" class="navbar-toggle collapsed header-btn" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">



<div id="mainmenu" class="mainmenu">
  <?php

  $all_services_menu = include('all-services-menu.php');

  switch (Yii::app()->params['subdomain']) {
    case SERVICE_POPUTCHIK:
      echo BsHtml::pills(array(
          $all_services_menu,
          array('label' => 'Попутчик по городу', 'url' => '/poputchik-po-gorodu', 'linkOptions' => array('title' => 'Попутчик по городу')),
          array('label' => 'Попутчик в другой город', 'url' => '/poputchik-v-gorod', 'linkOptions' => array('title' => 'Попутчик в другой город')),
          array('label' => 'О сервисе', 'url' => array('/o-servise'), 'linkOptions' => array('title' => 'О сервисе')),
      ), array('justified' => true));
      break;
    case SERVICE_BURO_NAHODOK:
      echo BsHtml::pills(array(
          $all_services_menu,
          array('label' => 'Потери', 'url' => '/poteri', 'linkOptions' => array('title' => 'Потери')),
          array('label' => 'Находки', 'url' => '/nahodki', 'linkOptions' => array('title' => 'Находки')),
          array('label' => 'Правовая информация', 'url' => '#', 'linkOptions' => array('title' => 'Правовая информация')),
          array('label' => 'Правила поведения', 'url' => '/pravila-polzovaniya-servisom', 'linkOptions' => array('title' => 'Правила поведения')),
      ), array('justified' => true));
      break;
    case SERVICE_NEPOTERAYKA:
      echo BsHtml::pills(array(
          $all_services_menu,
          array('label' => 'Непотеряйка', 'url' => array('/LostService/index'), 'linkOptions' => array('title' => 'Непотеряйка')),
          array('label' => 'Об услуге', 'url' => array('/LostService/info'), 'linkOptions' => array('title' => 'Об услуге')),
          array('label' => 'Получить ID', 'url' => array('/LostService/start'), 'linkOptions' => array('title' => 'Получить ID')),
          array('label' => 'Сообщить о находке', 'url' => array('/LostService/ifind'), 'linkOptions' => array('title' => 'Сообщить о находке')),
          array('label' => 'Купить набор', 'url' => '/#', 'linkOptions' => array('title' => 'Купить набор')),
      ), array('justified' => true));
      break;
    case SERVICE_NOCHLEG:
      echo BsHtml::pills(array(
          $all_services_menu,
          array('label' => 'Добавить объявление', 'url' => '/hotelService/create', 'linkOptions' => array('title' => 'Добавить объявление')),
      ), array('justified' => true));
      break;
    default:
      break;
  }
  ?>
</div><!-- mainmenu -->
        </div>
    </div>
</nav>
