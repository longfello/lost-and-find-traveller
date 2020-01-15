<?php

class ApiController extends AController
{
  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  public $layout = false;

  public function actionCityAutocomplete(){
    // header('Content-type: application/json');
    $ret = array();

    $what = Yii::app()->request->getParam('term', false);
    if ($what) {
      $infos = Sphinx::SearchCity($what);
      foreach($infos as $one) {
        $lang    = Yii::app()->getLanguage();
        $zone    = $one['zone_'.$lang]?', '.$one['zone_'.$lang]:'';
        $country = $one['country_'.$lang]?', '.$one['country_'.$lang]:'';
        $ret[] = array(
          'id'    => $one['geonameid'],
          'lat'   => $one['latitude'],
          'lng'   => $one['longitude'],
          'slug'  => $one['slug'],
          'pos'   => $one['latitude'].','.$one['longitude'],
          'label' => $one['city_'.$lang].$zone.$country,
          'value' => $one['city_'.$lang].$zone.$country,
          'area'  => array(
            'start' => array(
              'lat' => $one['start_lat'],
              'lng' => $one['start_lng'],
            ),
            'end' => array(
              'lat' => $one['end_lat'],
              'lng' => $one['end_lng'],
            )
          )
        );
      }
    }

    // [{"id":"Cuculus canorus","label":"Common Cuckoo","value":"Common Cuckoo"},{"id":"Clamator glandarius","label":"Great Spotted Cuckoo","value":"Great Spotted Cuckoo"},{"id":"Coccyzus americanus","label":"Yellow-Billed Cuckoo","value":"Yellow-Billed Cuckoo"},{"id":"Puffinus yelkouan","label":"Yelkouan Shearwater","value":"Yelkouan Shearwater"}]
    echo json_encode($ret);
    Yii::app()->end();
  }
  public function actionCityCorrector (){
    $return = array(
      'result' => true
    );
    $data = Core::getPost('data', array());
    if (is_array($data)){
      $return['correct'] = array();
      foreach($data as $one) {
        $cities = EGeocoderHelper::getCityByCoordMy($one['lat'], $one['lng'], $one['id']);
        $city = $cities?$cities[0]:false;
        if ($city) {
          if ($city['id'] != $one['id']) {
            $return['correct'][$one['type']] = array(
                'id'    => $city['id'],
                'lat'   => $city['latitude'],
                'lng'   => $city['longitude'],
                'pos'   => $city['latitude'].','.$city['longitude'],
                'name'  => EGeocoderHelper::getFullName($city['id']),
                'area'  => array(
                      'start' => array(
                          'lat' => $city['start_lat'],
                          'lng' => $city['start_lng'],
                      ),
                      'end' => array(
                          'lat' => $city['end_lat'],
                          'lng' => $city['end_lng'],
                      )
                  )
            );
          }
        }
      }
    }
    echo(json_encode($return));
  }
  public function actionUploader(){
    if (empty($_FILES) || $_FILES["file"]["error"]) {
      die('{"OK": 0}');
    }

    $fileName = Core::translit($_FILES["file"]["name"], true);

    $type = Core::getPost('type', 'file', type_string);
    $sdir = 'upload/'.$type.'/'.session_id().'/';
    $dir  = Yii::getPathOfAlias('webroot').'/images/'.$sdir;
    if (!is_dir($dir)){
      mkdir($dir, 0777, true);
    }

    move_uploaded_file($_FILES["file"]["tmp_name"], $dir.$fileName);

    die('{"OK": 1, "filename": "'.$sdir.$fileName.'"}');
  }
}