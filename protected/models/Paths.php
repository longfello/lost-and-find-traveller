<?php

/**
 * This is the model class for table "paths".
 *
 * The followings are the available columns in table 'paths':
 * @property integer $id_path
 * @property integer $id_settlement_1
 * @property integer $id_settlement_2
 * @property integer $distance
 * @property integer $time
 */
class Paths extends CActiveRecord
{
	public $settlement_search;
	public $empty_search;
	public $not_moderate_search;
	public $direction;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'paths';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_settlement_1, id_settlement_2', 'required'),
			array('id_settlement_1, id_settlement_2, distance, time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_path, id_settlement_1, id_settlement_2, distance, time, settlement_search, empty_search, not_moderate_search', 'safe', 'on'=>'search'),
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
			'startSettlement' => array(self::BELONGS_TO, 'Settlements', 'id_settlement_1'),
			'endSettlement' => array(self::BELONGS_TO, 'Settlements', 'id_settlement_2'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_path' => 'Id Path',
			'id_settlement_1' => 'Пункт 1',
			'id_settlement_2' => 'Пункт 2',
			'distance' => 'Расстояние (км)',
			'time' => 'Время (мин.)',
			'settlement_search' => 'Населённый пункт',
			'settlement_search_1' => 'Пункт 1',
			'settlement_search_2' => 'Пункт 2',
			'empty_search' => 'Без расстояния или времени',
			'not_moderate_search' => 'Требуют модерации',
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
		
		$criteria->with = array('startSettlement', 'endSettlement');
		$criteria->compare('startSettlement.name',$this->settlement_search, true, 'OR');
		$criteria->compare('endSettlement.name',$this->settlement_search, true, 'OR');
		
		if($this->empty_search) {
			$criteria->mergeWith(array('condition'=>'distance IS NULL OR time IS NULL'), 'AND');
		}
		
		if($this->not_moderate_search || !$_GET['ajax']) {
			$criteria->mergeWith(array('condition'=>'is_moderate = 0'), 'AND');
		}
		
		$criteria->mergeWith(array('condition'=>'(is_active = 1 OR is_moderate = 0)'), 'AND');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>50,
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Paths the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
		
	public function afterValidate()
	{
		$m_path = Paths::model()->getPathBySIDs($this->id_settlement_1, $this->id_settlement_2);
		if($m_path && $m_path->id_path != $this->id_path)
			$this->addError('id_path', 'Такой путь уже существует.');
	}
	
	public function getPathBySIDs($sid1, $sid2)
	{
		return Paths::model()->find('(id_settlement_1=:s1 AND id_settlement_2=:s2) OR (id_settlement_1=:s2 AND id_settlement_2=:s1)',array(':s1' => $sid1, ':s2' => $sid2));
	}
	
	public function findOrCreate()
	{
		$result = false;

		

		$m_path = $this->getPathBySIDs($this->id_settlement_1, $this->id_settlement_2);
		if(!$m_path) {
			$result = $this->save();//print $this->id_settlement_1;die();
			$this->direction = 0;
		} else {
			$result = true;
			$this->id_path = $m_path->id_path; 
			$this->direction = $m_path->id_settlement_1 == $this->id_settlement_1 ? 0 : 1;
		}
		return $result;
	}
}
