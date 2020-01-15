<?php

/**
 * This is the model class for table "seo_city".
 *
 * The followings are the available columns in table 'seo_city':
 * @property integer $id
 * @property integer $city_id
 * @property string $seo_text
 * @property string $url
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property settlements $settlements
 */
class SeoCity extends CActiveRecord {

    public $settlement_name_1;
    public $settlementName;
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'seo_city';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('city_id, url', 'required'),
            array('city_id', 'numerical', 'integerOnly' => true),
            array('url, title, keywords, description', 'length', 'max' => 255),
            array('city_id, url', 'unique'),
            array('seo_text, seo_text_top', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, city_id, seo_text, url, title, keywords, description, seo_text_top, settlementName', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'settlements' => array(self::BELONGS_TO, 'Settlements', 'city_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'city_id' => 'City',
            'seo_text' => 'Seo Text',
            'url' => 'Url',
            'seo_text_top' => 'Seo Text Top',
            'title' => 'Title',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'settlementName' => 'Город'
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
$criteria->compare('id', $this->id);
$criteria->compare('url', $this->url, true);
       $criteria->compare('settlements.name', $this->settlementName, true);
                $sort = new CSort();
                $sort->attributes = array(
                        'id',
                        'url',
                        'settlementName' => array(
                            'asc'=>'settlements.name',
                        'desc'=>'settlements.name DESC',
                                ),
                
                );

                $criteria->with = array('settlements');
        //$criteria->compare('city_name', $this->settlements->name, true);
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort'=>$sort,
                ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SeoCity the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function findBySlug($slug){
      return self::model()->findByAttributes(array('url' => $slug));
    }
    public static function generateSlug($city_id, $cityName_or_slug){

      Yii::import('application.extensions.UrlTransliterate.UrlTransliterate');

      if ($city_id > 0 && Settlements::model()->findByPk($city_id)){
        $slug = UrlTransliterate::cleanString($cityName_or_slug);
        $already = self::model()->findByAttributes(array('city_id' => $city_id));
        if ($already) {
          return $already->url;
        }

        $already = self::model()->findByAttributes(array('url' => $slug));

        if ($already) {
          if ($already->city_id == $city_id) {
            return $slug;
          } else {
            return self::generateSlug($city_id, $cityName_or_slug.$city_id);
          }
        } else {
          return $slug;
        }
      } else return '';
    }
}
