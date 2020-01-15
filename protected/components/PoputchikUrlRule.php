<?php

class PoputchikUrlRule extends CBaseUrlRule {
  public $connectionID = 'db';
  public $reservedKeywords = array(
    'driver'    => array('params' => false, 'name' => 'type'),
    'passenger' => array('params' => false, 'name' => 'type'),
    'from'      => array('params' => true),
    'to'        => array('params' => true),
    'date'      => array('params' => true),
    'class'     => array('params' => true),
    'seats'     => array('params' => true),
    'page'      => array('params' => true),
  );
  public $typeRoute = array(
      PpRoute::TYPE_ROUTE_SAME,
      PpRoute::TYPE_ROUTE_ANOTHER
  );

  public function createUrl($manager, $route, $params, $ampersand)
  {
    /*
    if ($route === 'page/view') {
      if (!empty($params) && isset($params['url'])) {
        if (count($params) > 1) {
          $url = $params['url'] . '?';
        } else {
          $url = $params['url'];
        }
        unset($params['url']);
        $i = 0;
        foreach ($params as $key => $param) {
          $i++;
          $url .= $key . '=' . $param;
          if (count($params) > $i)
            $url .= '&';
        }
        return $url;
      }
      //print_r($params);
      if (isset($params['url'], $params['page']))
        return $params['url'] . '?page=' . $params['page'];
      else
        return false;
    }
    */
    return false;  // не применяем данное правило
  }

  public function parseUrl($manager, $request, $pathInfo, $rawPathInfo){
    $data = explode('/', $pathInfo);
    $type_route = array_shift($data);
    if (in_array($type_route, $this->typeRoute)) {
      $_GET = array();
      $_GET['type_route'] = $type_route;

      $anonymousParams = array();
      $getParam = false;
      foreach($data as $one){
        if ($getParam) {
          $_GET[$getParam] = $one;
          $getParam = false;
          continue;
        }
        if (isset($this->reservedKeywords[$one])) {
          if ($this->reservedKeywords[$one]['params']) {
            $getParam = $one;
          } else {
            $_GET[$this->reservedKeywords[$one]['name']] = $one;
          }
          continue;
        }
        $anonymousParams[] = $one;
      }
      switch($type_route) {
        case PpRoute::TYPE_ROUTE_SAME:
          if ($city = array_shift($anonymousParams)) {
            $_GET['cityFrom'] = $_GET['cityTo'] = $city;
          }
          break;
        case PpRoute::TYPE_ROUTE_ANOTHER:
          if ($city = array_shift($anonymousParams)) {
            $_GET['cityFrom'] = $city;
          }
          if ($city = array_shift($anonymousParams)) {
            $_GET['cityTo'] = $city;
          }
          break;
      }
      if(count($anonymousParams) > 0) {
        $_GET['anonymousParams'] = $anonymousParams;
      }

      return 'poputchikOrder';
    }
    return false;
  }

}

?>
