<?php

/**
 * This is the model class for table "hotel_rooms_list".
 *
 * The followings are the available columns in table 'hotel_rooms_list':
 * @property integer $id_hs
 * @property integer $id_rt
 *
 * The followings are the available model relations:
 * @property RoomType $idRt
 * @property HotelService $idHs
 */
class HotelRoomsList extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'hotel_rooms_list';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{

	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{

	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{

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

		$criteria->compare('id_hs',$this->id_hs);
		$criteria->compare('id_rt',$this->id_rt);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HotelRoomsList the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
