<?php

/**
 * This is the model class for table "lost_service".
 *
 * The followings are the available columns in table 'lost_service':
 * @property integer $id_ls
 * @property string $name
 * @property integer $contact_phone
 * @property integer $id_thing
 */
class LostService extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	 public $verifyCode;
	public function tableName()
	{
		return 'lost_service';
	}

	/**
	 * @return array validation rules for model attributes.
	 */

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('verifyCode','captcha','allowEmpty'=>!Yii::app()->user->isGuest || !CCaptcha::checkRequirements(), ),
			array('name, contact_phone,verifyCode, id_thing', 'required'),
			array('name', 'length', 'max'=>20),
			array('comment, status', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_ls, name, contact_phone, id_thing, comment', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'id_thing'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'verifyCode'=>'Решите пример',
			'id_thing' =>  Yii::t('lostservice', 'Идентификационный номер'),
			'contact_phone' =>  Yii::t('lostservice', 'Контактный номер'),
			'name' =>  Yii::t('lostservice', 'Ваше имя'),		
			'comment' =>  Yii::t('lostservice', 'Комментарий'),
		
			
			
		
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

		$criteria->compare('id_ls',$this->id_ls);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('contact_phone',$this->contact_phone);
		$criteria->compare('id_thing',$this->id_thing);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function afterValidate()
	{
		if( isset($_POST['LostService'])){
			
			$user = User::model()->find(array('condition'=>'id=:id', 'params'=>array(':id'=>Yii::app()->general->idthing_to_userid($_POST['LostService']['id_thing']) )));
			
			if(!$user){
			
				$this->addError('id_thing','Данный id не найден.');  
			}

		}
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LostService the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
