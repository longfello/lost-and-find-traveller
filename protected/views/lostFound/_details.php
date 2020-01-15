<?php
/* @var $this PoputchikOrderController */
/* @var $dataProvider CActiveDataProvider */
$cs=Yii::app()->clientScript;
$cs->registerCssFile('/css/lostservice.css');
$cs->registerScriptFile('/js/lostservice.js', CClientScript::POS_HEAD);
global $days_of_week;
$language = Yii::app()->language;
$profile = Profile::model()->find(array('condition'=>'user_id=:user_id', 'params'=>array(':user_id'=>$order->id_user)));

$user = User::model()->find(array('condition'=>'id=:id', 'params'=>array(':id'=>$order->id_user)));

//Call addInstance (chainable) from the widget generated.
$colorbox = $this->widget('application.extensions.colorpowered.JColorBox');
$colorbox->addInstance('.cbox');

$like_url = 'http://buronahodok.'.$_SERVER['SERVER_NAME'].'/'.$language.'/lostFound/index?id_order='.$order->id_lf;
$photo_url = 'http://buronahodok.'.$_SERVER['SERVER_NAME'].$order->photo;

?>


<div class="wraper-for-marging trip-page">

  <?


  $breadcr = array();
  $breadcr[] = 'Бюро находок - '.$order->thing;
  $this->breadcrumbs = $breadcr;
  ?>

<div class="left_column" style="width:600px">

  <div id="lf-order-<?=$order->id_lf?>" class="lf-order clearfix">
    <div class="top" >
      <div class="left">
        <div class="photo">
          <?php if($order->photo): ?>
            <a href="<?=$order->photo?>" class="cbox">

              <?php
                            if( is_file( $_SERVER["DOCUMENT_ROOT"] .$order->photo) ){

                $image = Yii::app()->easyImage->thumbOf($order->photo,
                  array(
                  'resize' => array('width' => 100, 'height' => 100, 'master' => EasyImage::RESIZE_INVERSE),
                  'crop' => array('width' => 100, 'height' => 100),
                  'type' => 'jpg',
                  'quality' => 60,
                  ));
                  echo $image;
                                            }

              ?>
            </a>
          <?php else:  ?>
            <img src="/images/no-photo.png" />
          <?php endif; ?>
        </div>
      </div>
      <div class="right">
        <div class="category"><a href="/lostFound/index?category=<?=$order->category?>"><?=$order->idCategory->title?></a></div>
        <div class="<? $order->lost_or_found==1 ?print("found"):print("lost"); ?> title"> <?=$order->thing?></div>
        <div class="description" style="overflow: hidden;"> <?=$order->comment?></div>
        <div class="block-clearfix"></div>
      </div>

        <?php if(Yii::app()->user->id == $order->id_user):?>
        <div class="del-icon">	<form method="post" action="/<?=$language?>/LostFound/del_my/<?=$order->id_lf?>"><a class="del" href="/<?=$language?>/LostFound/del_my/<?=$order->id_lf?>" onclick="if(confirm('Вы уверены, что хотите удалить данный элемент?')) $(this).parent().submit(); return false;"></a><input type="hidden" name="returnUrl" value="<?=$_SERVER['REQUEST_URI']?>" /></form>
        <div class="del-label" id="del<?=$order->id_lf?>">Удалить</div>
        </div>

        <div class="update-icon">	<form method="post" action="/<?=$language?>/LostFound/update/<?=$order->id_lf?>"><a class="update" href="/<?=$language?>/LostFound/update/<?=$order->id_lf?>" onclick="if(confirm('При изменении, объявление будет повторно отпревлено на модерацию и временно пропадет из публикации.')) $(this).parent().submit(); return false;"></a></form>
        <div class="update-label" id="up<?=$order->id_lf?>">Редактировать</div>
        </div>
      <?php endif; ?>
    </div>
    <div class="bottom" >
      <div class="left">
        <div class="small-gray-row"><? $order->lost_or_found==1 ?print("Кто нашел:"):print("Кто потерял:"); ?></div>
        <div class="name"><?=$profile ->second_name?> <?=$profile->first_name?></div>
        <a href="#" class="ajax phone" rel="<?=$order->id_lf?>"><?=Yii::t('general', 'Номер телефона')?></a>
        <?php if($user->email != ""):  ?>
          <a href="#" class="ajax email" rel="<?=$order->id_user?>"><?=Yii::t('general', 'Написать автору')?></a>
        <?php endif; ?>
      </div>
      <div class="right">
        <div class="date">	<div class="small-gray-row"><?$order->lost_or_found==1? print("Когда нашел:"):print("Когда потерял:");?></div> <div class="icon"></div><?=$order->date_lf?></div>
        <div class="small-gray-row"><? $order->lost_or_found==1 ?print("Место находки:"):print("Место потери:"); ?></div>
        <div class="title settlement"><?=$order->idSettlement->name?></div>


        <div style="overflow: hidden;" class="description"><?$order->idSaSettlement?print($order->idSaSettlement->name.", "):"";?><?=$order->place_disc?></div>


        <?php if(Yii::app()->user->checkAccess('PoputchikOrder.moderator')): ?>
        <div class="control-icons">
          <?php if(!$order->status): ?><a class="moderate" href="/<?=$language?>/lostFound/moderate/<?=$order->id_lf?>"></a><?php endif; ?>
          <a class="edit" href="/<?=$language?>/lostFound/update/<?=$order->id_lf?>"></a>
          <form method="post" action="/<?=$language?>/lostFound/delete/<?=$order->id_lf?>"><a class="del" href="/<?=$language?>/lostFound/delete/<?=$order->id_lf?>" onclick="if(confirm('Вы уверены, что хотите удалить данный элемент?')) $(this).parent().submit(); return false;"></a><input type="hidden" name="returnUrl" value="<?=$_SERVER['REQUEST_URI']?>" /></form>
        </div>
        <?php endif; ?>
      </div>
      <div class="block-clearfix"></div>
      <div class="like-buttons">
        <div class="share42init"    data-image="<?=$photo_url?>"   data-url="<?=$like_url?>" data-title="<?$order->lost_or_found==1 ?print("Нашел: "):print("Потерял: ");print($order->thing); ?>"></div>
      </div>
    </div>
  </div>

