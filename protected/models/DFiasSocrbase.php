<?php

/**
 * This is the model class for table "d_fias_socrbase".
 *
 * The followings are the available columns in table 'd_fias_socrbase':
 * @property integer $kod_t_st
 * @property integer $level
 * @property string $scname
 * @property string $socrname
 *
 * The followings are the available model relations:
 * @property Areas[] $areases
 * @property Regions[] $regions
 * @property Settlements[] $settlements
 */
class DFiasSocrbase extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'd_fias_socrbase';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('kod_t_st, level, scname, socrname', 'required'),
			array('kod_t_st, level', 'numerical', 'integerOnly'=>true),
			array('scname', 'length', 'max'=>10),
			array('socrname', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('kod_t_st, level, scname, socrname', 'safe', 'on'=>'search'),
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
			'areases' => array(self::HAS_MANY, 'Areas', 'kod_t_st'),
			'regions' => array(self::HAS_MANY, 'Regions', 'kod_t_st'),
			'settlements' => array(self::HAS_MANY, 'Settlements', 'kod_t_st'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'kod_t_st' => 'Ключевое поле',
			'level' => 'Уровень адресного объекта',
			'scname' => 'Краткое наименование типа объекта',
			'socrname' => 'Полное наименование типа объекта',
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

		$criteria->compare('kod_t_st',$this->kod_t_st);
		$criteria->compare('level',$this->level);
		$criteria->compare('scname',$this->scname,true);
		$criteria->compare('socrname',$this->socrname,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DFiasSocrbase the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
