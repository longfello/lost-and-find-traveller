<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?49"></script>
<script type="text/javascript">
    VK.init({apiId: 4282513, onlyWidgets: true});
</script>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

<?php
/* @var $this LostFoundController */
/* @var $dataProvider CActiveDataProvider */
$cs=Yii::app()->clientScript;
$cs->registerCssFile($baseUrl.'/css/hotel_service.css');
$cs->registerScriptFile('/js/service.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/jquery.cookie.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/rate/jquery.raty.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/hotelservice.js', CClientScript::POS_HEAD);
$cs->registerCssFile($baseUrl.'/js/rate/jquery.raty.css');

if($pages) {
    $current_page = $_GET['page'];
    if(!$current_page) $current_page = 1;
    $start_page = $current_page - 4;
    if($start_page < 1) $start_page = 1;
    $end_page = $current_page + 5;
    if($end_page > $pages) $end_page = $pages;
    else if ($end_page < 10) $end_page = 10;
}


$text_not_results="<div style='text-align:center;'>По вашему запросу ничего не найдено.</div>";
global $cookie ;
 $cookie = (isset(Yii::app()->request->cookies['id_hs']->value)) ? json_decode( Yii::app()->request->cookies['id_hs']->value, true)   : '';


$colorbox = $this->widget('application.extensions.colorpowered.JColorBox');
$colorbox->addInstance('.cbox');


?>



<?php if($_GET['ajax']): ?>

    <?php if($orders): ?>
        <?php foreach($orders as $order): ?>
           <?php // $this->renderPartial('_order', array('order'=>$order)); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <?= $text_not_results ?>
    <?php endif; ?>

<?php else: ?>

    <div class="wraper-for-marging">
        <div class="left_column"><div class="header"><div id="anchor"></div><h1><?=Yii::t('lostfound', 'Сервис Ночлег')?></h1></div>
            <div id="lf-orders-filter" class="lf-orders-filter container-clearfix form ext">
                <form action="/hotelService/index" method="post" id="filter-form" >
                    <div class="type-order">
						<input type="checkbox" name="main_type" id="main_type" /><label for="main_type"><span></span><?=Yii::t('hotelservice', 'Ищу все')?></label>
						<input type="checkbox" <? (isset($_SESSION['hs_filter']['type'][0]) || !isset($_SESSION['hs_filter']['type']))? print("checked"):""; ?> name="type[0]" class="type" id="type_1" value="0"/><label for="type_1"><span></span><?=Yii::t('hotelservice', 'Гостиницы')?></label>
						<input type="checkbox" <? (isset($_SESSION['hs_filter']['type'][1]) || !isset($_SESSION['hs_filter']['type']))? print("checked"):""; ?> name="type[1]" class="type" id="type_2" value="1"/><label for="type_2"><span></span><?=Yii::t('hotelservice', 'Мотели')?></label>
						<input type="checkbox" <? (isset($_SESSION['hs_filter']['type'][2]) || !isset($_SESSION['hs_filter']['type']))? print("checked"):""; ?> name="type[2]" class="type" id="type_3" value="2"/><label for="type_3"><span></span><?=Yii::t('hotelservice', 'Квартиры')?></label>
						<div class="block-clearfix"></div>
                    </div>

                    <div id="settlement-wrapper" class="row col-xs-24">
                        <div class="inner"><?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
                                'name'=>'settlement_name',
                                'source' =>'js:function(request, response) {
                                    $.getJSON("'.$this->createUrl('settlements/autocomplete').'", {
                                        term: request.term.split(/,s*/).pop(),
                                        region_id: $("#region_1").val()
                                    }, response);

                                }',
                                // additional javascript options for the autocomplete plugin
                                'options'=>array(
                                    'minLength'=>'1',
                                    'showAnim'=>'fold',
                                    'delay'=>500,
                                    'select' =>'js: function(event, ui) {
										this.value = ui.item.value;
										// записываем полученный id в скрытое поле
										$("#settlement_id").val(ui.item.id);
										return false;
									}',
                                    'response' =>'js: autoCompleteResponse',
                                ),
                                'htmlOptions'=>array(
                                    'size'=>'50',
                                    'defaultValue'=>Yii::t('hotelservice', "Город")
                                ),
                                'value'=>$_SESSION['hs_filter']['settlement_name'],
                            )); 	?>
                            <?php echo CHtml::hiddenField('settlement_id', $_SESSION['hs_filter']['settlement_id']); ?>
                            <div class="errorMessage" style="display:none;"></div></div><div class="block-clearfix"></div>
                    </div>
                    <div id="settlement-arrow" class="row"></div>
					
					<hr>
					<div class="price">				
						<div style="margin-bottom: 10px; color:#58595b; font-weight:700;">Цена за сутки <span style="color:#6d6e70; font-weight:400; font-style:italic;">(в рублях)</span></div>
                        <div class="row">
                            <div class="col-md-5 col-sm-5 col-xs-24">
                                <div class="hint hint-1">От</div>
                                <?php echo  CHtml::textField('price_from',$_SESSION['hs_filter']['price_from'],array('maxlength'=>5)); ?>
                            </div>
                            <div class="col-md-14 col-sm-14 col-xs-24 fr-out">
                                <div id="filter-range"></div>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-24">
                                <div class="hint hint-2">До</div>
                                <?php echo CHtml::textField('price_to',$_SESSION['hs_filter']['price_to'],array('maxlength'=>5)); ?>
                            </div>
                        </div>
					</div>					
				
