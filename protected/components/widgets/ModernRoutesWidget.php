<?php

  class ModernRoutesWidget extends CWidget {
    const SLUG_CACHE_MOST_POPULAR = 'cache_slug_paths_most_popular';
    const SLUG_CACHE_FROM_SUFFIX  = 'cache_slug_paths_from_suffix_';
    const SLUG_CACHE_TO_SUFFIX    = 'cache_slug_paths_to_suffix_';
    const SLUG_CACHE_FROM_NEARLY_SUFFIX  = 'cache_slug_paths_from_nearly_suffix_';
    const SLUG_CACHE_TO_NEARLY_SUFFIX    = 'cache_slug_paths_to_nearly_suffix_';

    const MIN_NEARLY_POPULATION      = 10000;
    const MIN_NEARLY_DISTANCE = 150;

    public $layout = 'index';
    /* @var PoputchikForm */
    public $form;

    public function run() {
//      Yii:: app () ->cache->flush();

      if ($this->form->typeRoute == PpRoute::TYPE_ROUTE_ANOTHER) {
        $cols = array();

        if ($this->form->cityFrom && $this->form->cityTo) {
          // Оба города выбраны
          if ($data = $this->get_from_routes())           $cols[] = $data;
          if ($data = $this->get_to_routes())             $cols[] = $data;
        } else {
          if ($this->form->cityFrom) {
            // только ОТКУДА выбран
            if ($data = $this->get_from_routes())         $cols[] = $data;
            if ($data = $this->get_routes_from_nearly())  $cols[] = $data;
          }
          if ($this->form->cityTo) {
            // только КУДА выбран
            if ($data = $this->get_to_routes())           $cols[] = $data;
            if ($data = $this->get_routes_to_nearly())    $cols[] = $data;
          }
        }

        if ($data = $this->get_popular_routes())          $cols[] = $data;

/*        echo('<div class="clearfix"></div><pre>');
        print_r($cols);

        echo('</pre><div class="clearfix"></div>');
*/
        echo $this->renderFile(Yii::getPathOfAlias('application.components.widgets.views.ModernRoutesWidget').'/'.$this->layout.'.php', array(
            'cols' => $cols
        ), TRUE);
      }
    }

    public function get_to_routes(){
      $cache_slug = self::SLUG_CACHE_TO_SUFFIX.$this->form->getCityTo()->id;
      $result = Yii::app()->cache->get($cache_slug);

      if ($result === false) {
        $query = "
SELECT from_id, to_id
FROM pp_route ppr
WHERE to_id = {$this->form->getCityTo()->id}
GROUP BY from_id, to_id
ORDER BY count(id) DESC
LIMIT 5";
        $paths = Yii::app()->db->getCommandBuilder()->createSqlCommand($query)->queryAll();

        $result = array();
        foreach($paths as $path) {
          $from = Geoname::model()->cache(60)->findByPk($path['from_id']);
          $to   = Geoname::model()->cache(60)->findByPk($path['to_id']);

          $name = $from->name.' &rarr; '.$to->name;
          $url  = $this->controller->createAbsoluteUrl("/{$this->form->typeRoute}/{$from->slug}/{$to->slug}/{$this->form->type}");

          $result[$url] = $name;
        }
        Yii::app()->cache->set($cache_slug, $result, 300);
      }

      return $result?array(
          'name' => Yii::t('poputchik','Маршруты в ').$this->form->cityTo->name,
          'data' => $result
      ):false;
    }

    public function get_from_routes(){
      $cache_slug = self::SLUG_CACHE_FROM_SUFFIX.$this->form->getCityFrom()->id;
      $result = Yii::app()->cache->get($cache_slug);

      if ($result === false) {
        $query = "
SELECT from_id, to_id
FROM pp_route ppr
WHERE from_id = {$this->form->getCityFrom()->id}
GROUP BY from_id, to_id
ORDER BY count(id) DESC
LIMIT 5";

        $paths = Yii::app()->db->getCommandBuilder()->createSqlCommand($query)->queryAll();

        $result = array();
        foreach($paths as $path) {
          $from = Geoname::model()->cache(60)->findByPk($path['from_id']);
          $to   = Geoname::model()->cache(60)->findByPk($path['to_id']);

          $name = $from->name.' &rarr; '.$to->name;
          $url  = $this->controller->createAbsoluteUrl("/{$this->form->typeRoute}/{$from->slug}/{$to->slug}/{$this->form->type}");

          $result[$url] = $name;
        }
        Yii::app()->cache->set($cache_slug, $result, 300);
      }

      return $result?array(
          'name' => Yii::t('poputchik','Маршруты из ').$this->form->cityFrom->name,
          'data' => $result
      ):false;
    }

    public function get_popular_routes(){
      $result = Yii::app()->cache->get(self::SLUG_CACHE_MOST_POPULAR);

      if ($result === false) {
        $query = "
SELECT from_id, to_id
FROM pp_route ppr
GROUP BY from_id, to_id
ORDER BY count(id) DESC
LIMIT 5";
        $paths = Yii::app()->db->getCommandBuilder()->createSqlCommand($query)->queryAll();

        $result = array();
        foreach($paths as $path) {
          $from = Geoname::model()->cache(60)->findByPk($path['from_id']);
          $to   = Geoname::model()->cache(60)->findByPk($path['to_id']);

          $name = $from->name.' &rarr; '.$to->name;
          $url  = $this->controller->createAbsoluteUrl("/{$this->form->typeRoute}/{$from->slug}/{$to->slug}/{$this->form->type}");

          $result[$url] = $name;
        }
        Yii::app()->cache->set(self::SLUG_CACHE_MOST_POPULAR, $result, 300);
      }

      return $result?array(
          'name' => Yii::t('poputchik','Популярные маршруты'),
          'data' => $result
      ):false;
    }

    public function get_routes_from_nearly(){
      $cache_slug = self::SLUG_CACHE_FROM_NEARLY_SUFFIX.$this->form->getCityFrom()->id;
      $result = Yii::app()->cache->get($cache_slug);

      if ($result === false) {
        $query = "
SELECT from_id, to_id
FROM pp_route ppr
WHERE from_id IN (
	SELECT id
	FROM geoname
	WHERE st_distance(POINT({$this->form->getCityFrom()->longitude}, {$this->form->getCityFrom()->latitude}), coord) < ".(self::MIN_NEARLY_DISTANCE/111)."
	  AND population > ".(self::MIN_NEARLY_POPULATION)."
	  AND id <> {$this->form->getCityFrom()->id}
	ORDER BY population DESC
)
GROUP BY from_id, to_id
ORDER BY count(id) DESC
LIMIT 5";

        $paths = Yii::app()->db->getCommandBuilder()->createSqlCommand($query)->queryAll();

        $result = array();
        foreach($paths as $path) {
          $from = Geoname::model()->cache(60)->findByPk($path['from_id']);
          $to   = Geoname::model()->cache(60)->findByPk($path['to_id']);

          $name = $from->name.' &rarr; '.$to->name;
          $url  = $this->controller->createAbsoluteUrl("/{$this->form->typeRoute}/{$from->slug}/{$to->slug}/{$this->form->type}");

          $result[$url] = $name;
        }
        Yii::app()->cache->set($cache_slug, $result, 300);
      }

      return $result?array(
          'name' => Yii::t('poputchik','Маршруты из городов рядом'),
          'data' => $result
      ):false;
    }
    public function get_routes_to_nearly(){
      $cache_slug = self::SLUG_CACHE_TO_NEARLY_SUFFIX.$this->form->getCityTo()->id;
      $result = Yii::app()->cache->get($cache_slug);

      if ($result === false) {
        $query = "
SELECT from_id, to_id
FROM pp_route ppr
WHERE to_id IN (
	SELECT id
	FROM geoname
	WHERE st_distance(POINT({$this->form->getCityTo()->longitude}, {$this->form->getCityTo()->latitude}), coord) < ".(self::MIN_NEARLY_DISTANCE/111)."
	  AND population > ".(self::MIN_NEARLY_POPULATION)."
	  AND id <> {$this->form->getCityTo()->id}
	ORDER BY population DESC
)
GROUP BY from_id, to_id
ORDER BY count(id) DESC
LIMIT 5";

        $paths = Yii::app()->db->getCommandBuilder()->createSqlCommand($query)->queryAll();

        $result = array();
        foreach($paths as $path) {
          $from = Geoname::model()->cache(60)->findByPk($path['from_id']);
          $to   = Geoname::model()->cache(60)->findByPk($path['to_id']);

          $name = $from->name.' &rarr; '.$to->name;
          $url  = $this->controller->createAbsoluteUrl("/{$this->form->typeRoute}/{$from->slug}/{$to->slug}/{$this->form->type}");

          $result[$url] = $name;
        }
        Yii::app()->cache->set($cache_slug, $result, 300);
      }

      return $result?array(
          'name' => Yii::t('poputchik','Маршруты в города рядом'),
          'data' => $result
      ):false;
    }
  }
