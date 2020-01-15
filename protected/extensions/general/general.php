<?php
/**
 * General API
 *
 * @author Aliscom
 */

class general extends CApplicationComponent {
    public function activateUser($user) {

	if($user->status ==0){
		Yii::app()->smsgate->send($user->username, Yii::t('general', 'Ваш пароль на сайте INFOtoway : ') .$user->activkey);
		$user->status = 1;
		if($user->save()) return true;
		else return false;
	} else return true;
	}
	public function idthing_to_userid($id_ls) {
		return  str_replace("-", "", $id_ls);
	}
	
	public function userid_to_idthing($id) {
		$result = "";
		$len = strlen($id);
		
		$result = substr($id, 0, $len%3);
	
		for($i=$len%3; $i<$len; $i+=3  ){
			if($i!=0) $result.= "-";	
			$result.= substr($id, $i, 3);	
		}
		return  $result;
	}
}

?>