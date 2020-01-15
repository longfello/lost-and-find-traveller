<?php

class SiteMapHelper {

    public static function Generate() {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $xml .= '<url>

      <loc>http://' . $_SERVER['SERVER_NAME'] . '</loc>
          <lastmod>' . date('Y-m-d') . '</lastmod>
           <changefreq>weekly</changefreq>
            <priority>0.8</priority>
             </url>';

        $cms = Cms::model()->findAll();
        foreach ($cms as $page) {
            if ($page->url != '/') {
                if ($page->updated != '0000-00-00 00:00:00') {
                    $page->updated = strtotime($page->updated);
                    $page->updated = date('Y-m-d', $page->updated);
                } else {
                    unset($page->updated);
                }
                $xml .= '<url>

         <loc>http://' . $_SERVER['SERVER_NAME'] . '/' . $page->url . '</loc>';
                if (isset($page->updated)) {
                    $xml .= '<lastmod>' . $page->updated . '</lastmod>';
                }
                $xml .= ' <changefreq>weekly</changefreq>
            <priority>0.7</priority>
             </url>';
            }
        }
        $xml .= '<url>

      <loc>http://' . $_SERVER['SERVER_NAME'] . '/poleznye-sovety</loc>
          <lastmod>' . date('Y-m-d') . '</lastmod>
           <changefreq>weekly</changefreq>
            <priority>0.7</priority>
          </url>';

        $blog = Blog::model()->findAll();
        foreach ($blog as $b) {
            if ($b->url != '/') {
                if ($b->updated != '0000-00-00 00:00:00') {
                    $b->updated = strtotime($b->updated);
                    $b->updated = date('Y-m-d', $b->updated);
                } else {
                    unset($b->updated);
                }


                $xml .= '<url>

         <loc>http://' . $_SERVER['SERVER_NAME'] . '/' . $b->url . '</loc>';
                if (isset($b->updated)) {
                    $xml .= '<lastmod>' . $b->updated . '</lastmod>';
                }
                $xml .= ' <changefreq>weekly</changefreq>
            <priority>0.6</priority>
             </url>';
            }
        }

        /*
        $xml .= '<url>

      <loc>http://' . $_SERVER['SERVER_NAME'] . '/poputchik-v-gorod</loc>
           <lastmod>' . date('Y-m-d') . '</lastmod>
           <changefreq>weekly</changefreq>
            <priority>0.7</priority>
          </url>';
        $command = 'SELECT COUNT(`poputchik_order`.`id_settlement`) AS cnt, `updated` FROM `poputchik_order` 

              WHERE `poputchik_order`.`type_route` = 2 AND `poputchik_order`.date_available >= \'' . date('Y-m-d') . '\'';
        $command = Yii::app()->db->createCommand($command);
        $city = $command->queryRow();
        $num_pages = ceil($city['cnt'] / 10);
        if ($num_pages > 1) {
            for ($i = 2; $i <= $num_pages; $i++) {

                $xml .= '<url>
      <loc>http://' . $_SERVER['SERVER_NAME'] . '/poputchik-v-gorod/?page=' . $i . '</loc>
          <lastmod>' . date('Y-m-d') . '</lastmod>
           <changefreq>weekly</changefreq>
            <priority>0.6</priority>
          </url>';
            }
        }
        $xml .= '<url>

      <loc>http://' . $_SERVER['SERVER_NAME'] . '/poputchik-po-gorodu</loc>
          <lastmod>' . date('Y-m-d') . '</lastmod>
           <changefreq>weekly</changefreq>
            <priority>0.7</priority>
          </url>';
        $command = 'SELECT COUNT(`poputchik_order`.`id_settlement`) AS cnt FROM `poputchik_order` 

              WHERE `poputchik_order`.`type_route` = 1 AND `poputchik_order`.date_available >= \'' . date('Y-m-d') . '\'';
        $command = Yii::app()->db->createCommand($command);
        $city = $command->queryRow();
        $num_pages = ceil($city['cnt'] / 10);
        if ($num_pages > 1) {
            for ($i = 2; $i <= $num_pages; $i++) {
                $xml .= '<url>

      <loc>http://' . $_SERVER['SERVER_NAME'] . '/poputchik-po-gorodu/?page=' . $i . '</loc>
          <lastmod>' . date('Y-m-d') . '</lastmod>
           <changefreq>weekly</changefreq>
            <priority>0.6</priority>
          </url>';
            }
        }
        $command = 'SELECT COUNT(`poputchik_order`.`id_settlement`) AS cnt, s.name as name, seo_city.url as url FROM `poputchik_order` 
              LEFT JOIN settlements s ON poputchik_order.id_settlement = s.id_settlement
              LEFT JOIN seo_city ON s.id_settlement = seo_city.city_id
              WHERE `poputchik_order`.`type_route` = 1 AND `poputchik_order`.date_available >= \'' . date('Y-m-d') . '\' GROUP BY `poputchik_order`.`id_settlement` ORDER by s.name';
        $command = Yii::app()->db->createCommand($command);
        $citys = $command->queryAll();

        foreach ($citys as $city) {
            if ($city['url'] != '') {
                $xml .= '<url>

      <loc>http://' . $_SERVER['SERVER_NAME'] . '/poputchik-po-gorodu/' . $city['url'] . '</loc>
          <lastmod>' . date('Y-m-d') . '</lastmod>
           <changefreq>weekly</changefreq>
            <priority>0.6</priority>
          </url>';

                $num_pages = ceil($city['cnt'] / 10);
                if ($num_pages > 1) {
                    for ($i = 2; $i <= $num_pages; $i++) {
                        $xml .= '<url>

      <loc>http://' . $_SERVER['SERVER_NAME'] . '/poputchik-po-gorodu/' . $city['url'] . '?page=' . $i . '</loc>
          <lastmod>' . date('Y-m-d') . '</lastmod>
           <changefreq>weekly</changefreq>
            <priority>0.6</priority>
          </url>';
                    }
                }
            }
        }

        */
        $xml .= '</urlset>';
        $file = Yii::app()->basePath . "/../sitemap.xml";

        $fp = fopen($file, "w");

        fwrite($fp, $xml);


        fclose($fp);
    }

}

?>
