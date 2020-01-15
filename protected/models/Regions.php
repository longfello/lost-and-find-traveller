<?php

/**
 * This is the model class for table "regions".
 *
 * The followings are the available columns in table 'regions':
 * @property integer $id_region
 * @property string $name
 * @property string $aoid
 * @property integer $kod_t_st
 * @property integer $id_country
 *
 * The followings are the available model relations:
 * @property Areas[] $areases
 * @property DFiasSocrbase $kodTSt
 * @property Countries $idCountry
 * @property Settlements[] $settlements
 */
class Regions extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'regions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_country', 'required'),
			array('kod_t_st, id_country', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>120),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_region, name, aoid, kod_t_st, id_country', 'safe', 'on'=>'search'),
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
			'areases' => array(self::HAS_MANY, 'Areas', 'id_region'),
			'kodTSt' => array(self::BELONGS_TO, 'DFiasSocrbase', 'kod_t_st'),
			'idCountry' => array(self::BELONGS_TO, 'Countries', 'id_country'),
			'settlements' => array(self::HAS_MANY, 'Settlements', 'id_region'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_region' => 'ИД региона',
			'name' => 'Название',
			'kod_t_st' => 'Тип региона',
			'aoid' => 'ФИАС ИД',
			'id_country' => 'Страна',
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

		$criteria->compare('id_region',$this->id_region);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('aoid',$this->aoid,true);
		$criteria->compare('kod_t_st',$this->kod_t_st);
		$criteria->compare('id_country',$this->id_country);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Regions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
