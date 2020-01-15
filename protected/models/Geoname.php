<?php

/**
 * This is the model class for table "geoname".
 *
 * The followings are the available columns in table 'geoname':
 * @property integer $id
 * @property string $name_ru
 * @property string $name_en
 * @property string $alternatenames
 * @property string $latitude
 * @property string $longitude
 * @property integer $country
 * @property integer $zone
 * @property integer $population
 * @property string $timezone
 * @property integer $dia
 * @property string $coord
 * @property string $area
 * @property integer $t
 * @property string $slug
 * @property integer $old_id
 *
 * @property GeoZone $zoneModel
 * @property GeoCountry $countryModel
 */
class Geoname extends CActiveRecord
{
  public $start_lat = -90;
  public $start_lng = -90;
  public $end_lat = 90;
  public $end_lng = 90;

  public $name;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'geoname';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, area', 'required'),
			array('id, population, dia, t, zone, country, old_id', 'numerical', 'integerOnly'=>true),
			array('name_ru, name_en, slug', 'length', 'max'=>200),
			array('alternatenames', 'length', 'max'=>4000),
			array('latitude, longitude', 'length', 'max'=>12), 
			array('timezone', 'length', 'max'=>40),
			array('coord', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name_ru, name_en, alternatenames, latitude, longitude, country, zone, population, timezone, dia, coord, area, slug', 'safe', 'on'=>'search'),
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
      'zoneModel'    => array(self::BELONGS_TO, 'GeoZone',    'zone'),
      'countryModel' => array(self::BELONGS_TO, 'GeoCountry', 'country'),
      'seo'          => array(self::HAS_ONE,    'GeonameSeo', 'geoname_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name_ru' => 'Наименование (ru)',
			'name_en' => 'Наименование (en)',
			'alternatenames' => 'Альтернативные наименования',
			'latitude' => 'Широта',
			'longitude' => 'Долгота',
			'country' => 'Страна',
			'zone' => 'Область',
			'population' => 'Население',
			'timezone' => 'Времянная зона',
			'dia' => 'Приблизительный диаметр, км',
			'coord' => 'Координаты',
			'area' => 'Описывающий прямоугольник',
      't' => 'Название переведено?',
      'slug' => 'URL',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('name_ru',$this->name_ru,true);
		$criteria->compare('name_en',$this->name_en,true);
		$criteria->compare('alternatenames',$this->alternatenames,true);
		$criteria->compare('latitude',$this->latitude,true);
		$criteria->compare('longitude',$this->longitude,true);
		$criteria->compare('population',$this->population);
		$criteria->compare('timezone',$this->timezone,true);
		$criteria->compare('dia',$this->dia);
		$criteria->compare('coord',$this->coord,true);
		$criteria->compare('area',$this->area,true);
		$criteria->compare('t',$this->t,true);
		$criteria->compare('slug',$this->slug,true);

    if ($this->country) $criteria->compare('country',(int)$this->country, false);
    if ($this->zone)    $criteria->compare('zone',(int)$this->zone, false);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
      'Pagination' => array (
          'PageSize' => 50 //edit your number items per page here
      ),
		));
	}

  public function afterFind(){
    $this->name = $this->getAttribute('name_'.Yii::app()->language);
  }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Geoname the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

  public function scopes()
  {
    return array(
        'with_area' => array(
            'select' => array(
                '*',
                'Y(EndPoint(area)) end_lat',
                'X(EndPoint(area)) end_lng',
                'Y(StartPoint(area)) start_lat',
                'X(StartPoint(area)) start_lng'
            ),
            'order' => 'population DESC',
        )
    );
  }
}

/*

UPDATE geoname SET area = LINESTRING(
  POINT(longitude - (dia/2/ABS(COS(RADIANS(latitude)) * 111.2)),
  latitude + (dia/2/ 111.2)),
  POINT(longitude + (dia/2/ABS(COS(RADIANS(latitude)) * 111.2)),
  latitude - (dia/2/111.2))
)




*/
