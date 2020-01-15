<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
	/*	Yii::app()->getModule('files');
		switch((int)$_GET['type']) {
			case 1:
				$w = (int)$_GET['width'];
				if(!$w || $w > 2048) $w = 2048;
				$h = (int)$_GET['height'];
				if(!$h || $h > 2048) $h = 2048;
				$file = FilesController::uploadFile($_FILES['file'], 1, $errors, array(), array('imagesize' => array('width' => $w, 'height' => $h)));
				if($file) print '{"OK": 1, "id": "'.$file->id_file.'", "path": "'.$file->path.'"}';
				break;
			case 2:
				$file = FilesController::uploadFile($_FILES['file'], 2, $errors);
				if($file) print '{"OK": 1, "id": "'.$file->id_file.'", "name": "'.$file->name.'", "path": "'.$file->path.'"}';
				break;
		}
		if($file) die();
		else {
			$result = array();
			$result["OK"] = 0;
			$result["errors"] = $errors;
			print json_encode($result);
		}
	*/
	}
}