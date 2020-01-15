<?php

/**
 * This is the model class for table "pp_route".
 *
 * The followings are the available columns in table 'pp_route':
 * @property string $id
 * @property integer $uid
 * @property integer $from_id
 * @property integer $to_id
 * @property string $from
 * @property string $to
 * @property string $from_address
 * @property string $to_address
 * @property string $path
 * @property string $path_full
 * @property string $departure
 * @property string $departure_periodicity
 * @property string $return
 * @property double $cost
 * @property integer $car_id
 * @property integer $free_seats
 * @property string $luggage
 * @property string $punctuality
 * @property string $deviation_from_route
 * @property string $available_until
 * @property string $comment
 * @property string $type
 * @property integer $enabled
 * @property string $created
 * @property integer $viewed
 * @property integer $old_id
 *
 * @property Geoname $fromLocation
 * @property Geoname $toLocation
 * @property User $user
 * @property AutoModels $auto
 */
class PpRoute extends CActiveRecord {
  const TYPE_ROUTE_SAME      = 'poputchik-po-gorodu';
  const TYPE_ROUTE_ANOTHER   = 'poputchik-v-gorod';

	const TYPE_DRIVER     = EnumRouteType::TYPE_DRIVER;
	const TYPE_PASSENGER  = EnumRouteType::TYPE_PASSENGER;

  const LUGGAGE_SMALL  = EnumRouteLuggage::LUGGAGE_SMALL;
  const LUGGAGE_MEDIUM = EnumRouteLuggage::LUGGAGE_MEDIUM;
  const LUGGAGE_LARGE  = EnumRouteLuggage::LUGGAGE_LARGE;

  const PUNCTUALITY_EXACTLY        = EnumRoutePunctuality::PUNCTUALITY_EXACTLY;
  const PUNCTUALITY_WITHIN_15_MIN  = EnumRoutePunctuality::PUNCTUALITY_WITHIN_15_MIN;
  const PUNCTUALITY_WITHIN_30_MIN  = EnumRoutePunctuality::PUNCTUALITY_WITHIN_30_MIN;
  const PUNCTUALITY_WITHIN_1_HOUR  = EnumRoutePunctuality::PUNCTUALITY_WITHIN_1_HOUR;
  const PUNCTUALITY_WITHIN_2_HOURS = EnumRoutePunctuality::PUNCTUALITY_WITHIN_2_HOURS;

  const DEVIATION_NONE           = EnumRouteDeviation::DEVIATION_NONE;
  const DEVIATION_WITHIN_15_MIN  = EnumRouteDeviation::DEVIATION_WITHIN_15_MIN;
  const DEVIATION_WITHIN_30_MIN  = EnumRouteDeviation::DEVIATION_WITHIN_30_MIN;
  const DEVIATION_WITHIN_1_HOUR  = EnumRouteDeviation::DEVIATION_WITHIN_1_HOUR;
  const DEVIATION_WITHIN_2_HOURS = EnumRouteDeviation::DEVIATION_WITHIN_2_HOURS;

