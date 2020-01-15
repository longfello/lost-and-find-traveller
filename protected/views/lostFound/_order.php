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

<div id="lf-order-<?=$order->id_lf?>" class="lf-order clearfix">
  <div class="date_info"><?php echo Yii::app()->dateFormatter->format("EEEE d MMMM", $order->date_lf);?></div>
  <div class="top" >
    <div class="left col-md-5 col-sm-6 col-xs-24 text-center">
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
    <div class="right col-md-19 col-sm-18 col-xs-24">
      <div class="category"><a href="/lostFound/index?category=<?=$order->category?>"><?=$order->idCategory->title?>: </a></div>
        <div class="<? $order->lost_or_found==1 ?print("found"):print("lost"); ?> title"> <?=$order->thing?></div>

      <div class="description" style="overflow: hidden; "> <?=$order->comment?></div>
      <div class="block-clearfix"></div>
      <div class="title settlement"><?=$order->idSettlement->name?></div>
    </div>

    <?php if(Yii::app()->user->id == $order->id_user):?>
      <div class="del-icon">
        <form method="post" action="/<?=$language?>/LostFound/del_my/<?=$order->id_lf?>"><a class="del" href="/<?=$language?>/LostFound/del_my/<?=$order->id_lf?>" onclick="if(confirm('Вы уверены, что хотите удалить данный элемент?')) $(this).parent().submit(); return false;"></a><input type="hidden" name="returnUrl" value="<?=$_SERVER['REQUEST_URI']?>" /></form>
        <div class="del-label" id="del<?=$order->id_lf?>">Удалить</div>
      </div>

      <div class="update-icon">
        <form method="post" action="/<?=$language?>/LostFound/update/<?=$order->id_lf?>"><a class="update" href="/<?=$language?>/LostFound/update/<?=$order->id_lf?>" onclick="if(confirm('При изменении, объявление будет повторно отпревлено на модерацию и временно пропадет из публикации.')) $(this).parent().submit(); return false;"></a></form>
        <div class="update-label" id="up<?=$order->id_lf?>">Редактировать</div>
      </div>
    <?php endif; ?>
  </div>
  <a class="more_info" href="/<?=$language?>/lostFound/details/id/<?=$order->id_lf?>"><?=Yii::t('poputchik', 'Подробнее')?></a>
</div>


