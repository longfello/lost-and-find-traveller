<?php
/* @var $this PoputchikOrderController */
/* @var $dataProvider CActiveDataProvider */
$cs=Yii::app()->clientScript;
global $days_of_week;
global $cookie; 
$language = Yii::app()->language;



$cover = $order->hotelPhotoLists_cover[0]->path;

$price =(  $order->type == 2 ?  "<span class='price'>".$order->price_from."</span>" :  ("от <span class='price'>".$order->price_from."</span> до <span class='price'>".$order->price_to."</span>")   );


$guest_rate_active = "active";
if($cookie!='')
	$guest_rate_active = ( in_array($order->id_hs,$cookie )?"disable":"active"  );
//$like_url = 'http://'.$_SERVER['SERVER_NAME'].'/'.$language.'/lostFound/index?id_order='.$order->id_lf;
$sum=($order->guest_rating_positive+$order->guest_rating_negative); 
$total = $sum? round((10*$order->guest_rating_positive/$sum),1 ):0;
?>

<div id="hs-card-<?=$order->id_hs?>" class="hs-card clearfix bg_type_<?=$order->type?>">


		<div class="left-col col-md-4 col-sm-4">
			<div class="photo">
				<?php if( $cover ): ?>
					<a href="<?=$cover?>" class="cbox">
						<?php
							$image = Yii::app()->easyImage->thumbOf($cover, 
							  array(
								'resize' => array('width' => 100, 'height' => 100, 'master' => EasyImage::RESIZE_INVERSE),
								'crop' => array('width' => 100, 'height' => 100),
								'type' => 'jpg',
								'quality' => 90,
							  ));
							  echo $image;
						?>
					</a>
				<?php else:  ?>
					<img src="/images/no-photo.png" />
				<?php endif; ?>
			</div>	
				<div  class="guest_rate" >
					<div  class="total_label" >Общая оценка</div>
					<div  class="total" style="color:<?= ($total <5?'#ec1c24;':'#37b34a' )?>;"><? print($total) ;?></div>
                    <div class="row">
                        <div  class="negative_count" ><?=$order->guest_rating_negative?></div>
                        <div  class="positive_count" > <?=$order->guest_rating_positive?></div>
                    </div>
                    <div class="row">
                        <div  class="negative <?=$guest_rate_active?>" rel='<?=$order->id_hs?>'></div>
                        <div  class="positive <?=$guest_rate_active?>" rel='<?=$order->id_hs?>'></div>
                    </div>


				</div>
		</div>
		<div class="right-col col-md-20 col-sm-20">
				<div class="line-1 row">
					<h1><?=$order->title?></h1> 	
					<div class="type type_order_<?=$order->type?>"><?if( $order->type != 2):?><div   data-score="<?=$order->stars_rating?>" data-number="<?=$order->stars_rating?>" class="rate"></div><? endif;?></div>
					<div class="block-clearfix"></div>	
				</div>
				<div class="line-2 row"><span class="col-md-12 col-sm-10 col-xs-24 label adress">Город: <a class="city" href="/hotelService/index?id_city=<?=$order->idSettlement->id_settlement?>"><?=$order->idSettlement->name?></a></span>  <span class="col-md-12 col-sm-14 col-xs-24 label street">Улица:<span class="address"><?=$order->address?></span></span>	</div>
				<div class="line-3 row"><span class="label ">Цена за сутки (в рублях) </span> <div class='prices'><?=$price?></div>	<div class="block-clearfix"></div>				</div>
				<div class="line-4 row">	<?=crop_str($order->description,470)?>		</div>
				

		<div class="block-clearfix"></div>
		<?php if(Yii::app()->user->id == $order->id_user):?>
			<div class="del-icon">	<form method="post" action="/<?=$language?>/HotelService/del_my/<?=$order->id_hs?>"><a class="del" href="/<?=$language?>/HotelService/del_my/<?=$order->id_hs?>" onclick="if(confirm('Вы уверены, что хотите удалить данный элемент?')) $(this).parent().submit(); return false;"></a><input type="hidden" name="returnUrl" value="<?=$_SERVER['REQUEST_URI']?>" /></form>
			<div class="del-label" id="del<?=$order->id_hs?>">Удалить</div>
			</div>
			
			<div class="update-icon">	<form method="post" action="/<?=$language?>/HotelService/update/<?=$order->id_hs?>"><a class="update" href="/<?=$language?>/HotelService/update/<?=$order->id_hs?>" onclick="if(confirm('При изменении, объявление будет повторно отпревлено на модерацию и временно пропадет из публикации.')) $(this).parent().submit(); return false;"></a></form>
			<div class="update-label" id="up<?=$order->id_hs?>">Редактировать</div>
			</div>
		<?php endif; ?>
	


			
			
		
			
			<?php if(Yii::app()->user->checkAccess('PoputchikOrder.moderator')): ?>
			<div class="control-icons">
				<?php if(!$order->status): ?><a class="moderate" href="/<?=$language?>/HotelService/moderate/<?=$order->id_hs?>"></a><?php endif; ?>
				<a class="edit" href="/<?=$language?>/HotelService/update/<?=$order->id_hs?>"></a>
				<form method="post" action="/<?=$language?>/HotelService/delete/<?=$order->id_hs?>"><a class="del" href="/<?=$language?>/HotelService/delete/<?=$order->id_hs?>" onclick="if(confirm('Вы уверены, что хотите удалить данный элемент?')) $(this).parent().submit(); return false;"></a><input type="hidden" name="returnUrl" value="<?=$_SERVER['REQUEST_URI']?>" /></form>
			</div>
			<?php endif; ?>
	



	</div>
    <div class="more_info"><a href="/<?=$language?>/HotelService/details/id/<?=$order->id_hs?>"><?=Yii::t('hotelservice', 'Подробнее')?></a></div>
</div>



