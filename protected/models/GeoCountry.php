<?php

/**
 * This is the model class for table "geocountry".
 *
 * The followings are the available columns in table 'geocountry':
 * @property integer $id
 * @property string $iso_alpha2
 * @property string $iso_alpha3
 * @property integer $iso_numeric
 * @property string $fips_code
 * @property string $name_ru
 * @property string $name_en
 * @property string $capital
 * @property double $areainsqkm
 * @property integer $population
 * @property string $continent
 * @property string $tld
 * @property string $currency
 * @property string $currencyName
 * @property string $Phone
 * @property string $postalCodeFormat
 * @property string $postalCodeRegex
 * @property integer $geonameId
 * @property string $languages
 * @property string $neighbours
 */
class GeoCountry extends CActiveRecord
{
  public $name;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'geocountry';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iso_numeric, population, geonameId', 'numerical', 'integerOnly'=>true),
			array('areainsqkm', 'numerical'),
			array('iso_alpha2, continent', 'length', 'max'=>2),
			array('iso_alpha3, fips_code, tld, currency', 'length', 'max'=>3),
			array('name_ru, name_en, capital, languages', 'length', 'max'=>200),
			array('currencyName', 'length', 'max'=>20),
			array('Phone', 'length', 'max'=>10),
			array('postalCodeFormat, neighbours', 'length', 'max'=>100),
			array('postalCodeRegex', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, iso_alpha2, iso_alpha3, iso_numeric, fips_code, name_ru, name_en, capital, areainsqkm, population, continent, tld, currency, currencyName, Phone, postalCodeFormat, postalCodeRegex, geonameId, languages, neighbours', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'iso_alpha2' => 'ISO Alpha2',
			'iso_alpha3' => 'ISO Alpha3',
			'iso_numeric' => 'ISO код',
			'fips_code' => 'FIPS код',
			'name_ru' => 'Название (Ru)',
			'name_en' => 'Название (En)',
			'capital' => 'Столица',
			'areainsqkm' => 'Площадь, кв.км.',
			'population' => 'Население',
			'continent' => 'Континент',
			'tld' => 'Домен',
			'currency' => 'Валюта',
			'currencyName' => 'Название валюты',
			'Phone' => 'Телефонный код',
			'postalCodeFormat' => 'Формат почтового индекса',
			'postalCodeRegex' => 'Рег.віражение проверки почтового кода',
			'geonameId' => 'ID в таблице geoname',
			'languages' => 'Языки',
			'neighbours' => 'Соседи',
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
		$criteria->compare('iso_alpha2',$this->iso_alpha2,true);
		$criteria->compare('iso_alpha3',$this->iso_alpha3,true);
		$criteria->compare('iso_numeric',$this->iso_numeric);
		$criteria->compare('fips_code',$this->fips_code,true);
		$criteria->compare('name_ru',$this->name_ru,true);
		$criteria->compare('name_en',$this->name_en,true);
		$criteria->compare('capital',$this->capital,true);
		$criteria->compare('areainsqkm',$this->areainsqkm);
		$criteria->compare('population',$this->population);
		$criteria->compare('continent',$this->continent,true);
		$criteria->compare('tld',$this->tld,true);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('currencyName',$this->currencyName,true);
		$criteria->compare('Phone',$this->Phone,true);
		$criteria->compare('postalCodeFormat',$this->postalCodeFormat,true);
		$criteria->compare('postalCodeRegex',$this->postalCodeRegex,true);
		$criteria->compare('geonameId',$this->geonameId);
		$criteria->compare('languages',$this->languages,true);
		$criteria->compare('neighbours',$this->neighbours,true);

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
	 * @return GeoCountry the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
