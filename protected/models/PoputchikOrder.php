<?php

/**
 * This is the model class for table "poputchik_order".
 *
 * The followings are the available columns in table 'poputchik_order':
 * @property integer $id_order
 * @property integer $type_order
 * @property integer $target
 * @property integer $type_route
 * @property integer $id_route
 * @property integer $id_path
 * @property integer $transit
 * @property integer $direction
 * @property integer $id_settlement
 * @property integer $id_sa_start
 * @property integer $id_sa_end
 * @property string $from_place
 * @property string $to_place
 * @property integer $sum
 * @property integer $type_sum
 * @property integer $type_time
 * @property string $date_to
 * @property string $time_from_1
 * @property string $time_from_2
 * @property string $time_to_1
 * @property string $time_to_2
 * @property integer $reverse
 * @property string $date_reverse
 * @property integer $date_reverse_offset
 * @property string $time_r_from_1
 * @property string $time_r_from_2
 * @property string $time_r_to_1
 * @property string $time_r_to_2
 * @property string $name
 * @property string $phone
 * @property integer $type_auto
 * @property integer $id_brand
 * @property integer $id_model
 * @property integer $free_places_count
 * @property string $comment
 * @property string $date_order
 * @property string $date_available
 * @property integer $id_user
 * @property integer $operator
 */
class PoputchikOrder extends CActiveRecord {

    public $days_of_week_tmp = array();
    public $days_of_month_tmp = array();
    public $rim_sp_sid = array();
    public $settlement_name_1;
    public $settlement_name_2;
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'poputchik_order';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
       
        return array(
            
            array('id_brand, id_model, name, phone', 'required'),
            array('type_order, target, type_route, id_route, id_path, transit, direction, id_settlement, id_sa_start, id_sa_end, sum, type_sum, type_time, reverse, date_reverse_offset, type_auto, id_brand, id_model, free_places_count, id_user, operator', 'numerical', 'integerOnly' => true),
            array('from_place, to_place', 'length', 'max' => 100),
            array('date_from, date_to, date_available', 'date', 'format' => 'dd.MM.yyyy'),
            array('date_reverse, date_reverse_to', 'dateFixValidate', 'format' => 'dd.MM.yyyy'),
            array('name', 'length', 'max' => 50),
            array('phone', 'length', 'max' => 20),
            array('date_reverse, date_reverse_to, time_r_from_1, time_r_from_2, time_r_to_1, time_r_to_2, date_available', 'default', 'setOnEmpty' => true, 'value' => null),
            array('date_from, date_to, time_from_1, time_from_2, time_to_1, time_to_2, date_reverse, time_r_from_1, time_r_from_2, time_r_to_1, time_r_to_2, comment, date_order, date_available', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id_order, type_order, target, type_route, id_route, id_path, transit, direction, id_settlement, id_sa_start, id_sa_end, from_place, to_place, sum, type_sum, type_time, date_to, time_from_1, time_from_2, time_to_1, time_to_2, reverse, date_reverse, date_reverse_offset, time_r_from_1, time_r_from_2, time_r_to_1, time_r_to_2, name, phone, type_auto, id_brand, id_model, free_places_count, comment, date_order, date_available, id_user, operator', 'safe', 'on' => 'search'),
            
            );
    }