</div>
<div class="right_column" style="width: 310px;float: left; margin-top: 15px;">
  <div>
    <div>
      <?php
      $this->widget('application.extensions.service_menu.service_menu', array('buttons' => array(array('idx' => '8', 'href' => '/lostFound/create?type_order=1'), array('idx' => '9', 'href' => '/lostFound/create?type_order=0'),)));
      ?><div class="block-clearfix"></div>


      <a href="http://<?=SERVICE_NEPOTERAYKA?>.<?=Yii::app()->params['domain']?>/"><img src="/images/banner.png" ></a>
    </div>
  </div>

</div>

<div class="clearfix"></div>







</div>





<div class="overlay"></div>
<div class="my_popup">

  <div class="form">
    <?php
    $ContactForm = new ContactForm;


    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'contact-form',
        'enableClientValidation' => true,
        'action' => CHtml::normalizeUrl(Yii::app()->request->baseUrl . '/poputchikOrder/sendemail'),
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>
    <?php echo CHtml::hiddenField('user'); ?>
    <div class="title">Письмо автору</div>
    <div class='left'>
      <?php echo CHtml::label('Ваше имя', 'text'); ?>
      <?php echo CHtml::textField('name'); ?>

      <?php echo CHtml::label('Ваш E-mail', 'user'); ?>
      <?php echo CHtml::emailField('sender'); ?>
    </div><div class='right'>
      <?php echo CHtml::label('Текст', 'text'); ?>
      <?php echo CHtml::textArea('text', '', array('rows' => 8, 'cols' => 50, 'style' => 'resize:none')); ?>
    </div><div class="block-clearfix"></div>
    <div style="width:100%;height: 60px;position: relative;text-align: right;">
      <?php $this->widget('CCaptcha'); ?>
      <?php echo CHtml::textField('captcha'); ?>
      <?php echo CHtml::button('Отправить', array('id' => 'button')); ?>
    </div>



    <div id="contact_message"></div>
    <?php $this->endWidget(); ?>

  </div><!-- form -->

</div>


