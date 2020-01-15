<?php

/**
 * This is the model class for table "hotel_service".
 *
 * The followings are the available columns in table 'hotel_service':
 * @property integer $id_hs
 * @property string $title
 * @property string $address
 * @property string $place_desc
 * @property string $description
 * @property string $phones
 * @property string $site_link
 * @property double $coord_x
 * @property double $coord_y
 * @property integer $type
 * @property integer $guest_rating
 * @property integer $stars_rating
 * @property double $square
 * @property integer $price_from
 * @property integer $price_to
 *
 * The followings are the available model relations:
 * @property HotelPhotoList[] $hotelPhotoLists
 * @property HotelRoomsList[] $hotelRoomsLists
 * @property HotelServiceList[] $hotelServiceLists
 */
class HotelService extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'hotel_service';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user, title, address,    type, id_settlement', 'required'),
			array('id_user, type, stars_rating, price_from, price_to, operator,  guest_rating_positive,guest_rating_negative, id_settlement,  status', 'numerical', 'integerOnly'=>true),
			array('coord_x, coord_y, square', 'numerical'),
			array('title', 'length', 'max'=>250),
			array('address, site_link', 'length', 'max'=>300),
			array('place_desc, description', 'length', 'max'=>1000),
			array('phones', 'length', 'max'=>200),
			array('activecode', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_user, id_hs, title,  id_settlement, address, place_desc, description, phones, site_link, coord_x, coord_y, type, guest_rating_positive,guest_rating_negative, stars_rating, square, price_from, price_to,operator, status, activecode', 'safe', 'on'=>'search'),
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
			'hotelPhotoLists_cover' => array(self::HAS_MANY, 'HotelPhotoList', 'id_hs',  'on'=>'cover = 1 '),
			'hotelPhotoLists' => array(self::HAS_MANY, 'HotelPhotoList', 'id_hs'),
			'hotelRoomsLists' => array(self::MANY_MANY, 'RoomType', ' hotel_rooms_list(id_rt, id_hs)'),
			'hotelServiceLists' => array(self::MANY_MANY, 'ServicesList', ' hotel_service_list(id_sl, id_hs)','order'=>'hotelServiceLists.name ASC'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_hs' => 'Id Hs',
			'title' => 'Название',
			'address' => 'Адрес',
			'place_desc' => 'Описание<br>места расположения',
			'description' => 'Дополнительная информация',
			'phones' => 'Телефон',
			'site_link' => 'Ссылка на сайт',
			'coord_x' => 'Coord X',
			'coord_y' => 'Coord Y',
			'type' => 'Type',		
			'stars_rating' => 'Рейтинг',
			'square' => 'Площадь',
			'price_from' => 'Цена от',
			'price_to' => 'Цена до',
			'id_settlement' => 'Населенный пункт',
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

		$criteria->compare('id_hs',$this->id_hs);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('place_desc',$this->place_desc,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('phones',$this->phones,true);
		$criteria->compare('site_link',$this->site_link,true);
		$criteria->compare('coord_x',$this->coord_x);
		$criteria->compare('coord_y',$this->coord_y);
		$criteria->compare('type',$this->type);
		$criteria->compare('guest_rating_positive,',$this->guest_rating_positive);
		$criteria->compare('guest_rating_negative,',$this->guest_rating_negative);
		$criteria->compare('stars_rating',$this->stars_rating);
		$criteria->compare('square',$this->square);
		$criteria->compare('price_from',$this->price_from);
		$criteria->compare('price_to',$this->price_to);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HotelService the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	
	public function beforeDelete(){
	
	
			$command = Yii::app()->db->createCommand();
			$command->delete('hotel_service_list', 'id_hs=:id_hs', array(':id_hs'=>$this->id_hs));
			$command->reset(); 
		
			$command->delete('hotel_rooms_list', 'id_hs=:id_hs', array(':id_hs'=>$this->id_hs));
			$command->reset(); 
		
			$command->delete('photogallery', 'id_hs=:id_hs', array(':id_hs'=>$this->id_hs));
			$command->reset();  

		 return parent::beforeDelete();
} 


    public function delete() {

        return parent::delete();
    }




    protected function afterSave() {

        parent::afterSave();

        $insert_arr =  array();
        $insert_photo_arr =  array();

		
	
	
		
		
		
        if($_POST['services'] ) {
			$command = Yii::app()->db->createCommand();
			$command->delete('hotel_service_list', 'id_hs=:id_hs', array(':id_hs'=>$this->id_hs));
			$command->reset(); 
		
            foreach( $_POST['services'] as $service ){
                $insert_arr[] = array("id_hs"=>$this->id_hs, "id_sl"=>substr( $service, 3  ) );
            }
			
            $builder = Yii::app()->db->schema->commandBuilder;
            $command=$builder->createMultipleInsertCommand('hotel_service_list',    $insert_arr);
            $command->execute();
        }
		$insert_arr =  array();
	
	
        if($_POST['rooms'] ) {
			$command = Yii::app()->db->createCommand();
			$command->delete('hotel_rooms_list', 'id_hs=:id_hs', array(':id_hs'=>$this->id_hs));
			$command->reset(); 
		}	
	
        if($_POST['rooms'] && $_POST['HotelService']['type']!=2 ) {
		
		
		
            foreach( $_POST['rooms'] as $service ){
                $insert_arr[] = array("id_hs"=>$this->id_hs, "id_rt"=>substr( $service, 4  ) );
            }
		
            $builder = Yii::app()->db->schema->commandBuilder;
            $command=$builder->createMultipleInsertCommand('hotel_rooms_list',    $insert_arr);
            $command->execute();
        }
			$insert_arr =  array();
		
        if($_POST['places'] && $_POST['HotelService']['type']==2) {
           
            $insert_arr[] = array("id_hs"=>$this->id_hs, "id_rt"=>$_POST['places'] );
         
		    $builder = Yii::app()->db->schema->commandBuilder;
            $command=$builder->createMultipleInsertCommand('hotel_rooms_list',    $insert_arr);
            $command->execute();
        }
	
        if($_POST['attachments_photo'] ) {
			$command = Yii::app()->db->createCommand();
			$command->delete('photogallery', 'id_hs=:id_hs', array(':id_hs'=>$this->id_hs));
			$command->reset(); 
		
		
            foreach( $_POST['attachments_photo'] as $photo ){
               
                $from = '/files/hotelservice/tmp/'.$photo;
				$to = '/files/hotelservice/'.substr(md5($this->id_hs), 0, 2).'/';
				
                if(file_exists($_SERVER['DOCUMENT_ROOT'].$from) ){              
                    if(!is_dir($_SERVER['DOCUMENT_ROOT'].$to)) mkdir($_SERVER['DOCUMENT_ROOT'].$to, 0777, true);
                    $to .= $photo;
                    rename(  $_SERVER['DOCUMENT_ROOT'].$from, $_SERVER['DOCUMENT_ROOT'].$to );
					$insert_photo_arr [] = array("id_hs"=>$this->id_hs, "path"=>$to,"cover"=>($photo ==  $_POST["cover"]) );
                } else if(file_exists($_SERVER['DOCUMENT_ROOT'].$to.$photo) ){ 
				    $to .= $photo;
				    $insert_photo_arr [] = array("id_hs"=>$this->id_hs, "path"=>$to,"cover"=>($photo ==  $_POST["cover"]) );				
				}

             
            }
				
            $builder = Yii::app()->db->schema->commandBuilder;
            $command=$builder->createMultipleInsertCommand('photogallery',    $insert_photo_arr);
            $command->execute();

	
        }

    }
	
	public function beforeValidate()
	{
	
		
		if(!$this->activecode) $this->activecode = rand(1000, 9999);	
		$cu = Yii::app()->user;
		
		if($cu->checkAccess('operator')) {
			$this->activecode = 0;
			$this->status = 1;
			$this->operator = $cu->id;
		}else $this->status = 0;
        //
	      //	$this->status = 1;
		

		
		if(!$this->id_hs) {  
			if(!$cu->id || $cu->checkAccess('operator')) {
				$user = User::model()->find(array('condition'=>'username=:username', 
						'params'=>array(':username'=>$_POST['phone'])));
				if(!$user) {
				
					$user = new User;
					$user->username = preg_replace("([^0-9])", "", $_POST['phone']);
					$user->activkey = Yii::app()->epassgen->generate(7, 0, 3, 0);
					$user->password = UserModule::encrypting($user->activkey);
					$user->superuser = 0;
					$user->status = 0;
					if(!$user->save(false)) { $this->addError('id_hs', Yii::t('common', 'Произошла внутренняя ошибка сервера. Пожалуйста попробуйте ещё раз.')); return false; }
					$profile = new Profile;
					$profile->user_id = $user->id;
					$profile->save();
					
				}
				if($user->status == 0 && $cu->checkAccess('operator')) 
					if(!Yii::app()->general->activateUser($user)) { Yii::t('common', 'Произошла внутренняя ошибка сервера. Пожалуйста попробуйте ещё раз.'); return false; };
			} else { $user = $cu; $this->activecode = 0; }
			$this->id_user = $user->id;
	
		}return true;
	}
	

	
}
