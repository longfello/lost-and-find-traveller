  <script type="text/javascript">
  
	var from = 	<?=($model->price_from?$model->price_from :500)?>	; 
	var to =  	<?=($model->price_to?$model->price_to :50000)?>  ; 
	var login =  <?=!$userdata['phone']? 1:0 ?>;

    </script>

<?php




/* @var $this HotelServiceController */
/* @var $model HotelService */
/* @var $form CActiveForm */
$cs=Yii::app()->clientScript;
$cs->registerScriptFile('/js/hotelservice-create.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('http://api-maps.yandex.ru/2.1/?lang=ru_RU', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/jquery.ui.mask.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/service.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/rate/jquery.raty.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/jquery.resizecrop-1.0.3.min.js', CClientScript::POS_HEAD);
$cs->registerCssFile($baseUrl.'/css/hotel_service.css');
$cs->registerCssFile($baseUrl.'/js/rate/jquery.raty.css');


if($model->id_settlement)
    $def_city = Settlements::model()->findByPk($model->id_settlement);



?>

    <script type="text/javascript">
        var myMap, placemark;


        // Дождёмся загрузки API и готовности DOM.
        ymaps.ready(init);

        function init () {

            myMap = new ymaps.Map('Map', {
                // При инициализации карты обязательно нужно указать
                // её центр и коэффициент масштабирования.
                center: [<? $model->coord_x? print( $model->coord_x): print("55.76") ?>,<? $model->coord_y? print( $model->coord_y): print(" 37.64") ?>], // Москва
                controls: ['smallMapDefaultSet'],
                zoom: 8
            });
			

             placemark = new ymaps.GeoObject({
                geometry: {
                    type: "Point",
                    coordinates: myMap.getCenter()
                },
                properties: {

                }
            }, {
                draggable: true
            });

			placemark.geometry.setCoordinates([<? $model->coord_x? print( $model->coord_x): print("55.76") ?>,<? $model->coord_y? print( $model->coord_y): print(" 37.64") ?>]);
			
            myMap.geoObjects.add(placemark);


            myMap.events.add('click', function (e) {
                var coords = e.get('coords');
                placemark.geometry.setCoordinates(coords);

            });



        }


        function searchCityOnMap( City){
            var myGeocoder = ymaps.geocode(City);
            myGeocoder.then(
                function (res) {

                    if (res.geoObjects.getLength()) {

                        // point - первый элемент коллекции найденных объектов

                        var point = res.geoObjects.get(0).geometry.getCoordinates();
                        bounds = res.geoObjects.get(0).properties.get('boundedBy');

                        placemark.geometry.setCoordinates(point);
                        myMap.setBounds(bounds, {
                            checkZoomRange: true // проверяем наличие тайлов на данном масштабе.
                        });



                    }
                },
                // Обработка ошибки
                function (error) {
                    alert("Возникла ошибка: " + error.message);
                }
            );

        }
    </script>




<div class="form ext container-clearfix">





<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'hotel-service-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<div class="choice-block">
		<a class="choice hotel" rel="0" href="#"><?=Yii::t('hotelservice', 'Гостиница')?></a>
		<a class="choice motel" rel="1" href="#"><?=Yii::t('hotelservice', 'Мотель')?></a>
		<a class="choice apart" rel="2" href="#"><?=Yii::t('hotelservice', 'Квартира')?></a>
		<?php echo $form->hiddenField($model,'type',array('value'=>$_GET['type'] )); ?>
	</div>




	<div class="row row-1">
		<?php echo $form->labelEx($model,'title'); ?>
		<div class="inner"><?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'title'); ?>			
		<div class="errorMessage" style="display:none;"></div>
		</div><div class="block-clearfix"></div>
		
	</div>
    <div id="start_settlement-wrapper" class="row row-2">
        <?php echo CHtml::label(Yii::t('poputchik', 'Населённый пункт'),'id_settlement', array('required' => true)); ?>
        <div class="inner"><?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
                'name'=>'settlement_name_1',
                'source' =>'js:function(request, response) {
					$.getJSON("'.$this->createUrl('settlements/autocomplete').'", {
						term: request.term.split(/,s*/).pop(),
						country_id: $("#country_1").val()
					}, response);

				}',
                // additional javascript options for the autocomplete plugin
                'options'=>array(
                    'minLength'=>'1',
                    'showAnim'=>'fold',
                    'delay'=>500,


                    'select' =>'js: function(event, ui) {

                        searchCityOnMap(ui.item.value);
						this.value = ui.item.value;
						// записываем полученный id в скрытое поле
						$("#id_settlement").val(ui.item.id);
						ajax_server_error_hide();

						return false;
					}',
                    'response' =>'js: autoCompleteResponse',
                ),
                'htmlOptions'=>array(
                    'size'=>'20',
                ),
                'value'=>$def_city->name,

            ));	?>
            <?php echo  $form->hiddenField($model,'id_settlement',array('id'=>'id_settlement')); ?>
            <div class="errorMessage" style="display:none;"></div></div><div class="block-clearfix"></div>
    </div>

	<div class="row row-3">
		<?php echo $form->labelEx($model,'address'); ?>
		<div class="inner"><?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>300)); ?>
		<?php echo $form->error($model,'address'); ?>
		<div class="errorMessage" style="display:none;"></div>
		</div><div class="block-clearfix"></div>
	</div>

	
	
	
	
    <div id="Map" style="width: 600px; height: 300px"></div>

    <div class="row row-4">
		<?php echo $form->labelEx($model,'place_desc'); ?>
		<?php echo $form->textArea($model,'place_desc',array('placeholder'=>'Опишите место','rows'=>4,'size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'place_desc'); ?>
	</div>

	<div class="row row-5">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('placeholder'=>'Может вы что-то хотите добавить?','rows'=>4,'size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	
	<div class="row row-6">
		<div class="row-rate" style=" float: left;">
		<?php echo $form->labelEx($model,'stars_rating'); ?>
		<div id="rate"></div>
		<?php echo $form->hiddenField($model,'stars_rating'); ?>
		<?php echo $form->error($model,'stars_rating'); ?>
		</div>
		<div class="row-square"  style="    float: left;">
		<?php echo $form->labelEx($model,'square'); ?>
		<?php echo $form->textField($model,'square'); ?>
		<?php echo $form->error($model,'square'); ?>
		</div>
		
		<div class="row-phone" style="    float: right;">
		<?php echo $form->labelEx($model,'phones'); ?>
		<?php echo $form->textField($model,'phones',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'phones'); ?>
		</div>
		<div class="block-clearfix"></div>
	</div>
	
	

	<div class="row row-7">
		<?php echo $form->labelEx($model,'site_link'); ?>
		<?php echo $form->urlField($model,'site_link',array('size'=>60,'maxlength'=>300)); ?>
		<?php echo $form->error($model,'site_link'); ?>
	</div>


	

	<?php echo $form->hiddenField($model,'coord_x'); ?>
	<?php echo $form->hiddenField($model,'coord_y'); ?>



  <fieldset class="price">
	
		<div class="ref">*Все цены в рублях</div>
        <div class="legend">Цены</div>
		<div class="hint hint-1">От</div>
		<?php echo $form->textField($model,'price_from',array('maxlength'=>5) ); ?>
		<div id="slider-range"></div>
		<div id="slider-range2"></div>
	    <div class="hint hint-2">До</div>
		<?php echo $form->textField($model,'price_to',array('maxlength'=>5)); ?>
		<div class="block-clearfix"></div>
	
   </fieldset>

     

		
	<div id="place-row" class="row place-row">
	        <label>Спальные места</label>
		<?php  echo CHtml::dropDownList('places', ( isset($model->hotelRoomsLists[0]->id_rt)?$model->hotelRoomsLists[0]->id_rt:0 ) ,     $userdata['places']  ,     
			  array('empty' => 'Выберите количество спальных мест'));
		?>
		 	<div class="block-clearfix"></div>
	</div>
	
    <fieldset id="room_group" name="room_group" >
	    <div class="group">
			<div class="legend">Тип номеров</div>
			<?php foreach($userdata['rooms'] as $row ):  ?>
				<input type="checkbox" name="rooms[]" <? in_array($row,  $model->hotelRoomsLists)?print("checked"):""  ?>   class="rooms"  id="room<?=$row->id_rt ?>" value="room<?=$row->id_rt ?>"/><label for="room<?=$row->id_rt?>"><span></span><? echo $row->title." <div>(". $row->places." ".number($row->places, array('место', 'места', 'мест')).")</div>"; ?></label></br>
			<?php endforeach;  ?>
		</div>
    </fieldset>


    <fieldset id="serices_group" name="serices_group" >
	   <div class="group">
			<div class="legend">Услуги</div>
			<?php foreach($userdata['services'] as $row ):  ?>
				<input type="checkbox" name="services[]" <? in_array($row,  $model->hotelServiceLists)?print("checked"):""  ?> class="services" id="ser<?=$row->id_sl ?>" value="ser<?=$row->id_sl ?>"/><label for="ser<?=$row->id_sl?>"><span></span><?=$row->name?></label></br>
			<?php endforeach;  ?>
		</div>
    </fieldset>



   
	
	<div class="row row-photo" style="width: 610px;">
	
		<div id="photolist">
			<? 
			$cover;
			foreach($model->hotelPhotoLists as $photo){
					$image = Yii::app()->easyImage->thumbSrcOf($photo->path, 
							  array(
								'resize' => array('width' => 112, 'height' => 112, 'master' => EasyImage::RESIZE_INVERSE),
								'crop' => array('width' => 112, 'height' => 112),
								'type' => 'jpg',
								'quality' => 90,
							  ));
							  
					echo "<div class='photo ".($photo->cover? "cover":"")."' rel='".basename($photo->path)."' >	<img src='".$image."'  /><div class='close'></div>". ($photo->cover? "<div class='info_line'>Обложка</div>":"") ."</div>";
					if($photo->cover) $cover =basename($photo->path);
				} 
			
				for($i=0; $i< 5-count($model->hotelPhotoLists); $i++ )
					echo "<div class='slot'> </div>";	
			
			?>
		
		</div>	
		<div id='browse_button' style="display:<?=(count($model->hotelPhotoLists)>=5?"none":'' ) ?>; left: <?=count($model->hotelPhotoLists)*122 ?>px;">Добавить фото</div> 

	    <input type="hidden" name="cover"  id="cover" value="<?=$cover?>"/>
		
		<div class="attachments"> 
			<? foreach($model->hotelPhotoLists as $photo){	
				echo "<input class='attach' type='hidden' name='attachments_photo[]' value='".basename($photo->path)."' />";
				} 
			?>
		</div>
		<div id="filelist"></div>
	</div>
	
	



	
	

    <?php
		$this->widget('application.extensions.Plupload.PluploadWidget', array(
			'config' => array(
				'up_name' => "up_name",
				'browse_button' => 'browse_button',
				'filelist' => 'filelist',
				'files_count' => 5,
				'FileUploaded' => 'PhotoUploaded',
				'mime_types' => array(array('title' => Yii::t('files', 'Изображения'), 'exts' => 'jpg,jpeg,gif,png')),
				'url' => '/hotelService/AddPhoto',
			),
		));
    ?>
    <div class="row buttons phone">

	<?php 
		$options ;
		if( !$userdata['phone'] ){
	
		?>

		<?php echo CHtml::label(Yii::t('hotelservice', 'Логин'),'login_phone', array('required' => true  )); ?>
		<div class="inner" style="margin-right: 20px;"><?php echo CHTML::textField('phone',$userdata['phone'],array('id'=>'login_phone','size'=>28,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'phone'); ?>
		<div class="errorMessage" style="display:none;"></div>
		</div>
	<? }else{
		$login = ($userdata['phone']);
		$options = array('style'=>'float:none;' ) ;
		echo CHTML::hiddenField('phone',	$login ,array('id'=>'login_phone') ) ;
		}
	?>
		<div id="submit-wrapper">
		<div class="inner"><?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить изменения',$options  ); ?><div class="errorMessage" style="display:none;"></div></div>
		<div class="block-clearfix"></div>
		</div>
	</div>




<?php $this->endWidget(); ?>

</div><!-- form -->

<?php
Yii::app()->clientScript->registerScript('autocomplete', "

    jQuery('#settlement_name_1').focus(function(){
            $(this).autocomplete('search', $(this).val());
  }).data('autocomplete')._renderItem = function( ul, item ) {
    return $('<li></li>')
      .data('item.autocomplete', item)
      .append( '<a>' + item.label + '<br><span class=\"desc\">' + item.desc + '</span></a>' )
      .appendTo(ul);
  };  ",  CClientScript::POS_READY);

?>

