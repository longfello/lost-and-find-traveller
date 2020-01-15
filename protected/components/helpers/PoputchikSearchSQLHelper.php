<?php

/**
 * Class PoputchikSearchSQLHelper
 */
class PoputchikSearchSQLHelper
{
  /**
   * Поиск водителя
   */
  const ORDER_DRIVER    = 1;
  /**
   * Поиск пассажира
   */
  const ORDER_PASSENGER = 2;
  /**
   * Поиск любого
   */
  const ORDER_ANY       = 3;

  /**
   * Поиск по городу
   */
  const ROUTE_SAME      = 1;
  /**
   * Поиск по межгороду
   */
  const ROUTE_ANOTHER   = 2;

  /**
   * id заказа - не уверен, что используется на данный момент
   * @var int
   */
  var $id               = 0;
  /**
   * город поиска согласно URL
   * @var null
   */
  var $city             = null;
  /**
   * Критерий поиска - поиск водителя/пассажира/любого
   * @var self::ORDER_ANY|self::ORDER_DRIVER|self::ORDER_PASSENGER
   */
  var $order            = self::ORDER_DRIVER;
  /**
   * Критерий поиска - поиск по городу / по межгороду
   * @var self::ROUTE_SAME|self::ROUTE_ANOTHER
   */
  var $route            = self::ROUTE_ANOTHER;
  /**
   * Критерий поиска - Дата (timestamp)
   * @var int
   */
  var $when             = 0;
  /**
   * Место отправления
   * @var search_location
   */
  var $from;
  /**
   * Место прибытия
   * @var search_location
   */
  var $to;
  /**
   * Использовалось ранее - на данный момент константно 1
   * @var int
   */
  var $isSearch = 1;
  /**
   * Заголовок
   * @var string
   */
  var $title;
  /**
   * Количество карточек на странице
   * @var int
   */
  public $onPage = 15;

  /**
   * Инициализация параметров поиска
   */
  public function getParameters($city = null){
    $this->title = Yii::t('poputchik', 'Сервис Попутчик');
    $this->id   = Core::GetGet('id_order', 0);
    // псевдоним города из URL
    $this->city = $city;

    // получение параметров из GET / POST / SESSION -- в таком порядке
    $this->order = Core::getPost('type_order', Core::getGet('type_order', Yii::app()->session->get('search_filter_type_order', self::ORDER_DRIVER)));
    $this->route = Core::getPost('type_route', Core::getGet('type_route', Yii::app()->session->get('search_filter_type_route', self::ROUTE_ANOTHER)));

    $this->when  = Core::getPost('date_from', Core::getGet('date_from', 0));
    if (!($this->when)) {
      $this->when = Yii::app()->session->get('search_filter_when', '');
    } else {
      $this->when = strtotime($this->when);
    }
    $this->when = $this->when?$this->when:0;

    $this->from = new search_location(search_location::FROM, $this->city);
    $this->to   = new search_location(search_location::TO, $this->city);

    // Проверка на нажатие кнопки сброса и соответственно сброс
    if (Core::getPost('paction', false) == 'reset') {
      Yii::app()->session['clearFilter'] = 'yes';
      $this->reset(false, true);
      // Возвращаем URL куда переходить
      echo(json_encode(array(
          'href' => '/poputchik-po-gorodu'
      )));
      Core::yiiTerminate();
    }

    // корректировка URL в зависимости от типа запроса и наличия города
    $this->correctRequestURI();

    // Проверка на принудительный отказ от сброса формы (по странной логике)
    if (Yii::app()->session->get('clearFilter') != 'no') {
      // Проверка на предмет перехода по ссылке из шапки и сброс параметров фильтра в данном случае
      if (!$this->checkDataSource()) {
        $this->reset();
      }
    }

    // сохранение текущего состояния фильтра
    $this->saveState();

    return $this;
  }

