<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Profile");
$this->breadcrumbs=array(
	UserModule::t("Profile")=>array('profile'),
	UserModule::t("Edit"),
);


$cs=Yii::app()->clientScript;
$cs->registerScriptFile('/js/jquery-file.js', CClientScript::POS_END );
$cs->registerScriptFile('/js/service.js', CClientScript::POS_END );
/*$this->menu=array(
	((UserModule::isAdmin())
		?array('label'=>UserModule::t('Manage Users'), 'url'=>array('/user/admin'))
		:array()),
    array('label'=>UserModule::t('List User'), 'url'=>array('/user')),
    array('label'=>UserModule::t('Profile'), 'url'=>array('/user/profile')),
    array('label'=>UserModule::t('Change password'), 'url'=>array('changepassword')),
    array('label'=>UserModule::t('Logout'), 'url'=>array('/user/logout')),
);*/
?>



<h1 style="text-align:center;"><?php echo UserModule::t('Edit profile'); ?></h1>

<?php if(Yii::app()->user->hasFlash('profileMessage')): ?>
<div class="success">
<?php echo Yii::app()->user->getFlash('profileMessage'); ?>
</div>
<?php endif; ?>

<div   class='left_menu'>

<?php
		$this->widget('application.extensions.user_menu.user_menu',	array('active_button'=> "profile"	 )); 
?>
</div>


<div id="user-edit" class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'profile-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

	<?php echo $form->errorSummary($model); ?>
    <?php echo $form->errorSummary($profile); ?>
  
<div class="photo">
		<?php            
                if($profile->photo && is_file(Yii::getpathOfAlias('webroot').$profile->photo))
				echo Yii::app()->easyImage->thumbOf($profile->photo, 
				  array(
					'resize' => array('width' => 100, 'height' => 100, 'master' => EasyImage::RESIZE_INVERSE),
					'crop' => array('width' => 100, 'height' => 100),
					'type' => 'jpg',
					'quality' => 60,
				  ));
		?>
    
	<div class="row row-photo">	
		<?php echo CHtml::label(Yii::t('common', 'Фото'),'photo'); ?>
		<?php echo CHtml::fileField('photo'); ?>		
		<?php echo $form->error($model,'photo'); ?>
		<span style="color:rgb(153, 153, 153);">Формат файла: jpg, png, gif<br>Максимальный размер: <?=get_cfg_var('upload_max_filesize')?></span>
		
	</div>
	</div>
    
