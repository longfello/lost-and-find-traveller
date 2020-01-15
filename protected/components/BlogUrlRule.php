<?php

class BlogUrlRule extends CBaseUrlRule {

    public $connectionID = 'db';

    public function createUrl($manager, $route, $params, $ampersand) {
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
        return false;  // не применяем данное правило
    }

    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo) {
        if ($blog = Blog::model()->find('url=:url', array(':url' => $pathInfo))) {
            $_GET['url'] = $blog->url;
            return 'blog/view';
        } else if ($blog = Blog::model()->find('url=:url', array(':url' => urldecode(substr($request->requestUri, 1))))) {
            $_GET['url'] = $blog->url;
            return 'blog/view';
        }
        return false;
    }

}

?>
