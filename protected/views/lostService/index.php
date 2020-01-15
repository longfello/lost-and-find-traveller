<?php
/* @var $this LostServiceController */
/* @var $dataProvider CActiveDataProvider */

$cs=Yii::app()->clientScript;
$cs->registerCssFile($baseUrl.'/css/lostservice.css');
?>
<div class="row row-1">
	<div class="left return-title col-xs-12"><h1>Возвращаем утерянное!</h1></div>
	<div class="right"></div>
	<div class="block-clearfix"></div>
</div>
<div class="row row-2 four-img row-none">
	<img src="/images/chain.png" />
</div>
<div class="row row-3 row-none">
		<div class="info col-md-6 col-sm-24 col-xs-24">Вы прикрепляете наклейки и брелок на ценные вещи (паспорт, ключи, телефон и др). Активируете комплектв разделе "Активация" или позвонив по номеру <b>8-800-55-05-155 </b>.</div>
		<div class="info col-md-6 col-sm-24 col-xs-24">При обнаружении ваших вещей, нашедший сообщит о находке оператору контакт-центра или через форму на сайте сервиса.</div>
		<div class="info col-md-6 col-sm-24 col-xs-24">Сотрудник службы возврата на основании уникального номера ID, указанного на наклейке или брелоке, определит владельца и организует возврат.</div>
		<div class="info col-md-6 col-sm-24 col-xs-24">Служба возврата "Непотеряйка" обеспечит выплату вознаграждения человеку, вернувшему найденную вещь.</div>
</div>
<div class="row row-4 row-none">
	<div class="left col-md-12 col-sm-24 col-xs-24">
		<b>Непотеряйка</b> - это служба возврата утерянных вещей, сервис без абонентской платы и срока действия.<br><b>Непотеряйка</b> - это своего рода страховка, гарантия возврата. Защитив свои личные вещи сегодня (ключи, телефон, планшет, паспорт, кошелёк, банковские карты и др.) завтра они вернутся в случае их утери.  Закажите комплект с брелоками и наклейками «Непотеряйка» и необходимые дополнения к нему чтобы иметь возможность вернуть утерянное.
		<a class="green_button2">Купить набор Непотеряйка</a>		
	</div>
	<div class="right col-md-12 col-sm-24 col-xs-24">
		<div class="banner">  
			<div class="top_line">Что делать если ... ?</div>
			<div class="bottom_line">
				<a href="/LostService/how_its_work" class="choise choise-1">Купил</a>
				<a href="/LostService/ifind" class="choise choise-2">Нашел</a>
				<a href="/LostService/lost" class="choise choise-3">Потерял</a>			
			</div>
		</div> 	
	</div>
	<div class="block-clearfix"></div>
</div>