<?php 

		$profileFields=$profile->getFields();

		if ($profileFields) {
		   foreach($profileFields as $field) {
			?>
	<div class="row">
		<?php echo $form->labelEx($profile,$field->varname);
		
		if ($widgetEdit = $field->widgetEdit($profile)) {
			echo $widgetEdit;
		} elseif ($field->range) {
			echo $form->dropDownList($profile,$field->varname,Profile::range($field->range));
		} elseif ($field->field_type=="TEXT") {
			echo $form->textArea($profile,$field->varname,array('rows'=>6, 'cols'=>50));
		} else {
			echo $form->textField($profile,$field->varname,array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
		}
		echo $form->error($profile,$field->varname); ?>
	</div>	
			<?php
			}
		}

?>
    <div class="row login-row">
        <?php echo $form->labelEx($profile,'phone'); ?>
        <div id="current_phone">+<?=$model->username?></div><a id="change_login">Изменить</a><div class="clearfix"></div>

    </div>

	<div class="row">
		<?php echo $form->labelEx($profile,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>
	
	
	<div class="row">
		<?php  echo "<h3>".Yii::t('common', 'Изменить пароль')."</h3>"; ?>
	</div>
    <div class="pass_wrap">
	<div class="row"> 
		<?php echo CHtml::label(Yii::t('common', 'Старый пароль'),'oldPassword'); ?>
		<?php echo $form->passwordField($modelPass,'oldPassword',array('size'=>30,'maxlength'=>128)); ?>
		<?php echo $form->error($modelPass,'oldPassword'); ?>
	</div>
	<div class="row">
		<?php echo CHtml::label(Yii::t('common', 'Новый пароль'),'password'); ?>
		<?php echo $form->passwordField($modelPass,'password',array('size'=>30,'maxlength'=>128)); ?>
		<?php echo $form->error($modelPass,'password'); ?>
		<p class="hint">
		<?php echo UserModule::t("Minimal password length 6 symbols."); ?>
		</p>
	</div>
	<div class="row">
		<?php echo CHtml::label(Yii::t('common', 'Подтвердите пароль'),'verifyPassword'); ?>
		<?php echo $form->passwordField($modelPass,'verifyPassword',array('size'=>30,'maxlength'=>128)); ?>
		<?php echo $form->error($modelPass,'verifyPassword'); ?>
	</div>
    </div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? UserModule::t('Create') : UserModule::t('Save'),array('class'=>"submit")); ?>
	</div>

    <div class="overlay" style=""></div>
    <div id="form-change_login">
        <a class="close" onclick="close_form(); ;"></a>
        <div id="frame1"><label for="new_login">Введите новый номер</label><div class="inner"><span class="plus">+</span><input name="new_login"  id="new_login" maxlength="13" /></div><br><a id="next_frame">Далее</a></div>
        <div id="frame2"><label for="confirm_code">Введите код подтверждения</label><input name="confirm_code" id="confirm_code" /><a id="ok">Подтвердить</a></div>
        <div id="info_message"></div>
    </div>
<?php $this->endWidget(); ?>



</div><!-- form --><div class="clearfix"></div>


<script>
    $(document).ready(function () {

        $('.row.login-row').insertBefore( $('.row.login-row').prev());
        $("#profile-form #change_login").click(change_login);
        $("#profile-form #next_frame").click(next_frame);
        $("#profile-form #ok").click(confirm_phone);
        $('#new_login').keypress(function (e) {
            if (e.which == 13) {
                e.preventDefault();
                next_frame();
            }
        });
        $('#confirm_code').keypress(function (e) {
            if (e.which == 13) {
                e.preventDefault();
                confirm_phone();
            }
        });
        $(".overlay").click(function() {
            close_form();
        });


    });

    function close_form(){
        hide_window("#form-change_login")
        $("#form-change_login #frame1").hide( );
        $("#form-change_login #frame2").hide( );
        $("#info_message").hide();
        $("#new_login").val('');
        $("#confirm_code").val('');
    }

    function  confirm_phone ()
    {
        $("#info_message").hide();
        jQuery.ajax({
            type: "POST",
            async: false,
            dataType: "json",
            url:"/user/Profile/GetConfirm",
            data: { code: $("#confirm_code").val()	  },
            error: function (data) {
                $(" #info_message").show();
                $(" #info_message").html("Ошибка связи с сервером!");
                $(" #info_message").css("color","red");
            },
            success: function(val){

                if( val==1){
                    $("#current_phone").html( "+"+$("#new_login").val() );
                    $("#info_message").show();
                    $("#info_message").html("Телефон изменен!");
                    $("#info_message").css("color","green");
                }else if( val==2)   {
                    $(" #info_message").html("Не удалось сохранить!");
                    $(".login-row #info_message").css("color","red");
                }else{
                    $(" #info_message").show();
                    $(" #info_message").html("Неверный код!");
                    $(" #info_message").css("color","red");
                }

            }
        });
    }

    function  change_login ()
    {
        $("#form-change_login #frame1").show( );
        show_window('#form-change_login');
    }

    function  next_frame ()
    {
        $("#info_message").hide();
        if( $("#new_login").val() ){
            jQuery.ajax({
                type: "POST",
                async: false,
                dataType: "json",
                url:"/user/Profile/SetConfirm",
                data: { 	phone:  $("#new_login").val() },
                error: function (data) {
                    $(" #info_message").show();
                    $(" #info_message").html("Ошибка связи с сервером!");
                    $(" #info_message").css("color","red");
                },
                success: function(val){

                    if( val==1){
                        $("#form-change_login #frame2").show( );
                        $("#form-change_login #frame1").hide( );
                    }else if( val==2){
                        $(" #info_message").show();
                        $(" #info_message").html("Данный телефон уже используется!");
                        $(" #info_message").css("color","red");

                    }else{
                        $(" #info_message").show();
                        $(" #info_message").html("SMS не отправилась!");
                        $(" #info_message").css("color","red");
                    }

                }
            });
        }

    }
</script>
