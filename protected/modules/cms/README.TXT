Site Structure module.

Features:

1. Create Page/PageSet/Link
2. Sort items
3. Set page templates, subtemplates, layouts
4. Set title, metatags, description
5. Public\Authorised access to pages.

Main feature: Absolute URLS from the root of project without creating controllers.

SETTING UP.

1. copy files of module to modules/cms.
2. activate module in config:
'modules'=>array(
	...,
	'cms'=>array(
		// this layout will be set by default if no layout set for page
		'defaultLayout'=>'cms', // this layout will be set by default if no layout set for page
	),
	...
),
3. Module uses nested set extension and translitUrl extension, FCKEditor(optional). 
Extensions are in extensions.zip archive, so unpack them to application.extensions path.

Set
'import'=>array(
	'application.models.*',
	'application.components.*',
	'application.extensions.nestedset.*', // import nested set extension
)

4. set components config

'components'=>array(
...,
'errorHandler'=>array(
	'class'=>'application.modules.cms.components.CmsHandler',
),
'db'=>array(
    ...,
    'tablePrefix'=>'', // even empty table prefix required!!!
    ...
),
...,
'urlManager'=>array(
    'urlFormat'=>'path',
    ....
    'showScriptName'=>false,
),
),
Copy .htaccess from archive to root directory of project

5.  go to cms module via route PROJECT_ROOT/cms/install
Press "install database" link.

6. After database installed, go PROJECT_ROOT/cms/, log in and manage your pages!!!


HOW TO MAKE HOME PAGE OF PROJECT EDITABLE VIA CMS MODULE:

put this code to your default conntroller:

<?php
public function actionIndex()
{
    Yii::import('application.modules.cms.components.CmsHandler');
    CmsHandler::renderHomePage();
}
?>