<?php

/**
 * This is the model class for table "auto_models".
 *
 * The followings are the available columns in table 'auto_models':
 * @property integer $id_model
 * @property integer $id_brand
 * @property integer $id_type
 * @property string $model
 * @property string $class

 * @property AutoBrands $brand
 * @property AutoTypes  $type
 */
class AutoModels extends CActiveRecord
{
	public $brand_search;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'auto_models';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_brand', 'required'),
			array('id_brand, id_type', 'numerical', 'integerOnly'=>true),
			array('model', 'length', 'max'=>20),
			array('class', 'in', 'range' => EnumAutoClasses::getValidValues()),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_model, id_brand, id_type, model, brand_search', 'safe', 'on'=>'search'),
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
			'brand' => array(self::BELONGS_TO, 'AutoBrands', 'id_brand'),
			'type' => array(self::BELONGS_TO, 'AutoTypes', 'id_type'),
		);
	}

  public static function getClassesList(){
    return EnumAutoClasses::getValidValues();
  }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_model' => 'ID',
			'id_brand' => 'Марка',
			'id_type' => 'Тип',
			'brand_search' => 'Бренд',
			'model' => 'Модель',
			'class' => 'Класс',
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
		
		$criteria->with = array('brand');

		$criteria->compare('id_model',$this->id_model);
		$criteria->compare('brand.brand',$this->brand_search);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('class',$this->class);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'brand_search'=>array(
						'asc'=>'brand.brand',
						'desc'=>'brand.brand DESC',
						),
				'*',
				),
			),
			'pagination'=>array(
				'pageSize'=>50,
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AutoModels the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