  /**
   * корректировка URL в зависимости от типа запроса и наличия города
   */
  function correctRequestURI(){
    // корректировка первой части URL -- poputchik-po-gorodu VS poputchik-v-gorod
    $path  = Yii::app()->request->pathInfo;

    $path  = explode('/', $path);
    $route = isset($path[0])?$path[0]:'';
    if (!$route) {
      $this->reset(false, true);
      Yii::app()->controller->redirect('/poputchik-po-gorodu/');
      Core::yiiTerminate();
    }
    switch($this->route) {
      case self::ROUTE_SAME:
        // переадресация на СЕО-страницу -- новые данные введены в фильтр + смена типа фильтра
        if ($route != 'poputchik-po-gorodu' || $this->city != $this->from->slug) {
          Yii::app()->session['clearFilter'] = 'no';
          $this->saveState();
          Yii::app()->controller->redirect('/poputchik-po-gorodu/'.$this->from->slug);
          Core::yiiTerminate();
        }
        break;
      case self::ROUTE_ANOTHER:
        if ($route != 'poputchik-v-gorod') {
          Yii::app()->session['clearFilter'] = 'no';
          $this->saveState();
          Yii::app()->controller->redirect('/poputchik-v-gorod');
          Core::yiiTerminate();
        }
        break;
    }

    // Проверка на СЕО-страницу
    if ($this->route == self::ROUTE_SAME) {
      if ($this->from->slug) {
        $seoCity = SeoCity::model()->findBySlug($this->from->slug);
        if (!$seoCity){
          // Новая запись в таблице seoCity
          $seoCity = new SeoCity;
          $seoCity->city_id = $this->from->id;
          $seoCity->url = $this->from->slug;
          $seoCity->save();

          // переадресация на СЕО-страницу -- новая запись
          $this->saveState();
          Yii::app()->session['clearFilter'] = 'no';
          Yii::app()->controller->redirect('/poputchik-po-gorodu/' . $this->from->slug);
        }
        if ($this->city != $this->from->slug) {
          // переадресация на СЕО-страницу -- новые данные введены в фильтр
          $this->saveState();
          Yii::app()->session['clearFilter'] = 'no';
          Yii::app()->controller->redirect('/poputchik-po-gorodu/' . $this->from->slug);
        }
      }
    }

  }

  /**
   * сохранение текущего состояния фильтра в сессии
   */
  function saveState(){
    Yii::app()->session['search_filter_when']       = $this->when;
    Yii::app()->session['search_filter_type_route'] = $this->route;
    Yii::app()->session['search_filter_type_order'] = $this->order;
  }

  /**
   * Сброс параметров поиска
   */
  function reset($reGetParam = true, $force = false){
    if (!Yii::app()->request->isPostRequest || $force) {
      if (Yii::app()->session->get('clearFilter') != 'no') {
        $this->from->reset();
        $this->to->reset();
        Yii::app()->session->remove('search_filter_type_route');
        Yii::app()->session->remove('search_filter_type_order');
        Yii::app()->session->remove('search_filter_when');
        $default = new self;

        // применение значений по-умолчанию
        $this->id               = $default->id;
        $this->city             = $default->city;
        $this->order            = self::ORDER_ANY;
        $this->when             = $default->when;

        // Если требуется повторный разбор параметров фильтра
        if ($reGetParam) {
          $this->getParameters($this->city);
        }
      }
    }
  }

  /**
   * Проверяет, текущая ли форма отправила данные или другая
   */
  private function checkDataSource(){
    $current = true;
    $type  = $this->order."x".$this->route;
    if ($type != Yii::app()->session->get('search_filter_type', null)) {
      Yii::app()->session->remove('search_filter_type_order');
      Yii::app()->session->remove('search_filter_type_route');
      $current = false;
    }

    Yii::app()->session['search_filter_type'] = $type;
    return $current;
  }

  /**
   * Установка заголовка страницы
   */
  private function setTitle(){
    if ($this->route == self::ROUTE_ANOTHER) $this->title = Yii::t('poputchik', 'Поиск попутчиков в другой город');
      if ($this->route == self::ROUTE_SAME)    $this->title = Yii::t('poputchik', 'Поиск попутчиков по городу');
  }

