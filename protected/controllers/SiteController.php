<?php

class SiteController extends AController {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }
    public function actionRepair() {
      $query = "
SELECT name, count(id_settlement) aaaa
FROM settlements
GROUP BY `name`
HAVING aaaa > 1";
      $res =  Yii::app()->db->createCommand($query)->queryAll();
      foreach($res as $row){
        $query = "SELECT id_settlement FROM settlements WHERE name = '{$row['name']}' AND aoid IS NULL LIMIT 1";
        $sub = Yii::app()->db->createCommand($query)->queryAll();
        foreach($sub as $info) {
          $criteria = new CDbCriteria();
          $criteria->addCondition("name = '{$row['name']}'");
          $criteria->addCondition("aoid IS NOT NULL");
          $id = Settlements::model()->find($criteria)->id_settlement;
          $query = "UPDATE paths SET id_settlement_2 = {$id} WHERE id_settlement_2 = {$info['id_settlement']};";
          Yii::app()->db->createCommand($query)->execute();
          $query = "UPDATE paths SET id_settlement_1 = {$id} WHERE id_settlement_1 = {$info['id_settlement']};";
          Yii::app()->db->createCommand($query)->execute();
          $query = "DELETE FROM settlements WHERE id_settlement = {$info['id_settlement']}";
          Yii::app()->db->createCommand($query)->execute();
        }
      }
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $language = Yii::app()->language;
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'

        switch (Yii::app()->params['subdomain']) {
            case SERVICE_POPUTCHIK:
                $this->forward('poputchikOrder/index');
                break;
            case SERVICE_BURO_NAHODOK:
                $this->forward('lostFound/index');
                break;
		    case SERVICE_NEPOTERAYKA:
                $this->forward('LostService/index');
                break;	
		    case SERVICE_NOCHLEG:
                $this->forward('hotelService/index');
                break;	
            default:
                $poputDsc = Cms::model()->find('url=:url', array(':url' => $language . '/poputchik'));
                $buroNahodokDsc = Cms::model()->find('url=:url', array(':url' => $language . '/buroNahodok'));
                $this->render('index', array(
                    'text' => Cms::model()->find('url=:url', array(':url' => $language)),
                    'poputDsc' => empty($poputDsc)?null:$poputDsc->content,
                    'buroNahodokDsc' => empty($buroNahodokDsc)?null:$buroNahodokDsc->content
                ));
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                //$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-Type: text/plain; charset=UTF-8";
               
                $body = $model->typeLabel[$model->type];
                 $category = $model->getCategoryOptions();
                $key = array_search($model->category, $category);
                foreach($category as $cat){
                    if($cat['id'] == $model->category){
                        $body .= "<br>" .$cat['text'];
                    }
                }
                
                $body .= "<br><br>" . $model->body;
                if (!empty($model->phone)) {
                    $body .= "<br><br> Телефон:" . $model->phone;
                }
				 if (!empty($model->email)) {
                    $body .= "<br>Email:" . $model->email;
                }

  
   
				
			Yii::import('application.extensions.phpmailer.JPhpMailer');
			$mail = new JPhpMailer;
			$mail->Mailer = "smtp"; 
			$mail->IsSMTP();			
			$mail->Host = 'smtp.yandex.ru';
		    $mail->Port = '587'; 
			$mail->SMTPAuth = true;
		
			$mail->Username = 'support@infotoway.ru';
			$mail->Password = 'c943f93934r';
			$mail->SetFrom('support@infotoway.ru','support@infotoway.ru' );
			
				$mail->AddReplyTo($model->email, $model->email);
	
			
			$mail->Subject = $subject;		
			$mail->MsgHTML($body);
			$mail->AddAddress(Yii::app()->params['adminEmail'], 'John Doe');
			
			
			
			$mail->Send();
				
				
         // mail(Yii::app()->params['adminEmail'],$subject,$body,$headers);
     
                Yii::app()->user->setFlash('contact', 'Спасибо за обращение! Мы свяжемся с вами при первой же возможности.');
                $this->refresh();
            }
        }
	
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

/*
	public function actionTranslate(){
		if (Yii::app()->request->isAjaxRequest) {
			setlocale(LC_ALL, 'ru_RU.CP1251');
			$on_request = 5;
			$offset = $on_request*Core::getGet('ofst', 0);
//			$offset = 0;
			$query = "SELECT * FROM geoname WHERE t = 0 LIMIT ".$on_request." OFFSET ".$offset;
			$infos = Yii::app()->db->createCommand($query)->queryAll();
			if ($infos){
				foreach($infos as $info){
					$query = "SELECT * FROM countryinfo WHERE iso_alpha2 = '".$info['country']."'";
					$сinfo = Yii::app()->db->createCommand($query)->queryRow();
					$country = $сinfo?$сinfo['name']:$info['country'];
					$name = trim($info['asciiname']).', '.$country;
					// $name = $info['asciiname'];
					$res  = EGeocoderHelper::getCity($name);
					$is_english = preg_match("/[a-zA-Z]/",$res);

					$model = Geoname::model()->findByAttributes(array('id' => $info['id']));
					if ($res != $name && $res != $info['asciiname'] && Core::translit($res) != $res && !$is_english) {
						$model->t = 1;
						$model->name = $res;

						if (!$model->save()) {
							var_dump($model->getErrors()); die();
						}
						echo($name.' -> '.$model->name.'<br>');
					} else {
						$model->t = 2;
						if (!$model->save()) {
							var_dump($model->getErrors());  die();
  					}
						echo($name.' -> <span style="color:red">Not Founded</span><br>');
					}
				}
			} else {
				echo('done');
			}
		} else {
			?>
<html>
	<head>
		<script type="text/javascript" src="/js/jquery.min.js"></script>
		<script type="text/javascript">
			var cococo = 0;
			$(window).ready(function(){
				var response = '';
				while (gogogo(cococo) != 'done') {

				}
			});

			function gogogo(ofst){
				var resp = '';
				$.ajax({
					url: '/site/translate?ofst='+ofst,
					method: 'POST',
					async: false,
					success: function(data){
						cococo ++;
						if (cococo > 8) {
							cococo = 0;
							$('.log').empty();
							// resp = 'done';
						}

						resp = data;
						$('.log').append(data);
					}
				});
				return resp;
			}
		</script>
	</head>
	<body>
	  <div class="log"></div>
	</body>
</html>
		<?php
		}
	}
*/
}