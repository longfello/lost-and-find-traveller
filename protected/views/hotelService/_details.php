<script type="text/javascript">  
$(document).ready(function () {

	var type = 	<?=$order->type?>; 
	if(type ==2){
		$( ".line-6.slots" ).show();	
	}else{
		$( ".line-6.rooms" ).show();	
	}	
});
	
</script>
<?php
/* @var $this PoputchikOrderController */
/* @var $dataProvider CActiveDataProvider */
$cs=Yii::app()->clientScript;
$cs->registerCssFile($baseUrl.'/css/hotel_service.css');
$cs->registerScriptFile('/js/service.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/jquery.cookie.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/rate/jquery.raty.js', CClientScript::POS_HEAD);
$cs->registerCssFile($baseUrl.'/js/rate/jquery.raty.css');
$cs->registerScriptFile('/js/hotelservice.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('http://api-maps.yandex.ru/2.1/?lang=ru_RU', CClientScript::POS_HEAD);
global $days_of_week;
$language = Yii::app()->language;


$colorbox = $this->widget('application.extensions.colorpowered.JColorBox');
$colorbox->addInstance('.cbox', array('maxHeight'=>'80%', 'maxWidth'=>'90%') );


$cover = $order->hotelPhotoLists_cover[0]->path;
$price =(  $order->type == 2 ?  "<span class='price'>".$order->price_from."</span>" :  ("от <span class='price'>".$order->price_from."</span> до <span class='price'>".$order->price_to."</span>")   );

$guest_rate_active = "active";

$cookie = (isset(Yii::app()->request->cookies['id_hs']->value)) ? json_decode( Yii::app()->request->cookies['id_hs']->value, true)   : '';
if($cookie!='')
	$guest_rate_active = ( in_array($order->id_hs,$cookie )?"disable":"active"  );

$sum=($order->guest_rating_positive+$order->guest_rating_negative); 
$total = $sum? round((10*$order->guest_rating_positive/$sum),1 ):0;



//$like_url = 'http://'.$_SERVER['SERVER_NAME'].'/'.$language.'/lostFound/index?id_order='.$order->id_lf;
	

	
?>



<div class="left_column" style="width:600px">
<div id="hs-card-<?=$order->id_hs?>" class="hs-card details clearfix bg_type_<?=$order->type?>">


		<div class="left-col">
			<div class="photo">
				<?php if($cover): ?>
					<a href="<?=$cover?>" class="cbox">
						<?php
							$image = Yii::app()->easyImage->thumbOf($cover, 
							  array(
								'resize' => array('width' => 100, 'height' => 100, 'master' => EasyImage::RESIZE_INVERSE),
								'crop' => array('width' => 100, 'height' => 100),
								'type' => 'jpg',
								'quality' => 60,
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
					<div  class="negative_count" ><?=$order->guest_rating_negative?></div><div  class="positive_count" > <?=$order->guest_rating_positive?></div><div  class="negative <?=$guest_rate_active?>" rel='<?=$order->id_hs?>'></div><div  class="positive <?=$guest_rate_active?>" rel='<?=$order->id_hs?>'></div>
				</div>
		</div>
		<div class="right-col">
				<div class="line-1">
					<h1><?=$order->title?></h1> 	
					<div class="type type_order_<?=$order->type?>"><?if( $order->type != 2):?><div   data-score="<?=$order->stars_rating?>" data-number="<?=$order->stars_rating?>" class="rate"></div><? endif;?></div>
					<div class="block-clearfix"></div>	
				</div>
				<div class="line-2"><span class="label settl">Город: <a class="city" href="/hotelService/index?id_city=<?=$order->idSettlement->id_settlement?>"><?=$order->idSettlement->name?></a></span>  <span class="label street">Улица:<span class="address"><?=$order->address?></span></span>	</div>
				<div class="line-3"><div class="price-row"><span class="label ">Цена за сутки (в рублях) </span> <div class='prices'><?=$price?></div>	<div class="block-clearfix"></div></div><div class="square label">Площадь<span class="square-val"><?=($order->stars_rating." м2")?></span> 	</div>	</div>
			
				<? if(	strlen($order->site_link)!=0 || strlen($order->phones)!=0 ):?>
					<div class="line-contacts">
						<div class="url-row"><a target="blank" rel="nofollow" content="noindex" href="<?=$order->site_link?>"><?  $dots=( strlen($order->site_link) >34 ?" ..." :""); echo substr($order->site_link, 0, 34). $dots;  ?></a></div><div class="phone-row">
							<? if(	 strlen($order->phones)!=0 ):?>
								<a rel="<?=$order->id_hs?>" class="phone">Показать телефон</a>
							<?php endif;  ?>
						</div>
					</div> 
				<?php endif;  ?>
				
				<? if(	strlen($order->place_desc)!=0 ):?>
					<div class="line-4"><?=$order->description?></div>
				<?php endif;  ?>
				<? if(	strlen($order->place_desc)!=0):?>
					<div class="line-5"><?=$order->place_desc?></div>
				<?php endif;  ?>
				
				<? if(	count($order->hotelRoomsLists )!=0):?>
					<div class="line-6 rooms">
					<div class="label">Типы номеров</div>   <div class='list'> 
					<?php foreach($order->hotelRoomsLists as $row ):  ?>
						<div  class="room-type"   ><?=$row->title?> <span class='places-in-room'> (<?=$row->places?> <?=number($row->places, array('место', 'места', 'мест')) ?>)	</span>	</div>
					<?php endforeach;  ?>
					</div>			
					</div>
				<?php endif;  ?>
				<? if(	count($order->hotelRoomsLists )!=0):?>
					<div class="line-6 slots"><div class="label">Количество спальных мест: </div> <?=$order->hotelRoomsLists[0]->places?>		</div>
				<?php endif;  ?>
				<? if(	count($order->hotelServiceLists )!=0):?>
					<div class="line-7 ">
					<div class="label">Услуги</div>   <div class='list'>
					<?php foreach($order->hotelServiceLists as $row ):  ?>
						<div  class="room-type"   ><?=$row->name?> 	</div>
					<?php endforeach;  ?>
					</div>
						</div>
				<?php endif;  ?>
			

		
			
			</div>		<div class="block-clearfix"></div>
			
			<div id="photolist">
				<? if(	count($order->hotelPhotoLists )!=0){
				
					foreach($order->hotelPhotoLists as $photo){
						$image = Yii::app()->easyImage->thumbSrcOf($photo->path, 
								  array(
									'resize' => array('width' => 112, 'height' => 112, 'master' => EasyImage::RESIZE_INVERSE),
									'crop' => array('width' => 112, 'height' => 112),
									'type' => 'jpg',
									'quality' => 90,
								  ));
								  
						echo "<a href='".$photo->path."' class='cbox photo'  rel='albm' >	<img src='".$image."'  /></a>";
				
					} 		
					
				} 
				?>
				</div>

		  <script type="text/javascript">
        var myMap, placemark;


        // Дождёмся загрузки API и готовности DOM.
        ymaps.ready(init);

        function init () {

            myMap = new ymaps.Map('Map', {
                // При инициализации карты обязательно нужно указать
                // её центр и коэффициент масштабирования.
                center: [<? $order->coord_x? print( $order->coord_x): print("55.76") ?>,<? $order->coord_y? print( $order->coord_y): print(" 37.64") ?>], // Москва
                controls: ['smallMapDefaultSet'],
                zoom: 15
            });
			

             placemark = new ymaps.GeoObject({
                geometry: {
                    type: "Point",
                    coordinates: myMap.getCenter(),
			
                },
                properties: {

                }
            }, {
                draggable: false
            });

			placemark.geometry.setCoordinates([<? $order->coord_x? print( $order->coord_x): print("55.76") ?>,<? $order->coord_y? print( $order->coord_y): print(" 37.64") ?>]);
			
            myMap.geoObjects.add(placemark);





        }


        
    </script>


	
    <div id="Map" style="width: 600px; height: 300px; margin-top:15px;"></div>	
				
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
</div>

<div class="block-clearfix"></div>



