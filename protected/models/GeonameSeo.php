<?php

/**
 * This is the model class for table "geoname_seo".
 *
 * The followings are the available columns in table 'geoname_seo':
 * @property integer $id
 * @property integer $geoname_id
 * @property string $seo_text
 * @property string $seo_text_top
 * @property string $title
 * @property string $description
 * @property string $keywords
 */
class GeonameSeo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'geoname_seo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('geoname_id', 'required'),
			array('geoname_id', 'numerical', 'integerOnly'=>true),
			array('title, description, keywords', 'length', 'max'=>255),
			array('seo_text, seo_text_top', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, geoname_id, seo_text, seo_text_top, title, description, keywords', 'safe', 'on'=>'search'),
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
			'id'            => 'ID',
			'geoname_id'    => 'Geoname',
			'seo_text'      => 'Seo текст',
			'seo_text_top'  => 'Seo верхний текст',
			'title'         => 'Title',
			'description'   => 'Description',
			'keywords'      => 'Keywords',
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
		$criteria->compare('geoname_id',$this->geoname_id);
		$criteria->compare('seo_text',$this->seo_text,true);
		$criteria->compare('seo_text_top',$this->seo_text_top,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('keywords',$this->keywords,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GeonameSeo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
