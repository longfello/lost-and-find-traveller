<?php

global $days_of_week;
$days_of_week = array(
  'ru'=> array(
    array('short' => 'пн', 'long' => 'понедельник'),
    array('short' => 'вт', 'long' => 'вторник'),
    array('short' => 'ср', 'long' => 'среда'),
    array('short' => 'чт', 'long' => 'четверг'),
    array('short' => 'пт', 'long' => 'пятница'),
    array('short' => 'сб', 'long' => 'суббота'),
    array('short' => 'вс', 'long' => 'воскресенье'),
  )
);

define('SERVICE_CMS',          0);
define('SERVICE_POPUTCHIK',    'poputchik');
define('SERVICE_BURO_NAHODOK', 'buronahodok');
define('SERVICE_NOCHLEG',      'nochleg');
define('SERVICE_NEPOTERAYKA',  'nepoteryaika');
$allowed_subdomains = array(SERVICE_NOCHLEG, SERVICE_POPUTCHIK, SERVICE_NEPOTERAYKA, SERVICE_BURO_NAHODOK);

$url = explode('.',$_SERVER["HTTP_HOST"]);

$subdomain = strtolower($url[0]);
$subdomain = in_array($subdomain, $allowed_subdomains)?$subdomain:'';

$domain = implode('.', array_diff($url, array($subdomain)));

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'aliases' => array(
        'bootstrap' => 'ext.bootstrap'
    ),
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Единая информационная служба',
    'sourceLanguage'=>'slug',
    'language' => 'ru',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.components.helpers.*',
        'application.components.forms.*',
        'application.components.widgets.*',
        'application.modules.user.models.*',
        'application.modules.cms.models.*',
        'application.modules.user.components.*',
        'application.modules.rights.*',
        'application.modules.rights.components.*',
        'application.modules.UserLocation.components.*',
        'application.extensions.nestedset.*', // import nested set extension
        'ext.easyimage.EasyImage',
        'bootstrap.behaviors.*',
        'bootstrap.helpers.*',
        'bootstrap.widgets.*'
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool

        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'ghjtrn9',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            // 'ipFilters' => array('127.0.0.1', '::1', '178.49.245.132'),
            'ipFilters' => array(),
            'generatorPaths' => array(
                'bootstrap.gii'
            )
        ),
        'nepoteryai' => array(
        ),
        'user' => array(
            # encrypting method (php hash function)
            'hash' => 'md5',
            # send activation email
            'sendActivationMail' => true,
            # allow access for non-activated users
            'loginNotActiv' => false,
            # activate user on registration (only sendActivationMail = false)
            'activeAfterRegister' => false,
            # automatically login from registration
            'autoLogin' => true,
            # registration path
            'registrationUrl' => array('/'),
            # recovery password path
            'recoveryUrl' => array('/user/recovery'),
            # login form path
            'loginUrl' => array('/user/login'),
            # page after login
            'returnUrl' => array('/user/profile'),
            # page after logout
            'returnLogoutUrl' => array('/site/index'),
        ),
        'UserLocation',
        'rights' => array(
            'install' => false, // Enables the installer.
        ),
        'translate' => array(),
        'cms' => array(
        // this layout will be set by default if no layout set for page
        //'defaultLayout'=>'cms',
        ),
    ),
    // application components
    'components' => array(
        'bootstrap' => array(
            'class' => 'bootstrap.components.BsApi'
        ),
        'user' => array(
            // enable cookie-based authentication
            'class' => 'RWebUser',
            'allowAutoLogin' => true,
            'loginUrl' => array('/user/login'),
            'identityCookie' => array(
                'domain' => '.' . $_SERVER['SERVER_NAME'],
                'expire' => 24 * 3600,
            ),
        ),
        'location' => array(
          'class' => 'Location',
          'defaultCity' => 524901
        ),
        'authManager' => array(
            'class' => 'RDbAuthManager',
            'assignmentTable' => 'authassignment',
            'itemTable' => 'authitem',
            'itemChildTable' => 'authitemchild',
            'rightsTable' => 'rights',
            'defaultRoles' => array('Guest') // дефолтная роль
        ),
        'session' => array(
            'cookieMode' => 'allow',
            'cookieParams' => array(
                'path' => '/',
                'domain' => '.' . $_SERVER['SERVER_NAME'],
                'httpOnly' => true,
            ),
        ),
        'epassgen' => array(
            'class' => 'ext.epasswordgenerator.EPasswordGenerator',
        ),
        'smsgate' => array(
            'class' => 'ext.smsgate.smsgate',
        ),
        'general' => array(
            'class' => 'ext.general.general',
        ),
        'easyImage' => array(
            'class' => 'application.extensions.easyimage.EasyImage',
            //'driver' => 'GD',
            'quality' => 80,
            'cachePath' => '/assets/easyimage/',
            'cacheTime' => 100,
        //'retinaSupport' => false,
        ),
        'urlManager' => array(
            'class' => 'application.components.UrlManager',
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                array(
                    'class' => 'application.components.BlogUrlRule',
                    'connectionID' => 'db',
                ),
                array(
                    'class' => 'application.modules.cms.components.CmsUrlRule',
                    'connectionID' => 'db',
                ),
                array(
                    'class' => 'application.components.PoputchikUrlRule',
                    'connectionID' => 'db',
                ),
                '/feedback' => array('site/contact'),
                '/poleznye-sovety' => array('blog/index'),
                'poteri' => array('lostFound', 'defaultParams' => array('type_order' => 2)),
                'nahodki' => array('lostFound', 'defaultParams' => array('type_order' => 3)),

                'poputchik-po-gorodu' => array('poputchikOrder', 'defaultParams' => array('type_route' => 1)),
                'poputchik-po-gorodu/<city:(.+)>' => array('poputchikOrder', 'defaultParams' => array('type_route' => 1)),
                'poputchik-v-gorod' => array('poputchikOrder', 'defaultParams' => array('type_route' => 2)),

                '/admin2/<module:\w+>'=>'<module>/admin/default',
                '/admin2/<module:\w+>/<controller:\w+>'=>'<module>/admin/<controller>',
                '/admin2/<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/admin/<controller>/<action>',
                '/admin2/<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>'=>'<module>/admin/<controller>/<action>',
                '/admin2/<module:\w+>/<controller:\w+>/<action:\w+>/*'=>'<module>/admin/<controller>/<action>',
/*
                '<language:(ru|ua|en)>/' => 'site/index',
                '<language:(ru|ua|en)>/<action:(contact|login|logout)>/*' => 'site/<action>',
                '<language:(ru|ua|en)>/<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<language:(ru|ua|en)>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<language:(ru|ua|en)>/<controller:\w+>/<action:\w+>/*' => '<controller>/<action>',
*/

                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',


                '/img/thumb/in/<size:([\dx])*>/<path:(.)*>' => array('img/thumb', 'caseSensitive'=>true, 'defaultParams' => array('func' => 'in')),
                '/img/thumb/exacly/<size:([\dx])*>/<path:(.)*>' => array('img/thumb', 'caseSensitive'=>true, 'defaultParams' => array('func' => 'exacly')),
                '/img/thumb/<path:(.)*>' => array('img/thumb', 'caseSensitive'=>true),

            ),
        ),
        /* 'db'=>array(
          'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
          ),
          // uncomment the following to use a MySQL database
          /* */
        'cache'=>array(
		        'class'=>'system.caching.CApcCache',
		        //'class'=>'system.caching.CDummyCache',
        ),
        'db' => array(
            'connectionString' => '',
            'emulatePrepare' => true,
            'enableParamLogging' => true,
          // включаем профайлер
            'enableProfiling'=>true,
            'username' => '',
            'password' => '',
            'charset' => 'utf8',
            'tablePrefix' => '',
        ),
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                            // uncomment the following to show log messages on web pages
              /*
              array(
                  'class'=>'CProfileLogRoute',
                  'levels' => 'profile'
              ),
              */
            ),
        ),
    ),
    'params' => array(
      'languages' => array('ru' => 'Русский', 'ua' => 'Українська', 'en' => 'English'),
      'adminEmail' => 'support@infotoway.ru',
      'bannersWebFolder' => '/images/banners',
      'subdomain' => $subdomain,
      'domain' => $domain
    ),
);