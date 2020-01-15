<div id="account-menu">
  <?php if (Yii::app()->user->isGuest): ?>
    <a class="login ajax" href="#"><?= Yii::t('general', 'Войти') ?></a>
    <div id="login-form" class="form ext" style="display: none;"><form method="POST" action="/general/ajax_login">
        <?php  $this->widget('application.components.UloginWidget', array(
            'params'=>array(
                'redirect'=>$this->createAbsoluteUrl('/ulogin/login')
            )
        )); ?>

        <div class="roww"><span class="plus2">+</span><?php echo CHtml::textField('UserLogin[username]', $value = '', array('defaultvalue' => Yii::t('general', 'Телефон'), 'class' => 'textfield', 'maxlength' => 13)); ?></div>
        <div class="roww"><?php echo CHtml::textField('UserLogin[password]', $value = '', array('defaultvalue' => Yii::t('general', 'Пароль'), 'class' => 'textfield')); ?></div>
        <div class="roww button_enter" style="margin-bottom: 0;"><?php echo CHtml::submitButton(Yii::t('general', 'Войти')); ?>&nbsp;&nbsp;&nbsp;&nbsp;<a class="needpass ajax" href = "javascript:;"><?= Yii::t('general', 'Забыли пароль?') ?></a></div>
        <div class="errorSummary" style="display: none;"><?= Yii::t('general', 'Неверный телефон<br /> или пароль') ?></div>
      </form></div>

    <div id="needpass-form" class="form ext" style="display: none;"><form method="POST" action="/general/ajax_sendpass">
        <div class="roww"><span class="plus2">+</span><?php echo CHtml::textField('UserLogin[phone]', $value = '', array('defaultvalue' => Yii::t('general', 'Телефон'), 'class' => 'textfield', 'maxlength' => 13)); ?></div>
        <div class="roww buttons" style="margin-bottom: 0;"><?php echo CHtml::submitButton(Yii::t('general', 'Прислать новый пароль'), array('class' => 'sendpass')); ?></div>
        <div class="errorSummary" style="display: none;"><?= Yii::t('general', 'Данный номер телефона не зарегестрирован') ?></div>
        <div class="success" style="display: none;"><?= Yii::t('general', 'Ваш пароль успешно изменен и отправлен через SMS!') ?></div>
      </form></div>
  <?php else: ?>

    <?php
      echo '<a tabindex="-1" href="/general/cabinet">Личный кабинет</a>';
      echo '<a tabindex="-1" href="/user/logout">Выйти</a>';
      /*echo BsHtml::pills(array(
          array('label' => Yii::t('common', 'Личный кабинет'),'url' => "/general/cabinet"),
          array('label' => Yii::t('common', 'Выйти'),'url' => '/user/logout'),
      ), array('justified' => true));*/
    ?>
  <?php endif; ?>
</div>
