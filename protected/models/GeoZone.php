<?php

/**
 * This is the model class for table "geozone".
 *
 * The followings are the available columns in table 'geozone':
 * @property integer $id
 * @property string $code
 * @property string $name_ru
 * @property string $name_en
 */
class GeoZone extends CActiveRecord
{
  public $name;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'geozone';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code', 'length', 'max'=>15),
			array('name_ru, name_en', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, name_ru, name_en', 'safe', 'on'=>'search'),
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
			'code' => 'Код',
			'name_ru' => 'Наименование (Ru)',
			'name_en' => 'Наименование (En)',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('name_ru',$this->name_ru,true);
		$criteria->compare('name_en',$this->name_en,true);

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
	 * @return GeoZone the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