  const CITY_ON_PATH_MINIMUM_POPULATION = 100000;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pp_route';
	}

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        array('uid, from_id, to_id, from, to, path, path_full, cost', 'required'),
        array('id, uid, from_id, to_id, car_id, free_seats, enabled, viewed, old_id', 'numerical', 'integerOnly'=>true),
        array('cost', 'numerical'),
        array('comment, departure_periodicity, from_address, to_address', 'length', 'max'=>255),
        array('type', 'in', 'range' => EnumRouteType::getValidValues()),
        array('luggage', 'in', 'range' => EnumRouteLuggage::getValidValues()),
        array('punctuality', 'in', 'range' => EnumRoutePunctuality::getValidValues()),
        array('deviation_from_route', 'in', 'range' => EnumRouteDeviation::getValidValues()),
        array('departure, return, available_until, created, from_address, to_address', 'safe'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
        array('id, uid, from_id, to_id, departure, return, cost, car_id, available_until, comment, type, from_address, to_address', 'safe', 'on'=>'search'),
    );
  }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
      'auto'          =>  array(self::BELONGS_TO, 'AutoModels',    'car_id'),
      'user'          =>  array(self::BELONGS_TO, 'User',          'uid'),
      'fromLocation'  =>  array(self::BELONGS_TO, 'Geoname',       'from_id'),
      'toLocation'    =>  array(self::BELONGS_TO, 'Geoname',       'to_id'),
		);
	}

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
        'id' => 'ID',
        'uid' => 'Пользователь',
        'from_id' => 'ID локации откуда',
        'to_id' => 'ID локации куда',
        'from' => 'Исходная точка',
        'to' => 'Конечная точка',
        'from_address' => 'Исходный адрес',
        'to_address' => 'Конечный адрес',
        'path' => 'Маршрут',
        'path_full' => 'Полный маршрут с промежуточными точками',
        'departure' => 'Отправление',
        'departure_periodicity' => 'Периодичность отправления',
        'return' => 'Возвращение',
        'cost' => 'Стоимость',
        'car_id' => 'Автомобиль',
        'free_seats' => 'Посадочных мест',
        'luggage' => 'Багаж',
        'punctuality' => 'Пунктуальность отправления',
        'deviation_from_route' => 'Отклонение от маршрута',
        'available_until' => 'Объявление действительно до',
        'comment' => 'Комметарий',
        'type' => 'Тип объявления',
        'enabled' => 'Объявление разрешено',
        'viewed' => 'Просмотров',
        'created' => 'Создано',
        'old_id' => 'Old ID',
    );
  }

  /**
   * Retrieves a list of models based on the current search/filter conditions.
   *
   * Typical usecase:
   * - Initialize the model fields with values from filter form.
   * - Execute this method to get CActiveDataProvider instance which will filter
   * models according to data in model fields.
   * - Pass data provider to CGridView, CListView or any similar widget.
   *
   * @return CActiveDataProvider the data provider that can return the models
   * based on the search/filter conditions.
   */
  public function search()
  {
    // @todo Please modify the following code to remove attributes that should not be searched.

    $criteria=new CDbCriteria;

    $criteria->compare('id',$this->id,true);
    $criteria->compare('uid',$this->uid);
    $criteria->compare('from_id',$this->from_id);
    $criteria->compare('to_id',$this->to_id);
    $criteria->compare('departure',$this->departure,true);
    $criteria->compare('return',$this->return,true);
    $criteria->compare('cost',$this->cost);
    $criteria->compare('car_id',$this->car_id);
    $criteria->compare('available_until',$this->available_until,true);
    $criteria->compare('comment',$this->comment,true);
    $criteria->compare('type',$this->type,true);
    $criteria->compare('enabled',$this->enabled,true);
    $criteria->compare('from_addreaa',$this->from_address,true);
    $criteria->compare('to_address',$this->to_address,true);

    return new CActiveDataProvider($this, array(
        'criteria'=>$criteria,
    ));
  }

  public function behaviors() {
    return array(
        'spatial'=>array(
            'class'=>'application.components.behaviors.SpatialDataBehavior',
            'spatialFields'=>array(
                'from',
                'to',
                'path',
                'path_full'
            ),
        )
    );
  }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PpRoute the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

  public function interCity($from_city, $to_city){
    /*
    SELECT *
    FROM pp_route route
    INNER JOIN geoname source ON st_touches(route.path_full, source.area) AND source.id = 709930
    INNER JOIN geoname target ON st_touches(route.path_full, target.area) AND target.id = 524901
    WHERE st_distance(route.from, source.coord) < st_distance(route.from, target.coord)
    */

    $criteria = new CDbCriteria();
    $criteria->select = "{$this->tableAlias}.*";
    $criteria->join = "";
    if ($from_city) {
      $criteria->join .= " INNER JOIN pp_route_cache cache_from ON cache_from.route_id = {$this->tableAlias}.id AND cache_from.geoname_id = {$from_city->id} ";
      $criteria->join .= " LEFT  JOIN geoname source ON source.id = cache_from.geoname_id ";
//      $criteria->join .= " INNER JOIN geoname source ON ST_Overlaps({$this->tableAlias}.path_full, source.area) AND source.id = {$from_city->id} ";
//      $criteria->join .= " INNER JOIN geoname source ON st_touches({$this->tableAlias}.path_full, source.area) AND source.id = {$from_city->id} ";
      $criteria->addCondition("{$this->tableAlias}.to_id <> {$from_city->id}");
    }
    if ($to_city)   {
      $criteria->join .= " INNER JOIN pp_route_cache cache_to ON cache_to.route_id = {$this->tableAlias}.id AND cache_to.geoname_id = {$to_city->id} ";
      $criteria->join .= " LEFT  JOIN geoname target ON target.id = cache_to.geoname_id ";
//      $criteria->join .= " INNER JOIN geoname target ON ST_Overlaps({$this->tableAlias}.path_full, target.area) AND target.id = {$to_city->id} ";
//      $criteria->join .= " INNER JOIN geoname target ON st_touches({$this->tableAlias}.path_full, target.area) AND target.id = {$to_city->id} ";
      $criteria->addCondition("{$this->tableAlias}.from_id <> {$to_city->id}");
    }
    if ($from_city && $to_city) {
      $criteria->addCondition("st_distance({$this->tableAlias}.from, source.coord) < st_distance({$this->tableAlias}.from, target.coord)");
    }
    $this->getDbCriteria()->mergeWith($criteria);
    return $this;
  }

  public function inCity($city){
    $criteria = new CDbCriteria();
    $criteria->select = "{$this->tableAlias}.*";
    $criteria->join = "";
    if ($city->id) {
      $criteria->addCondition("{$this->tableAlias}.from_id = {$city->id}");
      $criteria->addCondition("{$this->tableAlias}.to_id = {$city->id}");
    } else {
      $criteria->addCondition("{$this->tableAlias}.from_id = {$this->tableAlias}.to_id");
    }
    $this->getDbCriteria()->mergeWith($criteria);
    return $this;
  }

	public static function validateStep($type, $step, $data) {
		$result = array(
			'result'   => 'error',
		  'messages' => array()
		);

		switch($type) {
			case self::TYPE_DRIVER:
				switch ($step) {
					case 1:
						$result['result'] = 'ok';
						break;
					case 2:
						$result['result'] = 'ok';
						break;
					case 3:
						$result['result'] = 'ok';
						break;
					default:
						$result['messages']['overall'] = 'Неизвестный шаг формы';
				}
				break;
			case self::TYPE_PASSENGER:
				switch ($step) {
					case 1:
						$result['result'] = 'ok';
						break;
					case 2:
						$result['result'] = 'ok';
						break;
					case 3:
						$result['result'] = 'ok';
						break;
					default:
						$result['messages']['overall'] = 'Неизвестный шаг формы';
				}
				break;
			default:
				$result['messages']['overall'] = 'Неизвестный тип формы';
		}
		return $result;
	}

  public function isActive(){
    return (strtotime($this->available_until) >= time()) &&
    (
        ((is_null($this->departure_periodicity)) && (strtotime($this->departure) >= time()))
        ||
        (!is_null($this->departure_periodicity))
    );
  }

  public function getRouteLocations($limit_population = self::CITY_ON_PATH_MINIMUM_POPULATION){
    $language = Yii::app()->language;

    $basePoints = array();
    $path = $this->path;

    array_pop($path);
//    array_shift($path);

//    var_dump($path);

    foreach($path as $line){
      $basePoints[] = array(
        'id' => 0,
        'name' => 'waypoint',
        'lng' => $line[1][0],
        'lat' => $line[1][1],
      );
    }


    $cnt = Yii::app()->db->commandBuilder->createSqlCommand("
SELECT g.id, g.name_{$language} name, g.latitude lat, g.longitude lng FROM pp_route r
INNER JOIN `geoname` g ON  ST_Touches(g.area, r.path_full)
WHERE r.id = {$this->id} AND g.population > ".$limit_population." AND g.id <> r.from_id AND g.id <> r.to_id
  AND st_distance(r.from, r.to)/10 < st_distance(r.from, g.coord)
  AND st_distance(r.from, r.to)/10 < st_distance(r.to, g.coord)
ORDER BY st_distance(r.`from`, coord);")->queryAll();

    $res = array(
        'base' => $basePoints,
        'near' => $cnt
    );
    if (count($cnt) < 5 && $limit_population > self::CITY_ON_PATH_MINIMUM_POPULATION/pow(2,12)) {
      $res = $this->getRouteLocations($limit_population/4);
    }

//    var_dump($cnt);
    return $res;
  }

  public function getRouteWaypoints(){
    $nearest_points = $this->getRouteLocations();
    $res_base = $nearest_points['base'];
    $res = array();

    foreach($nearest_points['near'] as $nearest_point) {
      $distance = null; $point = null;
      foreach($this->path_full as $line){
        $_point = $line[1];
        $_distance = self::getDistance($_point[1], $_point[0], $nearest_point['lat'], $nearest_point['lng']);
        if (is_null($distance) || $_distance < $distance) {
          $distance = $_distance;
          $point   = $_point;
        }
      }
      if (!is_null($point)) {
        $res[] = array(
          'lat' => $point[1],
          'lng' => $point[0]
        );
      }
    }

    $res = count($res_base>4)?array():PpRouteHelper::reduce($res, 5 - count($res_base));
    $res = array_merge($res_base, $res);

    return $res;
  }

// Расстояние в градусах между двумя точками
  public static function getDistance($lat1, $lng1, $lat2, $lng2) {

    $a = abs($lat1 - $lat2);
    $b = abs($lng1 - $lng2);
    $c = sqrt(pow($a, 2) + pow($b, 2));

    return $c;
    /*
    $lat1 *= M_PI / 180;
    $lat2 *= M_PI / 180;
    $lng1 *= M_PI / 180;
    $lng2 *= M_PI / 180;

    $d_lon = $lng1 - $lng2;

    $slat1 = sin($lat1);
    $slat2 = sin($lat2);
    $clat1 = cos($lat1);
    $clat2 = cos($lat2);
    $sdelt = sin($d_lon);
    $cdelt = cos($d_lon);

    $y = pow($clat2 * $sdelt, 2) + pow($clat1 * $slat2 - $slat1 * $clat2 * $cdelt, 2);
    $x = $slat1 * $slat2 + $clat1 * $clat2 * $cdelt;

    return atan2(sqrt($y), $x) * 6372795;
    */
  }
}