<!--					<hr style="margin-bottom: 20px;">-->
					
                    <div class="block-clearfix"></div>
                    <div id="search-button" class="">
                        <?php echo CHtml::submitButton(Yii::t('common', 'Поиск')); ?>
                    </div>
                    <a id="clear-button" href="<?=$language?>/hotelService/index" class="ajax">
                        <?=Yii::t('common', 'Сбросить')?>
                    </a>
                </form>
            </div>

            <div class="lf-orders container-clearfix">
                <?php  if($orders): ?>				
                    <?php foreach($orders as $order): ?>
                        <?php $this->renderPartial('_order', array('order'=>$order)); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?=$text_not_results ?>
                <?php endif; ?>
                <?php if($pages): ?>
                    <div class="pager"><div class="child"><div class="dop-block">
                                <?php if($current_page > 1): ?><a class="prev" rel="<?=($current_page-1)?>" href="?search=<?=$search?>&page=<?=($current_page-1)?>"><?=Yii::t('general', 'Предыдущая')?></a><?php endif; ?>
                                <?php for($i = $start_page; $i <= $end_page; $i++): ?>
                                    <a class="page<?php if($i == $current_page) print ' current';?>" rel="<?=$i?>" href="?search=<?=$search?>&page=<?=$i?>"><?=$i?></a>
                                <?php endfor; ?>
                                <?php if($current_page < $pages): ?><a class="next" rel="<?=($current_page+1)?>" href="?search=<?=$search?>&page=<?=($current_page+1)?>"><?=Yii::t('general', 'Следующая')?></a><?php endif; ?>
                            </div></div>
							<div class="block-clearfix"></div>
							</div>
                <?php endif; ?>
            </div></div><div class="block-clearfix"></div>

    </div>




    <?php
    Yii::app()->clientScript->registerScript('autocomplete', "
  jQuery('#settlement_name').focus(function(){
            $(this).autocomplete('search', $(this).val());
  }).data('autocomplete')._renderItem = function( ul, item ) {
    return $('<li></li>')
      .data('item.autocomplete', item)
      .append( '<a>' + item.label + '<br><span class=\"desc\">' + item.desc + '</span></a>' )
      .appendTo(ul);
  };
  ",
        CClientScript::POS_READY
    );
    ?>
<?php endif; ?>