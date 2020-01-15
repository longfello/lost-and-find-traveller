<div id="menu_wrapper">
  <?php

  switch (Yii::app()->params['subdomain']) {
    case SERVICE_POPUTCHIK:
      echo BsHtml::pills(array(
        //array('label' => 'О компании ', 'url' => array('/about')),
          array('label' => 'О сервисе', 'url' => array('/o-servise'), 'linkOptions' => array('title' => 'О сервисе')),
        //array('label' => 'Пользовательское соглашение', 'url' => array('/terms')),
          array('label' => 'Правила поведения', 'url' => array('/rules'), 'linkOptions' => array('title' => 'Правила поведения')),
      ), array('justified' => true));


      echo BsHtml::pills(array(
        //array('label' => 'Полезные советы', 'url' => array('/poleznye-sovety')),
          array('label' => 'Часто задаваемые вопросы', 'url' => array('/faq'), 'linkOptions' => array('title' => 'Часто задаваемые вопросы')),
          array('label' => 'Свяжитесь с нами', 'url' => array('/feedback'), 'linkOptions' => array('title' => 'Свяжитесь с нами')),
      ), array('justified' => true));

      break;
    case SERVICE_BURO_NAHODOK:
      echo BsHtml::pills(array(
          array('label' => 'Cтатьи и публикации', 'url' => array('/stati-i-publikatsii'), 'linkOptions' => array('title' => 'Cтатьи и публикации')),
          array('label' => 'Полезные советы и инструкции', 'url' => array('/soveti'), 'linkOptions' => array('title' => 'Полезные советы и инструкции')),
          array('label' => 'Другие ресурсы', 'url' => array('/other'), 'linkOptions' => array('title' => 'Другие ресурсы')),
      ), array('justified' => true));

      echo BsHtml::pills(array(
          array('label' => 'Внимание мошенники!', 'url' => '/vnimanie-moshenniki', 'linkOptions' => array('title' => 'Внимание мошенники!')),
          array('label' => 'Свяжитесь с нами', 'url' => array('/feedback'), 'linkOptions' => array('title' => 'Свяжитесь с нами')),
          array('label' => 'О сервисе', 'url' => array('/o-servise'), 'linkOptions' => array('title' => 'О сервисе')),
      ), array('justified' => true));

      break;
    case SERVICE_NEPOTERAYKA:
      echo BsHtml::pills(array(
          array('label' => 'Часто задаваемые вопросы', 'url' => array('/LostService/faq'), 'linkOptions' => array('title' => 'Часто задаваемые вопросы')),
          array('label' => 'Как это работает?', 'url' => array('/LostService/how_its_work'), 'linkOptions' => array('title' => 'Как это работает')),
      ), array('justified' => true));
      break;
    case SERVICE_NOCHLEG:

      break;
    default:

      break;
  }
  ?>
</div>