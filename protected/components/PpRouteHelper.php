<?php
/**
 * Created by PhpStorm.
 * User: Miloslawsky
 * Date: 14.04.2015
 * Time: 10:16
 */

class PpRouteHelper {

  static function array2path($path, $invert = false){
    if (count($path) > 1) {
      $s = array();
      $point1 = array_shift($path);
      while($point2 = array_shift($path)) {
        $s[] = array(
          array(
              floatval($invert?$point1['lat']:$point1['lng']),
              floatval($invert?$point1['lng']:$point1['lat']),
          ),
          array(
              floatval($invert?$point2['lat']:$point2['lng']),
              floatval($invert?$point2['lng']:$point2['lat']),
          )
        );
        $point1 = $point2;
      }
      return array($s);
    } else return 'null';
  }

  static function getFullPath($path){
    $x0=0; $y0=0; $pid = array(); $full_path = array();
    foreach($path as $point){
      //    echo($point['lat'].','.$point['lng'].'<br>');
      $info = EGeocoderHelper::getCityByCoordMy($point['lng'],$point['lat']);
      if ($info) {
        if (!in_array($info[0]['id'], $pid)) {
          $pid[] = $info[0]['id'];
        }
      }
      if ($x0) {
        $query = "SELECT * FROM `geoname` WHERE st_touches((linestring(point($x0, $y0), point({$point['lat']}, {$point['lng']}))), area) ORDER BY st_distance(point($x0, $y0), coord);";
        $res = Yii::app()->db->createCommand($query)->queryAll();
        foreach ( $res as $one ) {
          if (!in_array($one['id'], $pid)) {
            $full_path[] = array(
                'lat' => $one['latitude'],
                'lng' => $one['longitude']
            );
            $pid[] = $one['id'];
          }
        }

      }
      if ($pnt = array_shift($info)) {
        $y = str_replace(',','.',$pnt['longitude']);
        $x = str_replace(',','.',$pnt['latitude']);
        $full_path[] = array(
            'lat' => $x,
            'lng' => $y
        );
      }
    }

    // array_unique аналог для многомерных массивов
    $full_path = array_map("unserialize", array_unique(array_map("serialize", $full_path)));

    return $full_path;
  }

  static function inverseCoordinatesInPath($path){
    $full_path = array();
    foreach($path as $point){
      $full_path[] = array(
          'lat' => $point['lng'],
          'lng' => $point['lat']
      );
    }
    return $full_path;
  }

  static function reduce($what, $elements){
    while(count($what) > $elements) {
      unset($what[array_rand($what)]);
    }
    return $what;
  }

  static function convertDate($from){
    $data = explode('.', trim($from['date']));
    if (count($data) == 3) {
      list($d, $m, $Y) = $data;
      return date('Y-m-d H:i:s', mktime($from['time']['H'], $from['time']['m'], 0, $m, $d, $Y));
    } else return null;
  }

  static function getOrCreateUserID($data){
    if ($data['phone']) {
      $user = User::model()->findByAttributes(array('username' => $data['phone']));
      if ($user) {
        return $user->id;
      } else {
        $model   = new User;
        $model->scenario = 'autopost';
        /* @var User $model */
        $profile = new Profile;

        $password = uniqid();
        $model->username = $data['phone'];
        $model->password = UserModule::encrypting($password);
        $model->email    = $data['email'];
        $model->status   = 1;
        $model->city_id  = intval($data['start-location']['id']);
        if($model->save()) {
          $profile->user_id=$model->id;

          if ($data['avatar']) {
            $photo_dir  = '/files/users_photos/'.substr(md5($profile->user_id), 0, 2).'/';
            if(!is_dir(YiiBase::getPathOfAlias('webroot').$photo_dir)) mkdir(YiiBase::getPathOfAlias('webroot').$photo_dir, 0777, true);
            $photo_name = $profile->user_id.'.jpg';
            @unlink(YiiBase::getPathOfAlias('webroot').$photo_dir.$photo_name);

            $source_file = YiiBase::getPathOfAlias('webroot').'/images/'.$data['avatar'];
            if(@copy($source_file, YiiBase::getPathOfAlias('webroot').$photo_dir.$photo_name)) {
              $profile->photo = $photo_dir.$photo_name;
              list($width, $height, $type, $attr) = getimagesize(YiiBase::getPathOfAlias('webroot').$profile->photo);
              if($width > 1024 || $height > 1024) {
                $image = new EasyImage(YiiBase::getPathOfAlias('webroot').$profile->photo);
                $image->resize(1024, 1024);
                $image->save(YiiBase::getPathOfAlias('webroot').$profile->photo);
              }
            }
          }
          $profile->first_name = $data['name'];
          if(!$profile->save()) {
            echo(CHtml::errorSummary($model));
            die();
          }
        } else {
          echo(CHtml::errorSummary($model));
          die();
        }
        return $model->id;
      }
    } else return null;
  }

  static function getEnvelope($lat, $lng, $distantion_in_meters = 200){
    // $lat= 48.476;
    // $lon = 35.016;
    $dist = $distantion_in_meters*1.609344/1000;
    $rlon1 = $lng-$dist/abs(cos(deg2rad($lat))*69);
    $rlon2 = $lng+$dist/abs(cos(deg2rad($lat))*69);
    $rlat1 = $lat-($dist/69);
    $rlat2 = $lat+($dist/69);

    return "envelope(linestring(point({$rlon1}, {$rlat1}), point({$rlon2}, {$rlat2})))";
  }
}