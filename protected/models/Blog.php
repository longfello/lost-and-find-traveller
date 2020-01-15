<?php

/**
 * This is the model class for table "blog".
 *
 * The followings are the available columns in table 'blog':
 * @property integer $id
 * @property string $url
 * @property string $name
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $content
 * @property integer $created
 */
class Blog extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'blog';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('url, name, content, created', 'required'),
            array('created', 'numerical', 'integerOnly' => true),
            array('url, name, title, keywords, description', 'length', 'max' => 255),
            array('url', 'unique'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, url, name, title, keywords, description, content, created', 'safe', 'on' => 'search'),
        );
    }

    protected function afterSave() {
        SiteMapHelper::Generate();
    }

    protected function afterDelete() {
        parent::afterDelete();
        SiteMapHelper::Generate();
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'url' => 'Url',
            'name' => 'Name',
            'title' => 'Title',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'content' => 'Content',
            'created' => 'Created',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('keywords', $this->keywords, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('created', $this->created);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Blog the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    protected function beforeValidate() {
        $this->created = time();
        return parent::beforeValidate();
    }

}
