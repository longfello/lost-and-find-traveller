<?php

/**
 * This is the model class for table "lost_found".
 *
 * The followings are the available columns in table 'lost_found':
 * @property integer $id_lf
 * @property integer $id_user
 * @property integer $category
 * @property integer $lost_or_found
 * @property integer $city
 * @property string $place_disc
 * @property string $thing
 * @property integer $operator
 * @property integer $status
 * @property string $date_filing
 * @property string $date_lf
 * @property string $comment
 * @property string $phone
 * @property string $active_code
 */
class LostFound extends CActiveRecord
{
 public $flag=1;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lost_found';
	}
 
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user, category, lost_or_found, id_settlement,  thing, date_lf,  phone', 'required'),
			array('photo', 'safe'),
			array('id_sa_settlement', 'safe'),
			array('id_user, category, lost_or_found, id_settlement, operator, status', 'numerical', 'integerOnly'=>true),
			array('place_disc', 'length', 'max'=>200),
			array('comment', 'length', 'max'=>500),
			array('phone', 'length', 'max'=>15),
			array('activecode', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_lf, id_user, photo, category, lost_or_found, id_settlement, id_sa_settlement, place_disc, thing, operator, status, date_filing, date_lf, comment, phone, activecode', 'safe', 'on'=>'search'),
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
					'idSaSettlement' => array(self::BELONGS_TO, 'SAreas', 'id_sa_settlement'), 
					'idCategory' => array(self::BELONGS_TO, 'Category', 'category'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_lf' => 'Id Lf',
			'id_user' => 'Id User',
			'category' => 'Категория',
			'lost_or_found' => 'Lost Or Found',
			'id_settlement' => 'Город',
			'place_disc' => 'Место / ориентир',
			'thing' => 'Объект',
			'operator' => 'Operator',
			'status' => 'Status',
			'date_filing' => 'Date Filing',
			'date_lf' => 'Дата',
			'comment' => 'Описание',
			'id_sa_settlement' => 'Район',
			'phone' => 'Телефон',
			'activecode' => 'Active Code',
			'photo' => 'Фотография',
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
	 public function beforeValidate(){
		
		$this->flag = 1;
		$this->date_filing = date("Y-m-d"); 
		$this->date_lf = date('Y-m-d', strtotime($this->date_lf));
		if(!$this->activecode) $this->activecode = rand(1000, 9999);	
		
		$cu = Yii::app()->user;
		
		if($cu->checkAccess('operator')) {
			$this->activecode = 0;
			$this->status = 1;
			$this->operator = $cu->id;
		}
        
		if(!$this->id_lf) {
			if(!$cu->id || $cu->checkAccess('operator')) {
				$user = User::model()->find(array('condition'=>'username=:username', 
						'params'=>array(':username'=>$this->phone)));
				if(!$user) {
					$user = new User;
					$user->username = preg_replace("([^0-9])", "", $this->phone);
					$user->activkey = Yii::app()->epassgen->generate(7, 0, 3, 0);
					$user->password = UserModule::encrypting($user->activkey);
					$user->superuser = 0;
					$user->status = 0;
					if(!$user->save(false)) { $this->addError('id_lf', Yii::t('common', 'Произошла внутренняя ошибка сервера. Пожалуйста попробуйте ещё раз.')); return false; }
					$profile = new Profile;
					$profile->user_id = $user->id;
					$profile->first_name = $_POST['first_name'];
					$profile->second_name = $_POST['second_name'];
					$profile->save();
					
				}
				if ($user->status == 0 && $cu->checkAccess('operator')) {
					$user->status = 1;
					if(!$user->save()) {
						
						Yii::t('common', 'Произошла внутренняя ошибка сервера. Пожалуйста попробуйте ещё раз.2');
						return false;
					};
					/*if (!Yii::app()->general->activateUser($user)) {
						
						Yii::t('common', 'Произошла внутренняя ошибка сервера. Пожалуйста попробуйте ещё раз.2');
						return false;
					};*/
				}
			} else { $user = $cu; $this->activecode = 0; }
			$this->id_user = $user->id;
			
		}return true;
	}
	protected function afterSave() {
		
			parent::afterSave();
		
		 	if($_FILES['LostFound'] && $_FILES['LostFound']['error']['photo'] == UPLOAD_ERR_OK) {
				
				$ext = end(explode('.', strtolower($_FILES['LostFound']['name']['photo'])));	
				$from = '/files/lostfound/tmp/'.$this->id_user.'.'.$ext;
				if(file_exists($_SERVER['DOCUMENT_ROOT'].$from) ){
					$to = '/files/lostfound/'.substr(md5($this->id_lf), 0, 2).'/';				
					if(!is_dir($_SERVER['DOCUMENT_ROOT'].$to)) mkdir($_SERVER['DOCUMENT_ROOT'].$to, 0777, true);				
					$to .= $this->id_lf.'.'.$ext;
					rename(  $_SERVER['DOCUMENT_ROOT'].$from, $_SERVER['DOCUMENT_ROOT'].$to );
					if ($this->isNewRecord) {					  
					  	
						$this->isNewRecord = false;	
					}
					$this->saveAttributes(array('photo'=>$to));
			
				}
			}			
		
	}
		protected function beforeSave() {
		
			if(parent::beforeSave()){
			
				if($_FILES['LostFound']) {
				
					if($_FILES['LostFound']['error']['photo'] == UPLOAD_ERR_OK) {
				
						$allowedExt = array('jpg', 'jpeg', 'png', 'gif');
						$ext = end(explode('.', strtolower($_FILES['LostFound']['name']['photo'])));
						if (!in_array($ext, $allowedExt)) { $this->addError('photo', Yii::t('common', 'Некорректное фото')); return false; }
						$tmp_name = $_FILES['LostFound']["tmp_name"]['photo'];
						$name = '/files/lostfound/tmp/';
						if(!is_dir($_SERVER['DOCUMENT_ROOT'].$name)) mkdir($_SERVER['DOCUMENT_ROOT'].$name, 0777, true);
						$name .= $this->id_user.'.'.$ext;
						if(!file_exists($_SERVER['DOCUMENT_ROOT'].$name) ){
							@unlink($_SERVER['DOCUMENT_ROOT'].$name);
							if(@move_uploaded_file($tmp_name, $_SERVER['DOCUMENT_ROOT'].$name)) {
								
								list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'].$name);
								if($width > 1024 || $height > 1024) {
									$image = new EasyImage($name);
									$image->resize(1024, 1024);
									$image->save($_SERVER['DOCUMENT_ROOT'].$name);
								}						
							}
							else { $this->addError('photo', Yii::t('common', 'Ошибка загрузки фото')); return false; }
						}
					}
				}
				return true;
			}else return false;
		   //$this->date_from = date('Y-m-d', strtotime($this->date_from));
	}
	 public function afterFind()	{
		parent::afterFind();
		$this->date_lf = date('d.m.y', strtotime($this->date_lf));
	}
	public function search(){
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id_lf',$this->id_lf);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('category',$this->category);
		$criteria->compare('lost_or_found',$this->lost_or_found);
		$criteria->compare('id_settlement',$this->id_settlement);
		$criteria->compare('place_disc',$this->place_disc,true);
		$criteria->compare('thing',$this->thing,true);
		$criteria->compare('operator',$this->operator);
		$criteria->compare('status',$this->status);
		$criteria->compare('date_filing',$this->date_filing,true);
		$criteria->compare('date_lf',$this->date_lf,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('activecode',$this->activecode,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LostFound the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}
