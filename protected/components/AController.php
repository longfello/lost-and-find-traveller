<?php
# protected/components/AController.php
class AController extends Controller {

  public function init()
	{
		if(Yii::app()->user->checkAccess('PoputchikOrder.moderator'))
		  Yii::app()->theme = 'admin';
		else
		  Yii::app()->theme = 'classic';

		parent::init();
	}
}
?>