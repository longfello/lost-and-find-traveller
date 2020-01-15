<?php

define('SERVICE_CMS',          0);
define('SERVICE_POPUTCHIK',    'poputchik');
define('SERVICE_BURO_NAHODOK', 'buronahodok');
define('SERVICE_NOCHLEG',      'nochleg');
define('SERVICE_NEPOTERAYKA',  'nepoteryaika');

include_once('infotoway/protected/models/PpRoute.php');

  return array(
    'sourcePath' => '/home/webuser/kvk-dev.pp.ua/infotoway', //root dir of all source
    'messagePath' => '/home/webuser/kvk-dev.pp.ua/infotoway/protected/messages', //root dir of message translations
    'languages' => array('en', 'ru'), //array of lang codes to translate to, e.g. es_mx
    'fileTypes' => array('php',), //array of extensions no dot all others excluded
    'exclude' => array('.svn', '.git'), //list of paths or files to exclude
    'translator' => 'Yii::t', //this is the default but lets be complete
    'overwrite' => true,
    'removeOld' => false,
    'sort' => true,
  );