  /**
   * Поиск согласно текущих параметров
   * Возвращает id_order таблицы poputchik_order
   * @return int[]
   */
  public function search(){
    $where_clause = $where_params = $join = $wheres_date = $wheres_date_reverse = array();

    if ($this->isSearch) {
      $this->setTitle();

      $query = 'SELECT DISTINCT po.id_order FROM poputchik_order po';
      $where_clause[] = 'po.status = 1';
//      $where_clause[] = 'po.date_available >= "' . date('Y-m-d') . '"';
      $where_clause[] = 'po.type_route = :type_route';

      $where_params[':type_route'] = $this->route;

      if (in_array($this->order, array(self::ORDER_DRIVER, self::ORDER_PASSENGER))) {
        $where_clause[]              = 'po.type_order = :type_order';
        $where_params[':type_order'] = $this->order;
      }

      // Если задана дата
      if ($this->when) {
        $wheres_date[] = 'po.date_from = :date';
        $wheres_date_reverse[] = 'po.date_reverse = :date';

        $dow = date('N', $this->when) - 1;
        $join[] = 'LEFT JOIN days_of_week dow ON po.id_order = dow.id_order';
        $wheres_date[] = 'dow.day= :dow';
        $wheres_date_reverse[] = 'dow.day_reverse= :dow';

        $dom = (int)date('d', $this->when);
        $join[] = 'LEFT JOIN days_of_month dom ON po.id_order = dom.id_order';
        $wheres_date[] = 'dom.day= :dom';
        $dim = (int)date('m', $this->when) - 1;
        if ($dim < 1) $dim = 12;
        $dim = (int)date('t', strtotime("2014-$dim-1"));
        $wheres_date_reverse[] = "dom.day_reverse_$dim= :dom";

        $this->where_date = '(' . implode(' OR ', $wheres_date) . ')';
        $this->where_date_reverse = '(' . implode(' OR ', $wheres_date_reverse) . ')';

        $where_params[':date'] = date('Y-m-d', $this->when?$this->when:time());
        $where_params[':dow'] = $dow;
        $where_params[':dom'] = $dom;
      }

      // Добавление параметров в зависимости от типа поиска (по городу / по межгороду)
      switch ($this->route) {
        case self::ROUTE_SAME:
          if ($this->from->id) {
            if ($this->when) $where_clause[] = "(po.id_settlement IN (:idss) AND ({$this->where_date} OR {$this->where_date_reverse}))";
                        else $where_clause[] = "po.id_settlement IN (:idss)";
            $where_params[':idss'] = $this->from->id;
          } else if ($this->when) $where_clause[] = "({$this->where_date} OR {$this->where_date_reverse})";
          break;
        case self::ROUTE_ANOTHER:
          // Если указан город/города - добавляем в выборку соответствующие таблицы
          if ($this->from->id || $this->to->id) {
            $join[] = 'LEFT JOIN paths p ON po.id_path = p.id_path';
            $join[] = 'LEFT JOIN routes r ON po.id_route = r.id_route';
            $join[] = 'LEFT JOIN route_settlements rs ON po.id_route = rs.id_route';
            $join[] = 'LEFT JOIN poputchik_order_settlements pos ON po.id_order = pos.id_order';
          }
          // Если указан город - источник
          if ($this->from->id) {
            $wheres_route = array();
            //path
            $where = '((po.direction = 0 AND p.id_settlement_1 IN (:idss)) OR (po.direction = 1 AND p.id_settlement_2 IN (:idss)))';
            if ($this->when) $where = '(' . $where . " AND {$this->where_date})";
            $wheres_route[] = $where;
            $where = '((po.reverse = 1 AND po.direction = 1 AND p.id_settlement_1 IN (:idss)) OR (po.reverse = 1 AND po.direction = 0 AND p.id_settlement_2 IN (:idss)))';
            if ($this->when) $where = '(' . $where . " AND {$this->where_date_reverse})";
            $wheres_route[] = $where;
            //route
            $where = '((po.direction = 0 AND r.start_settlement IN (:idss)) OR (po.direction = 1 AND r.end_settlement IN (:idss)))';
            if ($this->when) $where = '(' . $where . " AND {$this->where_date})";
            $wheres_route[] = $where;
            $where = '((po.reverse = 1 AND po.direction = 1 AND r.start_settlement IN (:idss)) OR (po.reverse = 1 AND po.direction = 0 AND r.end_settlement IN (:idss)))';
            if ($this->when) $where = '(' . $where . " AND {$this->where_date_reverse})";
            $wheres_route[] = $where;
            //route_settlements
            if (!$this->to->id) {
              if ($this->when) $wheres_route[] = "(rs.id_settlement IN (:idss) AND ({$this->where_date} OR {$this->where_date_reverse}))";
              else  $wheres_route[] = "rs.id_settlement IN (:idss)";
            }
            //po_settlements
            if ($this->when) $wheres_route[] = "(pos.id_settlement IN (:idss) AND ({$this->where_date} OR {$this->where_date_reverse}))";
            else  $wheres_route[] = "pos.id_settlement IN (:idss)";
            $where_idss = '(' . implode(' OR ', $wheres_route) . ')';
            $where_params[':idss'] = $this->from->id;
          }
          // если указан город назначения
          if ($this->to->id) {
            $wheres_route = array();
            //path
            $where = '((po.direction = 1 AND p.id_settlement_1 IN (:ides)) OR (po.direction = 0 AND p.id_settlement_2 IN (:ides)))';
            if ($this->when) $where = '(' . $where . " AND {$this->where_date})";
            $wheres_route[] = $where;
            $where = '((po.reverse = 1 AND po.direction = 0 AND p.id_settlement_1 IN (:ides)) OR (po.reverse = 1 AND po.direction = 1 AND p.id_settlement_2 IN (:ides)))';
            if ($this->when) $where = '(' . $where . " AND {$this->where_date_reverse})";
            $wheres_route[] = $where;
            //route
            $where = '((po.direction = 1 AND r.start_settlement IN (:ides)) OR (po.direction = 0 AND r.end_settlement IN (:ides)))';
            if ($this->when) $where = '(' . $where . " AND {$this->where_date})";
            $wheres_route[] = $where;
            $where = '((po.reverse = 1 AND po.direction = 0 AND r.start_settlement IN (:ides)) OR (po.reverse = 1 AND po.direction = 1 AND r.end_settlement IN (:ides)))';
            if ($this->when) $where = '(' . $where . " AND {$this->where_date_reverse})";
            $wheres_route[] = $where;
            //route_settlements
            if (!$this->from->id) {
              if ($this->when) $wheres_route[] = "(rs.id_settlement IN (:ides) AND ({$this->where_date} OR {$this->where_date_reverse}))";
              else  $wheres_route[] = "rs.id_settlement IN (:ides)";
            }
            //po_settlements
            if ($this->when) $wheres_route[] = "(pos.id_settlement IN (:ides) AND ({$this->where_date} OR {$this->where_date_reverse}))";
            else  $wheres_route[] = "pos.id_settlement IN (:ides)";
            $where_ides = '(' . implode(' OR ', $wheres_route) . ')';
            $where_params[':ides'] = $this->to->id;
          }
          // если указаны оба города
          if ($this->from->id && $this->to->id) {
            $join[] = 'LEFT JOIN route_paths rp ON r.id_route = rp.id_route';
            $join[] = 'LEFT JOIN paths pr ON rp.id_path = pr.id_path';
            $wheres_route = array();
            //route_pathes
            $where = '((rp.direction = 0 AND pr.id_settlement_1 IN (:idss) AND pr.id_settlement_2 IN (:ides))
										OR (rp.direction = 1 AND pr.id_settlement_2 IN (:idss) AND pr.id_settlement_1 IN (:ides)))';
            if ($this->when) $where = '(' . $where . " AND {$this->where_date})";
            $wheres_route[] = $where;
            $where = '((po.reverse = 1 AND rp.direction = 1 AND pr.id_settlement_1 IN (:idss) AND pr.id_settlement_2 IN (:ides))
										OR (po.reverse = 1 AND rp.direction = 0 AND pr.id_settlement_2 IN (:idss) AND pr.id_settlement_1 IN (:ides)))';
            if ($this->when) $where = '(' . $where . " AND {$this->where_date_reverse})";
            $wheres_route[] = $where;
            $where_clause[] = '(' . implode(' OR ', $wheres_route) . " OR ($where_idss AND $where_ides))";
          } else {
            if ($this->from->id) $where_clause[] = $where_idss;
            if ($this->to->id)   $where_clause[] = $where_ides;
          }
          if (!$this->from->id && !$this->to->id && $this->when) $where_clause[] = "({$this->where_date} OR {$this->where_date_reverse})";
          break;
      }

