<?php

class CmsUrlRule extends CBaseUrlRule {

    public $connectionID = 'db';

    public function createUrl($manager, $route, $params, $ampersand) {
        if ($route === 'cms/front/initCms') {
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
        return false;  // не применяем данное правило
    }
    
    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo) {
        if ($cms = Cms::model()->find('url=:url', array(':url' => $pathInfo))) {
            $_GET['url'] = $cms->url;
            return 'cms/front/initCms';
        } else if ($cms = Blog::model()->find('url=:url', array(':url' => urldecode(substr($request->requestUri, 1))))) {
            $_GET['url'] = $cms->url;
            return 'cms/front/initCms';
        }
        return false;
    }

}

?>
