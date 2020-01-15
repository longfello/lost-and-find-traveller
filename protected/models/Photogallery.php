<?php

/**
 * This is the model class for table "photogallery".
 *
 * The followings are the available columns in table 'photogallery':
 * @property integer $id_ph
 * @property string $path
 *
 * The followings are the available model relations:
 * @property HotelPhotoList[] $hotelPhotoLists
 */
class Photogallery extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'photogallery';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('path', 'required'),
			array('path', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_ph, path, cover', 'safe', 'on'=>'search'),
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

            'idHs' => array(self::BELONGS_TO, 'HotelService', 'id_hs'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_ph' => 'Id Ph',
			'path' => 'Path',
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

		$criteria->compare('id_ph',$this->id_ph);
		$criteria->compare('path',$this->path,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Photogallery the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