      // компоновка запроса
      $query .= ' ' . implode(' ', $join) . ' WHERE ' . implode(' AND ', $where_clause) . ' ORDER BY date_available<now(), date_from';
      $command = Yii::app()->db->createCommand($query);
      $command->params = $where_params;

    } else {
      // согласно текущему положению вещей - данный участок кода не выполняется никогда
      $id_order = ($this->id)?"id_order = {$this->id} AND ": '';
      // $this->query = "SELECT id_order FROM poputchik_order WHERE $id_order status = 1 AND type_route = {$this->route} AND date_available >= \"" . date('Y-m-d') . '" ORDER BY date_from ';
      $query = "SELECT id_order FROM poputchik_order WHERE $id_order status = 1 AND type_route = {$this->route} ORDER BY date_available<now(), date_from";
      $command = Yii::app()->db->createCommand($query);
    }

    $orders = $command->queryAll();
    $orders_ids = array();
    foreach ($orders as $order) $orders_ids[] = $order['id_order'];
    return $orders_ids;
  }


  /**
   * Возвращает данные, подготовленные для view
   * @return array
   */
  public function getData(){

    Yii::app()->session['clearFilter'] = 'yes';
    $orders_ids = $this->search();

    $count_orders = count($orders_ids);
    if ($count_orders > $this->onPage) {
      $pages = (int)(($count_orders + ($this->onPage - 1)) / $this->onPage);
      $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
      $orders_ids = array_slice($orders_ids, $this->onPage * ($current_page - 1), $this->onPage);
    }

    $orders_ids = implode(', ', $orders_ids);
    if ($orders_ids) {
      $orders = PoputchikOrder::model()->findAll(array('condition' => "id_order IN ($orders_ids)", 'order' => 'date_from', 'order' => 'date_available<now(), date_from'));

    } else $orders = array();

    return array(
      'orders' => $orders,
      'count_orders' => $count_orders,
      'search' => $this->isSearch,
      'pages' => isset($pages) ? $pages : NULL,
      'title' => $this->title,
      'search_info' => $this
    );
  }
}