    public function dateFixValidate ($attribute,$params){
        if($this->reverse == 1 && $this->type_time == 1){
            $timestamp=CDateTimeParser::parse($this->$attribute,$params['format'],array('month'=>1,'day'=>1,'hour'=>0,'minute'=>0,'second'=>0));
			if($timestamp==false)
			{
                            $this->addError($attribute, 'Не верный формат даты.');
                        }
        }
            
    }
    
    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'idSettlement' => array(self::BELONGS_TO, 'Settlements', 'id_settlement'),
            'idSaStart' => array(self::BELONGS_TO, 'SAreas', 'id_sa_start'),
            'idSaEnd' => array(self::BELONGS_TO, 'SAreas', 'id_sa_end'),
            'route' => array(self::BELONGS_TO, 'Routes', 'id_route'),
            'path' => array(self::BELONGS_TO, 'Paths', 'id_path'),
            'rims_sp' => array(self::HAS_MANY, 'PoputchikOrderSettlements', 'id_order'),
            'days_of_week' => array(self::HAS_MANY, 'DaysOfWeek', 'id_order', 'order' => 'days_of_week.day ASC'),
            'days_of_month' => array(self::HAS_MANY, 'DaysOfMonth', 'id_order', 'order' => 'days_of_month.day ASC'),
            'brand' => array(self::BELONGS_TO, 'AutoBrands', 'id_brand'),
            'idModel' => array(self::BELONGS_TO, 'AutoModels', 'id_model'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id_order' => 'Id Order',
            'type_order' => Yii::t('poputchik', 'Вы'),
            'target' => Yii::t('poputchik', 'Цель поездки'),
            'type_route' => Yii::t('poputchik', 'Тип поездки'),
            'id_route' => 'Id Route',
            'id_path' => 'Id Path',
            'transit' => Yii::t('poputchik', 'Возможен заезд в другие города по пути следования'),
            'direction' => 'Direction',
            'id_settlement' => 'Id Settlement',
            'id_sa_start' => Yii::t('poputchik', 'Район'),
            'id_sa_end' => Yii::t('poputchik', 'Район'),
            'from_place' => Yii::t('poputchik', 'Место / ориентир'),
            'to_place' => Yii::t('poputchik', 'Место / ориентир'),
            'sum' => Yii::t('poputchik', 'Стоимость'),
            'type_sum' => 'Type Sum',
            'type_time' => Yii::t('poputchik', 'Периодичность'),
            'date_from' => Yii::t('poputchik', 'Дата'),
            'date_to' => Yii::t('poputchik', 'Дата'),
            'date_to_offset' => Yii::t('poputchik', 'Дней в пути'),
            'time_from_1' => Yii::t('poputchik', 'Время'),
            'time_from_2' => 'Time From 2',
            'time_to_1' => Yii::t('poputchik', 'Время'),
            'time_to_2' => 'Time To 2',
            'reverse' => Yii::t('poputchik', 'Едем обратно?'),
            'date_reverse' => Yii::t('poputchik', 'Дата'),
            'date_reverse_offset' => Yii::t('poputchik', 'Через сколько дней поедем обратно (считать от дня выезда)?'),
            'date_reverse_to' => Yii::t('poputchik', 'Дата'),
            'date_reverse_to_offset' => Yii::t('poputchik', 'Дней в пути обратно'),
            'time_r_from_1' => Yii::t('poputchik', 'Время'),
            'time_r_from_2' => 'Time R From 2',
            'time_r_to_1' => Yii::t('poputchik', 'Время'),
            'time_r_to_2' => 'Time R To 2',
            'name' => Yii::t('poputchik', 'Имя'),
            'phone' => Yii::t('poputchik', 'Телефон'),
            'type_auto' => Yii::t('poputchik', 'Тип авто'),
            'id_brand' => Yii::t('poputchik', 'Марка'),
            'id_model' => Yii::t('poputchik', 'Модель'),
            'free_places_count' => 'Свободных мест',
            'comment' => Yii::t('poputchik', 'Комментарий'),
            'date_order' => Yii::t('poputchik', 'Дата объявления'),
            'date_available' => Yii::t('poputchik', 'Объявление действительно до'),
            'id_user' => 'Id User',
            'operator' => 'Operator',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id_order', $this->id_order);
        $criteria->compare('type_order', $this->type_order);
        $criteria->compare('target', $this->target);
        $criteria->compare('type_route', $this->type_route);
        $criteria->compare('id_route', $this->id_route);
        $criteria->compare('id_path', $this->id_path);
        $criteria->compare('transit', $this->transit);
        $criteria->compare('direction', $this->direction);
        $criteria->compare('id_settlement', $this->id_settlement);
        $criteria->compare('id_sa_start', $this->id_sa_start);
        $criteria->compare('id_sa_end', $this->id_sa_end);
        $criteria->compare('from_place', $this->from_place, true);
        $criteria->compare('to_place', $this->to_place, true);
        $criteria->compare('sum', $this->sum);
        $criteria->compare('type_sum', $this->type_sum);
        $criteria->compare('type_time', $this->type_time);
        $criteria->compare('date_to', $this->date_to, true);
        $criteria->compare('time_from_1', $this->time_from_1, true);
        $criteria->compare('time_from_2', $this->time_from_2, true);
        $criteria->compare('time_to_1', $this->time_to_1, true);
        $criteria->compare('time_to_2', $this->time_to_2, true);
        $criteria->compare('reverse', $this->reverse);
        $criteria->compare('date_reverse', $this->date_reverse, true);
        $criteria->compare('date_reverse_offset', $this->date_reverse_offset);
        $criteria->compare('time_r_from_1', $this->time_r_from_1, true);
        $criteria->compare('time_r_from_2', $this->time_r_from_2, true);
        $criteria->compare('time_r_to_1', $this->time_r_to_1, true);
        $criteria->compare('time_r_to_2', $this->time_r_to_2, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('type_auto', $this->type_auto);
        $criteria->compare('id_brand', $this->id_brand);
        $criteria->compare('id_model', $this->id_model);
        $criteria->compare('free_places_count', $this->free_places_count);
        $criteria->compare('comment', $this->comment, true);
        $criteria->compare('date_order', $this->date_order, true);
        $criteria->compare('date_available', $this->date_available, true);
        $criteria->compare('id_user', $this->id_user);
        $criteria->compare('operator', $this->operator);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function afterValidate() {
        if ($this->time_from_2 == "")
            $this->time_from_2 = $this->time_from_1;
        if ($this->time_r_from_2 == "")
            $this->time_r_from_2 = $this->time_r_from_1;
        if ($this->time_to_2 == "")
            $this->time_to_2 = $this->time_to_1;
        if ($this->time_r_to_2 == "")
            $this->time_r_to_2 = $this->time_r_to_1;
        //Определяем тип маршрута
        if ($this->type_route == 1) {
            $this->id_settlement = $_POST['start_settlement'];
        } else {
            $cntRim = count($_POST['rim_sid']);
            if ($cntRim > 0) {
                $route = new Routes;
                $route->start_settlement = $_POST['start_settlement'];
                $route->end_settlement = $_POST['end_settlement'];
                $route->setRPaths($_POST['rim_sid']);
                if (!$route->findOrCreate()) {
                    $this->addError('id_route', Yii::t('common', 'Произошла внутренняя ошибка сервера при создании маршрута. Пожалуйста попробуйте ещё раз.3'));
                    return;
                }
                $this->id_route = $route->id_route;
                $this->direction = $route->direction;
            } else {
                $path = new Paths;
                $path->id_settlement_1 = $_POST['start_settlement'];
                $path->id_settlement_2 = $_POST['end_settlement'];
                if (!$path->findOrCreate()) {
                    $this->addError('id_route', Yii::t('common', 'Произошла внутренняя ошибка сервера. Пожалуйста попробуйте ещё раз.4'));
                    return;
                }
                $this->id_path = $path->id_path;
                $this->direction = $path->direction;
            }
        }
        //периодичность
        switch ($this->type_time) {
            case 2:
                $this->days_of_week_tmp = array();
                foreach ($_POST['days_of_week'] as $dow)
                    $this->days_of_week_tmp[] = $dow;
                break;
            case 3:
                $this->days_of_month_tmp = array();
                foreach ($_POST['days_of_month'] as $dom)
                    $this->days_of_month_tmp[] = $dom;
                break;
        }
        if (isset($_POST['rim_sp_sid'])) {
            foreach ($_POST['rim_sp_sid'] as $rss)
                $this->rim_sp_sid[] = $rss; $this->transit = 1;
        }
        else
            $this->transit = 0;
        //$this->addError('id_route', 'ERROR 2');
        
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {

            $this->date_from = date('Y-m-d', strtotime($this->date_from));
            $this->date_to = date('Y-m-d', strtotime($this->date_to));
            $this->date_reverse = ($this->reverse && $this->type_time == 1) ? date('Y-m-d', strtotime($this->date_reverse)) : "1970-01-01";
            $this->date_reverse_to = ($this->reverse && $this->type_time == 1) ? date('Y-m-d', strtotime($this->date_reverse_to)) : "1970-01-01";
            if ($this->date_available)
                $this->date_available = date('Y-m-d', strtotime($this->date_available));
            else if ($this->type_time == 1)
                $this->date_available = $this->date_from;
            else
                $this->date_available = date('Y-m-d', strtotime("+3 month"));
            if ($this->activecode !== 0) {
                $this->activecode = rand(1000, 9999);
            }

            $cu = Yii::app()->user;

            if ($cu->checkAccess('operator')) {

                $this->activecode = 0;
                $this->status = 1;
                $this->operator = $cu->id;
            } else
                $this->status = 0;

            if (!$this->id_order) {
				
                if (!$cu->id || $cu->checkAccess('operator')) {
                    $user = User::model()->find(array('condition' => 'username=:username', 'params' => array(':username' => $this->phone)));
                    if (!$user) {
                        $user = new User;
                        $user->username = preg_replace("([^0-9])", "", $this->phone);
                        $user->activkey = Yii::app()->epassgen->generate(7, 0, 3, 0);
                        $user->password = UserModule::encrypting($user->activkey);
                        $user->superuser = 0;
                        $user->status = 0;
                        if (!$user->save(false)) {
                            $this->addError('id_order', Yii::t('common', 'Произошла внутренняя ошибка сервера. Пожалуйста попробуйте ещё раз.1'));
                            return false;
                        }
                        $profile = new Profile;
                        $profile->user_id = $user->id;
                        $profile->first_name = $this->name;
                       
						
					
					
						$profile->save();
						
                    }
                    if ($user->status == 0 && $cu->checkAccess('operator'))
                        $user->status = 1;
						if(!$user->save()) {
                            
                            Yii::t('common', 'Произошла внутренняя ошибка сервера. Пожалуйста попробуйте ещё раз.2');
                            return false;
                        };
						/*if (!Yii::app()->general->activateUser($user)) {
                            
                            Yii::t('common', 'Произошла внутренняя ошибка сервера. Пожалуйста попробуйте ещё раз.2');
                            return false;
                        };*/
                } else {
                    $user = $cu;
                    $this->activecode = 0;
                }
                $this->id_user = $user->id;
            }

            //$this->status = 1;
            //$this->activecode = 0;
            return true;
        } else {
            return false;
        }
        
    }

    protected function afterSave() {

        if (isset($_POST['email'])) {
            $user = User::model()->find(array('condition' => 'id=:id', 'params' => array(':id' => $this->id_user)));
            if ($user->email == "")
                $user->email = $_POST['email'];
            $user->save();
        }
        SiteMapHelper::Generate();
    }

    protected function afterDelete() {
        parent::afterDelete();
        SiteMapHelper::Generate();
    }

    public function save($runValidation = true, $attributes = null) {
        if (parent::save($runValidation, $attributes)) {
            if (!$runValidation)
                return true;
            $this->deleteDW();
            $this->deleteDM();
            $this->deleteSS();
            if ($this->days_of_week_tmp)
                foreach ($this->days_of_week_tmp as $dow) {
                    $m_dow = new DaysOfWeek;
                    $m_dow->id_order = $this->id_order;
                    $m_dow->day = $dow;
                    if ($this->reverse) {
                        $m_dow->day_reverse = $dow + $this->date_reverse_offset;
                        if ($m_dow->day_reverse > 6)
                            $m_dow->day_reverse -= 6;
                    }
                    if (!$m_dow->save())
                        return false;
                }
            if ($this->days_of_month_tmp)
                foreach ($this->days_of_month_tmp as $dom) {
                    $m_dom = new DaysOfMonth;
                    $m_dom->id_order = $this->id_order;
                    $m_dom->day = $dom;
                    if ($this->reverse) {
                        $m_dom->day_reverse_28 = $dom + $this->date_reverse_offset;
                        if ($m_dom->day_reverse_28 > 28)
                            $m_dom->day_reverse_28 -= 28;
                        $m_dom->day_reverse_30 = $dom + $this->date_reverse_offset;
                        if ($m_dom->day_reverse_30 > 30)
                            $m_dom->day_reverse_30 -= 30;
                        $m_dom->day_reverse_31 = $dom + $this->date_reverse_offset;
                        if ($m_dom->day_reverse_31 > 31)
                            $m_dom->day_reverse_31 -= 31;
                    }
                    if (!$m_dom->save())
                        return false;
                }
            foreach ($this->rim_sp_sid as $rss) {
                $ss = new PoputchikOrderSettlements;
                $ss->id_order = $this->id_order;
                $ss->id_settlement = $rss;
                if (!$ss->save())
                    return false;
            }
            return true;
        } else {
            return false;
        }
    }

    public function delete() {
        $this->deleteDW();
        $this->deleteDM();
        $this->deleteSS();
        return parent::delete();
    }

    public function deleteDW() {
        Yii::app()->db->createCommand()->delete('days_of_week', 'id_order=:id', array(':id' => $this->id_order));
    }

    public function deleteDM() {
        Yii::app()->db->createCommand()->delete('days_of_month', 'id_order=:id', array(':id' => $this->id_order));
    }

    public function deleteSS() {
        Yii::app()->db->createCommand()->delete('poputchik_order_settlements', 'id_order=:id', array(':id' => $this->id_order));
    }

    public function afterFind() {
        parent::afterFind();
        if(!$this->isNewRecord){
            $this->date_from = date('d.m.Y', strtotime($this->date_from));
            $this->date_to = date('d.m.Y', strtotime($this->date_to));
            $this->date_reverse = date('d.m.Y', strtotime($this->date_reverse));
            $this->date_reverse_to = date('d.m.Y', strtotime($this->date_reverse_to));
            $this->date_available = date('d.m.Y', strtotime($this->date_available));
            $this->time_from_1 = date('H:i', strtotime($this->time_from_1));
            $this->time_from_2 = date('H:i', strtotime($this->time_from_2));
            $this->time_to_1 = date('H:i', strtotime($this->time_to_1));
            $this->time_to_2 = date('H:i', strtotime($this->time_to_2));
            $this->time_r_from_1 = date('H:i', strtotime($this->time_r_from_1));
            $this->time_r_from_2 = date('H:i', strtotime($this->time_r_from_2));
            $this->time_r_to_1 = date('H:i', strtotime($this->time_r_to_1));
            $this->time_r_to_2 = date('H:i', strtotime($this->time_r_to_2));
        }
    }
    

    public function getSP() {
        $settlements = array();
        foreach ($this->rims_sp as $settlement)
            $settlements[] = $settlement->idSettlement->name;
        return $settlements;
    }

    public function isActive(){
      return time() < CDateTimeParser::parse($this->date_available, 'dd.MM.yyyy');
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PoputchikOrder the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
