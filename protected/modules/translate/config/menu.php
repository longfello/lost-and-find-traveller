<?php

/**
 * Admin menu items for pages module
 */
return array(
	'cms'=>array(
		'position'=>5,
		'items'=>array(
			array(
				'label'=>Yii::t('TranslateModule', 'Локализация'),
				'url'=>array('/admin2/translate'),
				'position'=>3
			),
		),
	),
);