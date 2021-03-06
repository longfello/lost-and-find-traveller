<?php

  class Banners extends CActiveRecord {
    public $deleteImage; // для удаление картинки при редаутировании
    public $oldFile; // для сохранения/удаления старой картинки
    public $dropViewed; // для "сброса кол-во показов"

    private $cacheId = 'Banners'; //идентификатор кеша

    const BANNERS_TYP_IMG = 'img';
    const BANNERS_TYP_SWF = 'swf';
    const BANNERS_TYP_TEXT = 'text';

    const POSITION_TOP               = 'position_top';

    public $bnrVisibleDate;

    /**
     * Returns the static model of the specified AR class.
     *
     * @param string $className
     * @return Banners the static model class
     */
    public static function model($className = __CLASS__) {
      return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
      return 'brotate';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
      return array(//array('bnrTag','unique','on'=>'insert, update','message'=>Yii::t('lan','Field "{value}" already used.')), // уникальный логин
        // array('position', 'type'=>'string'),
        array('pid', 'numerical', 'integerOnly'=>true),
        array('bnrTag', 'match', 'pattern' => '/^[A-Za-z0-9\_]+$/u', 'message' => Yii::t('lan', 'Field "{attribute}" incorrect.')),
        // паттерн на метку
        //array('txt_image', 'file', 'types'=>Yii::app()->params['bannersFilesUploadTypes'],'allowEmpty'=>true,'maxSize'=>Yii::app()->params['bannersFilesUploadSize'],'tooLarge'=>'The file was larger than 2MB. Please upload a smaller file.',),
        array('bnrTyp', 'in', 'range' => array('img','swf','text')),
        array('position', 'in', 'range'=> array(self::POSITION_TOP)),
        //статус
        array('bnrWidth, bnrHeight, bnrVisibleLimit', 'numerical','integerOnly' => TRUE),
        array('dropViewed,id,bnrDefault,bnrVisible,bnrUrl,bnrDescr,bnrFile,bnrVisibleLimit,bnrVisibleFrom,bnrVisibleTo', 'safe'),);
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
      // NOTE: you may need to adjust the relation name and the related
      // class name for the relations automatically generated below.
      return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
      return array('bnrTag' => 'Tag',
        'pid' => 'Limit allocation',
        'position' => 'Banner position',
        'bnrTyp' => 'Banner type',
        'bnrClicks' => 'Click count',
        'bnrFile' => 'File (if banner type is image or flash)',
        'bnrUrl' => 'Href',
        'bnrViewedCurrent' => 'Current shows',
        'bnrViewedTotal' => 'Overall shows',
        'bnrVisible' => 'Is banner visible',
        'bnrVisibleFrom' => 'Visible from',
        'bnrVisibleTo' => 'Visible to',
        'bnrVisibleLimit' => 'Limit shows',
        'bnrVisibleDate' => 'Visible on (dates)',
        'bnrDescr' => 'Description or code, if banner type is text',
        'bnrWidth' => 'Width (px)',
        'bnrHeight' => 'Height (px)',
        'bnrDefault' => 'Default banner',
        'dropViewed' => 'Reset shows count',);
    }

    public function getCacheId($cacheId) {
      return $_SERVER['HTTP_HOST'] . '_' . $cacheId . '_' . Yii::app()->language;
    }

    public function bannersClick($params = array()) {
      if (isset($params['id']) and !empty($params['id'])) {
        $this->updateClicks($params['id']);
        return $this->dbConnection->createCommand("select bnrUrl from " . self::tableName() . " where id=:id")->queryScalar(array(':id' => (int)$params['id']));
      } else {
        return '/';
      }
    }

    public function bannersResult($result) {
      $module = Yii::app()->getModule('brotate');

      switch ($result['bnrTyp']) {
        case self::BANNERS_TYP_IMG :
        {
          $res = '<img width="' . $result['bnrWidth'] . '" height="' . $result['bnrHeight'] . '" src="' . Yii::app()->params['bannersWebFolder'] . $result['bnrFile'] . '" alt="'.htmlentities($result['bnrDescr']).'" title="'.htmlentities($result['bnrDescr']).'">';

          if (isset($result['bnrUrl']) and !empty($result['bnrUrl'])) {
            $res = '<a href="' . Yii::app()->createAbsoluteUrl('/site/bannerclick', array('id' => $result['id'])) . '">' . $res . '</a>';
          }

          break;
        }
        case self::BANNERS_TYP_SWF:
        {
          $res = '<object type="application/x-shockwave-flash" data="' . Yii::app()->params['bannersWebFolder'] . $result['bnrFile'] . '" width="' . $result['bnrWidth'] . '" height="' . $result['bnrHeight'] . '"><param name="movie" value="' . $module->webFolder . $result['bnrFile'] . '" /><param name="quality" value="high"></object>';

          if (isset($result['bnrUrl']) and !empty($result['bnrUrl'])) {
            $res = '?link=' . Yii::app()->createAbsoluteUrl('/brotate/click', array('id' => $result['id']));
            $res = '<object type="application/x-shockwave-flash" data="' . Yii::app()->params['bannersWebFolder'] . $result['bnrFile'] . $res . '" width="' . $result['bnrWidth'] . '" height="' . $result['bnrHeight'] . '"><param name="movie" value="' . $module->webFolder . $result['bnrFile'] . $res . '" /><param name="quality" value="high"></object>';
          }
          break;
        }
        case self::BANNERS_TYP_TEXT:
        {
          $res = str_replace("\r", '', $result['bnrDescr']);
          $res = strtr($res, array('{bnrClick}' => Yii::app()->createAbsoluteUrl('/brotate/click', array('id' => $result['id'])),
              '{bnrUrl}' => $result['bnrUrl']));
          break;
        }

        default   :
          {
          $res = '';
          break;
          }
      }
      return $res;
    }

    public function bannersGet($params = array()) {
      $txt_tag = '';
      if (isset($params['bnrTag']) and !empty($params['bnrTag'])) {
        $txt_tag = " and bnrTag='" . $params['bnrTag'] . "'";
      }
      $allocation_clause = $allocation_join = '';
      /*
      $allocation_id = isset(Yii::app()->controller->location)?Yii::app()->controller->location:false;
      if ($allocation_id) {
        $allocation_clause = "AND ((b.pid = 0) OR (at.cid = {$allocation_id}))";
        $allocation_join = "LEFT JOIN alocations_tree at ON at.pid = b.pid";
      }
      */

      $result = $this->dbConnection->createCommand("
        SELECT b.id,bnrTyp,bnrFile,bnrWidth,bnrHeight,bnrDescr,bnrUrl,bnrVisibleLimit,bnrViewedCurrent,bnrViewedTotal
        FROM " . self::tableName() . " b
        $allocation_join
        WHERE bnrVisible=1 and bnrDefault = 0 {$txt_tag} AND position = '{$params['position']}' AND bnrVisibleTo > now()
        $allocation_clause
        order by rand()
        LIMIT 1")->queryRow(TRUE);

      //если нет баннера - выбираем "баннер по умолчанию"
      if (!isset($result) or empty($result)) {
        $result = $this->dbConnection->createCommand("SELECT id,bnrTyp,bnrFile,bnrWidth,bnrHeight,bnrDescr,bnrUrl FROM " . self::tableName() . " WHERE bnrDefault=1  AND position = '{$params['position']}' LIMIT 1")->queryRow(TRUE);
        if (!isset($result) or (!$result)) {
          return "";
        }
      }

      $res = self::bannersResult($result);

      $this->updateViewedCurrent($result['id']); //получаем ИД баннера и обновляем счетчик показов баннера
      //BannersStat::model()->updateStat($result['id']);

      return array('bnrTyp' => $result['bnrTyp'],
        'banner' => $res);
    }

    protected function updateClicks($id = NULL) {
      $this->dbConnection->createCommand("update " . self::tableName() . " set bnrClicks=bnrClicks+1 where id=:id")->execute(array(':id' => (int)$id));
    }

    protected function updateViewedCurrent($id = NULL) {
      $this->dbConnection->createCommand("update " . self::tableName() . " set bnrViewedCurrent=bnrViewedCurrent+1 where id=:id")->execute(array(':id' => (int)$id));
    }

    protected function updateViewedTotal() {
      $ids = $this->dbConnection->createCommand("SELECT id FROM " . self::tableName() . " WHERE bnrVisible=1 and bnrViewedCurrent!=0")->queryColumn();
      foreach ($ids as $id) {
        $this->dbConnection->createCommand("update " . self::tableName() . " set bnrViewedTotal=bnrViewedTotal+bnrViewedCurrent, bnrViewedCurrent=0 where id=:id")->execute(array(':id' => $id));
      }
    }

    protected function afterFind() {
      $bnrVisibleFrom = DateTime::createFromFormat('Y-m-d', $this->bnrVisibleFrom);
      $this->bnrVisibleFrom = $bnrVisibleFrom->format('d.m.Y');

      $bnrVisibleTo = DateTime::createFromFormat('Y-m-d', $this->bnrVisibleTo);
      $this->bnrVisibleTo = $bnrVisibleTo->format('d.m.Y');

      $this->bnrVisibleDate = $this->bnrVisibleFrom . ' - ' . $this->bnrVisibleTo;
      parent::afterFind();
    }

    //после удаления записи, удаляем связь между "Каталогом и Записью"
    protected function afterDelete() {
      parent::afterDelete();

      $module = Yii::app()->getModule('brotate');
      @unlink(Yii::getPathOfAlias('webroot') . Yii::app()->params['bannersWebFolder'] . $this->bnrFile); //стираем ее

      $cacheId = $this->getCacheId($this->cacheId);
//      Yii::app()->cache->delete($cacheId); //удаляем кеш
      $this->updateViewedTotal();
    }

    /**
     * Processing after the record is saved
     */
    protected function afterSave() {
      $cacheId = $this->getCacheId($this->cacheId);
//      Yii::app()->cache->delete($cacheId); //удаляем кеш
      $this->updateViewedTotal();
      if (isset($this->dropViewed) and !empty($this->dropViewed)) //обнуляем кол-во показов
      {
        $this->dbConnection->createCommand("update " . self::tableName() . " set bnrViewedTotal=0,bnrViewedCurrent=0 where id=:id")->execute(array(':id' => $this->id));
      }
    }

    /**
     * Processing before the record is saved
     */
    protected function beforeSave() {
      Yii::log('beforeSave: begin', "trace", "system.*");
      if (parent::beforeSave()) {
        if (!isset($this->bnrVisibleFrom) or empty($this->bnrVisibleFrom)) {
          $this->bnrVisibleFrom = date('d.m.Y');
        }
        if (!isset($this->bnrVisibleTo) or empty($this->bnrVisibleTo)) {
          $this->bnrVisibleTo = date('d.m.Y');
        }

        $bnrVisibleFrom = DateTime::createFromFormat('d.m.Y', $this->bnrVisibleFrom);
        $this->bnrVisibleFrom = $bnrVisibleFrom->format('Y-m-d');

        $bnrVisibleTo = DateTime::createFromFormat('d.m.Y', $this->bnrVisibleTo);
        $this->bnrVisibleTo = $bnrVisibleTo->format('Y-m-d');

        $imagePath = Yii::getPathOfAlias('webroot') . Yii::app()->params['bannersWebFolder'];
        Yii::log($imagePath, "trace", "system.*");
        if (!file_exists($imagePath)) {
          mkdir($imagePath, 0777, TRUE);
        }

        //если нам сказали "удалить картинку"
        if (isset($this->deleteImage) and !empty($this->deleteImage)) {
          @unlink($imagePath . $this->bnrFile); //стираем ее
          $this->bnrFile = ''; //обнуляем "картинку"
        }

        if (is_object($this->bnrFile)) //тут у нас картинка? да!
        {
          $pi = pathinfo($this->bnrFile->name); //делим на составляющие имя файла (картинки)
          $file = mktime(date("H"), date("i"), date("s"), date("n"), date("j"), date("Y")) . '.' . $pi['extension']; //генерируем случайное имя файла

          //пытаемся сохранить картинку
          if (!$this->bnrFile->saveAs($imagePath . $file)) {
            //не удалось сохранить картинку
            $this->addError('bnrFile', $this->bnrFile->getError());
            return FALSE;
          }

          //*** сюда попали, если загрузка картинки прошла успешно ***
          @unlink($imagePath . $this->oldFile);
          $this->bnrFile = $file;

          list($this->bnrWidth, $this->bnrHeight, $type, $attr) = getimagesize($imagePath . $file);
        }
        return TRUE;
      } else {
        return FALSE;
      }
    }

    /**
     * @return array user status names indexed by status IDs
     */
    public function getBannersDefault() {
      return array(
        0 => 'No',
        1 => 'Yes',);
    }
    public function getBannersTyp() {
      return array(self::BANNERS_TYP_IMG => 'Image',
        self::BANNERS_TYP_SWF => 'Flash (swf)',
        self::BANNERS_TYP_TEXT => 'Code (text)',);
    }

    /**
     * @return array user status names indexed by status IDs
     */
    public function getBannersPosition() {
      return array(
        self::POSITION_TOP               => 'Top on right column',
      );
    }

    /**
     * Возвращает список юзеров, основанный на фильтре.
     *
     * @return CActiveDataProvider
     */
    public function search() {
      $criteria = new CDbCriteria;
      $criteria->compare('t.id', $this->id, TRUE);
      $criteria->compare('t.bnrTyp', $this->bnrTyp, TRUE);
      $criteria->compare('t.position', $this->position, TRUE);
      $criteria->compare('t.bnrDefault', $this->bnrDefault, TRUE);

      return new CActiveDataProvider(get_class($this), array('criteria' => $criteria,
        'pagination' => array('pageSize' => 20,),
        'sort' => array('defaultOrder' => 't.id DESC',),));
    }
  }
