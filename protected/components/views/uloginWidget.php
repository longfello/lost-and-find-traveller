<?php if (Yii::app()->user->isGuest): ?>

<div id="<?=$id?>" x-ulogin-params="display=<?php echo $display ?>;fields=<?php echo $fields ?>;providers=<?php echo $providers ?>;hidden=<?php echo $hidden ?>;redirect_uri=<?php echo urlencode($redirect) ?>"></div>

<?php else: ?>
    <?
    $anchor = 'Выйти ('.Yii::app()->user->getName().')';
    echo CHtml::link($anchor, array($logout_url));
    ?>
<?php endif ?>