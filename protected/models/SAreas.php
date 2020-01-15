<?php

/**
 * This is the model class for table "s_areas".
 *
 * The followings are the available columns in table 's_areas':
 * @property integer $id_sa
 * @property string $name
 * @property integer $id_settlement
 *
 * The followings are the available model relations:
 * @property Settlements $idSettlement
 */
class SAreas extends CActiveRecord
{
	public $settlement_search;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 's_areas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_settlement', 'required'),
			array('id_settlement', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_sa, name, id_settlement, settlement_search', 'safe', 'on'=>'search'),
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
			'idSettlement' => array(self::BELONGS_TO, 'Settlements', 'id_settlement'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_sa' => 'ИД',
			'name' => 'Название',
			'id_settlement' => 'Город',
			'settlement_search' => 'Город',
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
		
		$criteria->with = array('idSettlement');
		
		$criteria->compare('id_sa',$this->id_sa);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('id_settlement',$this->id_settlement);
		$criteria->compare('idSettlement.name', $this->settlement_search, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'settlement_search'=>array(
						'asc'=>'idSettlement.name',
						'desc'=>'idSettlement.name DESC',
						),
				'*',
				),
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SAreas the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
