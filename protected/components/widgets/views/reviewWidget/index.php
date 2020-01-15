<div id="review">
  <div class='smail' id="frame1">
    <div id="quest1"><!--<img src="/images/icon-question.png" alt=""/>--><span><?= Yii::t('main', "Помог ли Вам наш сервис?") ?></span></div>
    <div class='button' rel='1' id="review_1"><?= Yii::t('main', "Да") ?></div>
    <!--
                    -->
    <div class='button' rel='2' id="review_2"><?= Yii::t('main', "Нет") ?></div>
    <!--
                    -->
    <div class='button' rel='3' id="review_3"><?= Yii::t('main', "Отчасти") ?></div>
  </div>
  <div class='smail' id="frame2" style="display:none;">
    <div id="quest2"><?= Yii::t('main', "Поделитесь с нами вашим пожеланием") ?></div>
    <textarea id="review-text" name='review-text' maxlength="400" placeholder="<?= Yii::t('main', "Расскажите нам по секрету…") ?>"></textarea>
    <br>
    <div class='review2' rel='4' id="review_4"><?= Yii::t('main', "Отправить") ?></div>
    <!--
                    -->
    <div class='review2' rel='5' id="review_5"><?= Yii::t('main', "Нет спасибо") ?></div>
  </div>
  <div id="frame3" style="display:none;">
    <div id="quest3"><?= Yii::t('main', "Спасибо! Благодаря Вам сервис станет лучше.") ?></div>
  </div>
  <div id="frame4" style="display:none;">
    <div id="quest4"><?= Yii::t('main', "Извините, ошибка! Попробуйте еще раз позже.") ?></div>
  </div>
</div>

<?php
  Yii::app()->clientScript->registerScript('reviewWidget', '$("#review_1, #review_2, #review_3, .review2").on("click", send_review);', CClientScript::POS_READY);