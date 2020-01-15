<?php

/**
 * This is the model class for table "settlements".
 *
 * The followings are the available columns in table 'settlements':
 * @property integer $id_settlement
 * @property string $name
 * @property string $aoid
 * @property integer $id_region
 * @property integer $id_area
 * @property integer $kod_t_st
 *
 * The followings are the available model relations:
 * @property SAreas[] $sAreases
 * @property Regions $idRegion
 * @property Areas $idArea
 * @property DFiasSocrbase $kodTSt
 */
class Settlements extends CActiveRecord
{
	public $region_search;
	public $area_search;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'settlements';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		
			array('id_region, id_area, kod_t_st', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>120),
			array('aoid', 'length', 'max'=>36),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_settlement, name, aoid, id_region, id_area, kod_t_st, region_search, area_search', 'safe', 'on'=>'search'),
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
			'sAreases' => array(self::HAS_MANY, 'SAreas', 'id_settlement'),
			'idRegion' => array(self::BELONGS_TO, 'Regions', 'id_region'),
			'idArea' => array(self::BELONGS_TO, 'Areas', 'id_area'),
			'kodTSt' => array(self::BELONGS_TO, 'DFiasSocrbase', 'kod_t_st'),
                        'seocity' => array(self::HAS_MANY, 'SeoCity', 'city_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_settlement' => 'ИД',
			'name' => 'Название',
			'aoid' => 'ФИАС ИД',
			'id_region' => 'Регион',
			'id_area' => 'Район',
			'kod_t_st' => 'Тип',
			'region_search' => 'Регион',
			'area_search' => 'Район',
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
		
		$criteria->with = array('idRegion');

		$criteria->compare('id_settlement',$this->id_settlement);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('aoid',$this->aoid,true);
		$criteria->compare('id_region',$this->id_region);
		$criteria->compare('id_area',$this->id_area);
		$criteria->compare('kod_t_st',$this->kod_t_st);
		$criteria->compare('idRegion.name', $this->region_search, true);
		$criteria->compare('idArea.name', $this->area_search, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'region_search'=>array(
						'asc'=>'idRegion.name',
						'desc'=>'idRegion.name DESC',
						),
					'area_search'=>array(
						'asc'=>'idArea.name',
						'desc'=>'idArea.name DESC',
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
	 * @return Settlements the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function getSIDsByName($name)
	{
		$cond .= "t.name LIKE :term";
		$items = Settlements::model()->findAll($cond, array(':term' => $name.'%'));
		$result = array();
		foreach($items as $item) {
			$result[] = $item['id_settlement'];
		}
		return implode(', ', $result);
	}
	
	public function getSettlementFullname()
	{
		$fullname = $this->name.'<br /><span class="desc">';
		$fullname .= $this->kodTSt->socrname.", ";
		if($this->idArea) $fullname .= $this->idArea->name . ' ' . mb_strtolower($this->idArea->kodTSt->socrname,'UTF-8') .', ';
		$fullname .= $this->idRegion->name . ' ' . mb_strtolower($this->idRegion->kodTSt->socrname,'UTF-8');
		return $fullname;
	}
}
