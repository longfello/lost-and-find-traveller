<?php
/**
 * Created by PhpStorm.
 * User: Miloslawsky
 * Date: 25.09.14
 * Time: 11:44
 */

include_once(Yii::app()->basePath.'/components/geocoder/autoload.php');

class EGeocoderHelper {
  const LOCALE = 'ru_RU';
  const REGION = NULL;
  const TOPONYM = NULL;
  const COUNTRY = NULL;

  static function getCity($city_data){
    $cache_hash = 'Geocoder_city_'.sha1($city_data);
    if ($city = Yii::app()->cache->get($cache_hash)) {
      return $city;
    }

    // $adapter  = new \Geocoder\HttpAdapter\BuzzHttpAdapter();
    // $adapter  = new \Geocoder\HttpAdapter\SocketHttpAdapter();
    $adapter  = new \Geocoder\HttpAdapter\CurlHttpAdapter();

	  $providers = array(
			  new \Geocoder\Provider\YandexProvider($adapter, self::LOCALE, self::TOPONYM), // Умеренно находит на русском
//			  new \Geocoder\Provider\GeonamesProvider($adapter, 'Miloslawsky', self::LOCALE), // Умеренно находит на русском
//			  new \Geocoder\Provider\GoogleMapsProvider($adapter, self::LOCALE, self::REGION, false), // Много находит на русском
//			  new \Geocoder\Provider\OpenStreetMapProvider($adapter, self::LOCALE), // Мало находит на русском
//			  new \Geocoder\Provider\TomTomProvider($adapter, '050e8448-c384-4d1b-81cb-28d66d4ddb26', self::LOCALE), // Мало находит на русском
//			  new \Geocoder\Provider\OpenCageProvider($adapter, 'cbbebbad6401fab24820e9f9abf4e61c', false,  self::LOCALE), // Мало находит на русском
//			  new \Geocoder\Provider\NominatimProvider($adapter, '', false,  self::LOCALE), // Нихрена не находит на русском
//			  new \Geocoder\Provider\MapQuestProvider($adapter, 'Fmjtd%7Cluurn9uy2h%2Cb5%3Do5-9wza5w', self::LOCALE), // Нихрена не находит на русском
//			  new \Geocoder\Provider\BingMapsProvider($adapter, 'AvF2MePGTplfxZ0pogRmCl98yP_EyQQvHXa47I9fGZIlr1T7qWRtmTmY8SpbWR5u', self::LOCALE), // Находит на русском, норм
//        new \Geocoder\Provider\ArcGISOnlineProvider($adapter, self::COUNTRY, false),
	  );

    $geocoder = new \Geocoder\Geocoder();
    $geocoder->registerProviders($providers);

    try {
	    $c = 1+count($providers);

	    do {
		    $geocode = $geocoder->geocode($city_data);
		    $c--;
	    } while ($c > 0 && !($city = $geocode->getCity()));

//	    $geocode = $geocoder->geocode($city_data);
//	    $city = $geocode->getCity();
      if ($city) {
        Yii::app()->cache->set($cache_hash, $city, 3600);
        return $city;
      } else return $city_data;
    } catch (Exception $e) {
      echo $e->getMessage();
      return $city_data;
    }
  }

  static function getCityByCoord($lat, $lng){
    $cache_hash = 'Geocoder_cityCoord_'.sha1($lat.','.$lng);
    if ($city = Yii::app()->cache->get($cache_hash)) {
      return $city;
    }

    // $adapter  = new \Geocoder\HttpAdapter\BuzzHttpAdapter();
    $adapter  = new \Geocoder\HttpAdapter\CurlHttpAdapter();

    $geocoder = new \Geocoder\Geocoder();
	  $providers = array(
			  new \Geocoder\Provider\GoogleMapsProvider($adapter, self::LOCALE, self::REGION, false),
			  new \Geocoder\Provider\YandexProvider($adapter, self::LOCALE, self::TOPONYM),
			  new \Geocoder\Provider\GeonamesProvider($adapter, 'Miloslawsky', self::LOCALE),
			  new \Geocoder\Provider\OpenStreetMapProvider($adapter, self::LOCALE),
			  new \Geocoder\Provider\TomTomProvider($adapter, '050e8448-c384-4d1b-81cb-28d66d4ddb26', self::LOCALE),
			  new \Geocoder\Provider\OpenCageProvider($adapter, 'cbbebbad6401fab24820e9f9abf4e61c', false,  self::LOCALE),
			  new \Geocoder\Provider\NominatimProvider($adapter, '', false,  self::LOCALE),
			  new \Geocoder\Provider\MapQuestProvider($adapter, 'Fmjtd%7Cluurn9uy2h%2Cb5%3Do5-9wza5w', self::LOCALE),
			  new \Geocoder\Provider\BingMapsProvider($adapter, 'AvF2MePGTplfxZ0pogRmCl98yP_EyQQvHXa47I9fGZIlr1T7qWRtmTmY8SpbWR5u', self::LOCALE),
//		    new \Geocoder\Provider\ArcGISOnlineProvider($adapter, self::COUNTRY, false),
	  );
    $geocoder->registerProviders($providers);

    try {
	    $c = count($providers);

	    do {
		    $geocode = $geocoder->reverse($lat, $lng);
		    $c--;
	    } while ($c > 0 && !($city = $geocode->getCity()));

      if ($city) {
	      $return = array(
		      'city' => $city,
		      'zone' => $geocode->getRegion(),
		      'country' => $geocode->getCountry()
	      );
	      Yii::app()->cache->set($cache_hash, $return, 3600);
        return $return;
      } else {
	      return false;
      }
    } catch (Exception $e) {
      echo $e->getMessage();
      return '';
    }
  }


	static function getCityByCoordMy($lat, $lng, $id = false){
		$lng = floatval($lng);
		$lat = floatval($lat);

    $add_order = $id?" id <> $id, ":"";

		$query = "SELECT *, Y(EndPoint(area)) end_lat, X(EndPoint(area)) end_lng, Y(StartPoint(area)) start_lat, X(StartPoint(area)) start_lng FROM geoname WHERE MBRContains (area, POINT($lng, $lat)) ORDER BY $add_order population ASC";

		return Yii::app()->db->createCommand($query)->queryAll();
	}

	static function getCityByNameMy($name){
    $name = mysql_real_escape_string($name);
		$query = "SELECT *, Y(EndPoint(area)) end_lat, X(EndPoint(area)) end_lng, Y(StartPoint(area)) start_lat, X(StartPoint(area)) start_lng FROM geoname WHERE name_ru='$name' OR name_en='$name' ORDER BY population DESC";
		return Yii::app()->db->createCommand($query)->queryAll();
	}

  static function getFullName($id) {
    $query = "SELECT city.name_ru city_ru, city.name_en city_en,
                         geocountry.name_ru country_ru, geocountry.name_en country_en,
                         geozone.name_ru zone_ru,geozone.name_en zone_en,
                         city.id geonameid, city.latitude, city.longitude
          FROM geoname city
          LEFT JOIN geozone ON geozone.id = city.zone
          LEFT JOIN geocountry ON geocountry.id = city.country
          WHERE city.id = $id";
    $one = Yii::app()->db->createCommand($query)->queryRow();
    $lang    = Yii::app()->getLanguage();
    $zone    = $one['zone_'.$lang]?', '.$one['zone_'.$lang]:'';
    $country = $one['country_'.$lang]?', '.$one['country_'.$lang]:'';
    return $one['city_'.$lang].$zone.$country;
  }

}