/**
 * Локации поиска
 * Class search_location
 */
class search_location {
  /**
   * место отправления
   */
  const FROM = 1;
  /**
   * место прибытия
   */
  const TO   = 2;

  /**
   * псевдоним сессии
   */
  const SESSION_SLUG_ID        = 'search_location_id';
  /**
   * псевдоним сессии
   */
  const SESSION_SLUG_NAME      = 'search_location_name';
  /**
   * псевдоним сессии
   */
  const SESSION_SLUG_FULL_NAME = 'search_location_full_name';

  /**
   * Направление движения (место отправления или место прибытия)
   * @var search_location::FROM|search_location::TO
   */
  var $type = self::FROM;

  /**
   * id локации
   * @var int|null
   */
  var $id;
  /**
   * Название локации
   * @var string
   */
  var $name;
  /**
   * Полное название локации
   * @var string
   */
  var $full_name;
  /**
   * Псевдоним локации (часть в URL)
   * @var string
   */
  var $slug = null;

  /**
   * @param int $type
   * @param string $custom_city_slug
   */
  public function __construct($type = search_location::FROM, $custom_city_slug){
    $this->type = $type;
    $this->getParameters($custom_city_slug);
  }

  /**
   * Получение параметров локации
   * @param string|bool $custom_city_slug
   * @throws Exception
   */
  private function getParameters($custom_city_slug = false){
    /*
    if ($this->type == self::FROM) {
      $model = SeoCity::model()->findBySlug($custom_city_slug);
      print_r(Core::toArray($model->settlements));
    }
    */

    $source_request = true;
    switch ($this->type) {
      case self::FROM:
        $this->id = null;
        // Если передано непосредственно из фильтра по имени
        $is_name        = !isset($_POST['settlement_name_g']) || $_POST['settlement_name_g'];
        if ($is_name) {
          $this->id       = Core::getPost('start_settlement', Core::getGet('start_settlement', false));
          // Если НЕ передано непосредственно из фильтра по id - получение из сессии
          if (!$this->id) {
            $this->id       = Yii::app()->session->get(self::SESSION_SLUG_ID.$this->type, false);
            if ($this->id) {
              // прежнее состояние не было сохранено - пробывать получить локацию из URL
              $source_request = false;
            }
          }
        }
        if ($this->id) {
          $info = Settlements::model()->findByPk($this->id);
          /* @var $info Settlements */
          $this->name = $this->full_name = $info->name;
          $this->slug = SeoCity::generateSlug($this->id, $this->name);
        } else {
          $this->name = $this->full_name = '';
          $this->id = null;
        }
        break;
      case self::TO:
        $this->id = null;
        // Если передано непосредственно из фильтра по имени
        $is_name        = !isset($_POST['settlement_name_2']) || $_POST['settlement_name_2'];
        if ($is_name){
          $this->id       = Core::getPost('end_settlement', Core::getGet('end_settlement', false));
          // Если НЕ передано непосредственно из фильтра по id - получение из сессии
          if (!$this->id) {
            $this->id       = Yii::app()->session->get(self::SESSION_SLUG_ID.$this->type, false);
          }
        }
        if ($this->id) {
          $info = Settlements::model()->findByPk($this->id);
          /* @var $info Settlements */
          $this->name = $this->full_name = $info->name;
        } else {
          $this->name = $this->full_name = '';
          $this->id = null;
        }
        break;
      default:
        throw new Exception('Unknown location type');
    }

    if ($this->type == self::FROM && (!$source_request || !$this->id) && $custom_city_slug && (!isset($_POST['settlement_name_g']) || $_POST['settlement_name_g'])) {
      // Если локация не найдена и город задан в URL
      $seo = SeoCity::model()->findBySlug($custom_city_slug);
      if ($seo) {
        /* @var $seo SeoCity */
        $this->id   = $seo->city_id;
        $this->name = $this->full_name = $seo->settlements->name;
        $this->slug = $seo->url;
      }
    }

    // Сохранение локации фильтра в сессии
    Yii::app()->session[self::SESSION_SLUG_ID.$this->type]        = $this->id;
    Yii::app()->session[self::SESSION_SLUG_NAME.$this->type]      = $this->name;
    Yii::app()->session[self::SESSION_SLUG_FULL_NAME.$this->type] = $this->full_name;
  }

  /**
   * Сброс сохраненной локации и повторная интерпретация параметров фильтра
   */
  public function reset(){
    Yii::app()->session->remove(self::SESSION_SLUG_ID.$this->type);
    Yii::app()->session->remove(self::SESSION_SLUG_NAME.$this->type);
    Yii::app()->session->remove(self::SESSION_SLUG_FULL_NAME.$this->type);
    $this->getParameters();
  }
}