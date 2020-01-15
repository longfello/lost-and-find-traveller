<?php

class StatsController extends AController {

    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('city', 'Intercity', 'test'),
                'users' => array('admin', 'moderator'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionTest() {
        SiteMapHelper::Generate();
    }

    public function actionCity() {

        $command = 'SELECT COUNT(`poputchik_order`.`id_settlement`) AS cnt, s.name as name FROM `poputchik_order` LEFT JOIN settlements s ON poputchik_order.id_settlement = s.id_settlement WHERE `poputchik_order`.`type_route` = 1 AND `poputchik_order`.date_available >= \'' . date('Y-m-d') . '\' GROUP BY `poputchik_order`.`id_settlement` ORDER by s.name';
        $command = Yii::app()->db->createCommand($command);
        $citys = $command->queryAll();
        $this->render('city', array(
            'citys' => $citys,
        ));
    }

    public function actionIntercity() {
      /*
        $command = 'SELECT COUNT(`routes`.`start_settlement`) AS cnt, s.name as name, s2.name as name2, `poputchik_order`.direction
            FROM `poputchik_order`
            LEFT JOIN routes ON poputchik_order.`id_route` = routes.id_route
            
            LEFT JOIN settlements s ON `routes`.`start_settlement` = s.id_settlement 
            LEFT JOIN settlements s2 ON `routes`.`end_settlement` = s2.id_settlement 
           
            WHERE `poputchik_order`.`type_route` = 2 AND `poputchik_order`.`id_route` IS NOT NULL AND `poputchik_order`.date_available >= \'' . date('Y-m-d') . '\' 
                GROUP BY `routes`.`start_settlement`, `routes`.`end_settlement` 
                ORDER by s.name';


        $command = Yii::app()->db->createCommand($command);
        $routes = $command->queryAll();
        $keys = array();
        foreach ($routes as $key => $route) {
            if (!empty($route['direction']) && $route['direction'] == 1) {
                $tmp = $route['name'];
                $routes[$key]['name'] = $routes[$key]['name2'];
                $routes[$key]['name2'] = $tmp;
                $keys[] = $key;
            }
        }


        $command = 'SELECT COUNT(`paths`.`id_settlement_1`) AS cnt, s.name as name, s2.name as name2, `poputchik_order`.direction
            FROM `poputchik_order`
            LEFT JOIN paths ON poputchik_order.`id_path` = paths.id_path 
            
            LEFT JOIN settlements s ON `paths`.`id_settlement_1` = s.id_settlement 
            LEFT JOIN settlements s2 ON `paths`.`id_settlement_2` = s2.id_settlement 
            
            
            WHERE `poputchik_order`.`type_route` = 2 AND `poputchik_order`.`id_path` IS NOT NULL AND `poputchik_order`.date_available >= \'' . date('Y-m-d') . '\' 
                GROUP BY `paths`.`id_settlement_1`, `paths`.`id_settlement_2`
                ORDER by s.name';

        $command = Yii::app()->db->createCommand($command);
        $paths = $command->queryAll();
        $keys = array();

        foreach ($paths as $key => $path) {
            if (!empty($path['direction']) && $path['direction'] == 1) {
                $tmp = $path['name'];
                $paths[$key]['name'] = $paths[$key]['name2'];
                $paths[$key]['name2'] = $tmp;
                $keys[] = $key;
            }
        }


       
        foreach ($paths as $key => $path) {

            foreach ($routes as $key2 => $route) {
                if ($route['name'] == $path['name'] && $route['name2'] == $path['name2']) {
                    $paths[$key] = array('name' => $route['name'], 'name2' => $route['name2'], 'cnt' => $route['cnt'] + $path['cnt']);
                    unset($routes[$key2]);
                }
            }
        }
        $citys = array_merge($paths, $routes);
        
        
        // Получение списка столбцов
foreach ($citys as $key => $row) {
    $name[$key]  = $row['name'];
    $name1[$key] = $row['name1'];
    $cnt[$key] = $row['cnt'];
}

// Сортируем данные по volume по убыванию и по edition по возрастанию
// Добавляем $data в качестве последнего параметра, для сортировки по общему ключу
array_multisort($name, SORT_ASC, $name1, SORT_ASC, $cnt, SORT_ASC, $citys);

      var_dump($citys);*/

      $query = "
SELECT gf.name_ru name, gt.name_ru name2, count(r.id) cnt
FROM `pp_route` r
LEFT JOIN geoname gf ON gf.id = r.from_id
LEFT JOIN geoname gt ON gt.id = r.to_id
WHERE r.available_until >= '" . date('Y-m-d') . "' AND enabled=1
GROUP BY from_id, to_id
ORDER BY gf.name_ru, gt.name_ru
";
        $this->render('intercity', array(
            'citys' => Yii::app()->db->commandBuilder->createSqlCommand($query)->queryAll(),
        ));
    }

}

?>
