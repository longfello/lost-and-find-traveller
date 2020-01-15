<?php
/**
 * Copyright (c) 2014, Aliscom, http://aliscom.ru
 * All rights reserved.
 *
 * @author Denis Baklaev
 */
class PluploadWidget extends CWidget {

    const ASSETS_DIR_NAME       = '/assets';
    const PLUPLOAD_FILE_NAME    = '/js/plupload.full.min.js';

    public $publicPath;
    public $config = array(
        'up_name' => 'uploader',
    );

    public function init() {
        //   if(!$this->config['max_filesize']) $this->config['max_filesize'] = (convertBytes(ini_get('upload_max_filesize'))/(1024*1024)) . 'mb';
        if(!$this->config['max_filesize']) $this->config['max_filesize'] = '10mb';
        $localPath = dirname(__FILE__) . self::ASSETS_DIR_NAME;
        $this->publicPath = Yii::app()->getAssetManager()->publish($localPath);

        $pluploadPath = $this->publicPath . self::PLUPLOAD_FILE_NAME;
        Yii::app()->clientScript->registerScriptFile($pluploadPath);

        if(file_exists($localPath . '/js/i18n/' . Yii::app()->language . '.js'))
            Yii::app()->clientScript->registerScriptFile($this->publicPath . '/js/i18n/' . Yii::app()->language . '.js');
        Yii::app()->clientScript->registerCssFile($this->publicPath.'/css/plupload.css');
    }

    public function run()
    {
        $this->render('widget', array(
            'publicPath' => $this->publicPath,
            'config' => $this->config,
        ));
    }
}

?>
