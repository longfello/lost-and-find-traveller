
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=true_or_false"></script>
<?php
/* @var $this PoputchikOrderController */
/* @var $model PoputchikOrder */
/* @var $form CActiveForm */

//print Yii::app()->smsgate->send('73435460461', 'test');die();
$cs = Yii::app()->clientScript;
$cs->registerScriptFile('/js/globalize/globalize.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/globalize/globalize.culture.ru-RU.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/jquery.mousewheel.js', CClientScript::POS_HEAD);
/* $cs->registerScriptFile('/js/jquery.custom.widgets.js', CClientScript::POS_HEAD); */
$cs->registerScriptFile('/js/jquery.ui.core.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/jquery.ui.mask.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/jquery.ui.timepicker.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/route.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/poputchik-order.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/service.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/g_maps.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/jquery-file.js', CClientScript::POS_END );
// $cs->registerCssFile('/css/poputchik.css');
if ($_POST)
    $rims = $_POST;
global $days_of_week;

if ($_POST) {
    if (isset($_POST['days_of_week']))
        $days_of_week_values = $_POST['days_of_week'];
    if (isset($_POST['days_of_month']))
        $days_of_month_values = $_POST['days_of_month'];
} else {
    $days_of_week_values = array();
    foreach ($model->days_of_week as $dow)
        $days_of_week_values[] = $dow->day;
    $days_of_month_values = array();
    foreach ($model->days_of_month as $dom)
        $days_of_month_values[] = $dom->day;
}
if (empty($days_of_week_values))
    $days_of_week_values = array();
if (empty($days_of_month_values))
    $days_of_month_values = array();
if ($_GET['type_order'])
    $model->type_order = $_GET['type_order'];
//print_r($sa_1); die();
?>
<script type="text/javascript">
    $poput_order_messages = Array();
</script>

<div id="poputchik-form" class="form ext container-clearfix">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'poputchik-order-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
			'htmlOptions' => array('enctype'=>'multipart/form-data'),
            ));
    ?>

<?php echo $form->errorSummary($model); ?>

    <div class="person-block">
        <div class="i-block"><img src="/images/poputchik/i-<?= Yii::app()->language ?>.png" /></div>
        <div class="passenger"></div>
        <div class="driver"></div>
        <a class="passenger" rel="2" href="#"><?= Yii::t('poputchik', 'Пассажир') ?></a>
        <a class="driver" rel="1" href="#"><?= Yii::t('poputchik', 'Водитель') ?></a>
            <?php echo $form->hiddenField($model, 'type_order'); ?>
    </div>

    <div class="pf-main" style="display: none;">

        <div class="pf-main-info">
            <!--
                <div class="row">
<?php echo $form->labelEx($model, 'target'); ?>
                        <div class="new-select-style"><?php echo $form->radioButtonList($model, 'target', CHtml::listData($targets, 'IdRef', 'NameRef')); ?></div>
<?php echo $form->error($model, 'target'); ?>
                </div>
            -->
            <div class="row clearfix"><!-- class="type-route"-->
                <?php echo $form->labelEx($model, 'type_route'); ?>

                <?php echo $form->radioButtonList($model, 'type_route', array(1 => Yii::t('poputchik', 'По городу'), 2 => Yii::t('poputchik', 'В другой город'))); ?>
                <?php echo $form->error($model, 'type_route'); ?>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="pf-period-info">
            <div class="row clearfix">
                <?php echo $form->labelEx($model, 'type_time'); ?>

<?php echo $form->radioButtonList($model, 'type_time', array(1 => Yii::t('poputchik', 'разовая'), 2 => Yii::t('poputchik', 'по определённым дням недели'), 3 => Yii::t('poputchik', 'по определённым дням месяца'))); ?>
<?php echo $form->error($model, 'type_time'); ?>
            </div>

            <div id="days_of_week-wrapper" class="row">
                <?php
                for ($i = 0; $i < 7; $i++) {
                    print '<a href="#" class="checkbox' . (in_array($i, $days_of_week_values) ? ' active' : '') . '" name="days_of_week[]" rel="' . $i . '" id="days_of_week-' . $i . '">' . $days_of_week[Yii::app()->language][$i]['short'] . '</a>';
                    print CHtml::checkBox('days_of_week[]', in_array($i, $days_of_week_values), array('value' => $i, 'style' => 'display: none;'));
                }
                ?>
                <div class="errorMessage" style="display:none;"></div>
                <div class="block-clearfix"></div>
            </div>

            <div id="days_of_month-wrapper" class="row">
<?php
for ($i = 1; $i < 32; $i++) {
    print '<a href="#" class="checkbox' . (in_array($i, $days_of_month_values) ? ' active' : '') . '" name="days_of_month[]" rel="' . $i . '" id="days_of_month-' . $i . '">' . $i . '</a>';
    print CHtml::checkBox('days_of_month[]', in_array($i, $days_of_month_values), array('value' => $i, 'style' => 'display: none;'));
}
?>
                <div class="errorMessage" style="display:none;"></div>
                <div class="block-clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>

        <fieldset id="s1" name="s1">
            <div class="legend"><?= Yii::t('poputchik', "Откуда") ?></div>
            <table><tr><td class="img"><img src="/images/poputchik/s1.png" /></td>
                    <td class="data">

                        <div id="start_settlement-wrapper" class="row">
                            <?php echo CHtml::label(Yii::t('poputchik', 'Населённый пункт'), 'start_settlement', array('required' => true)); ?>
                            <div class="inner">
							<?php echo  CHtml::textField( 'settlement_name_1', isset($startSettlement->name) ? $startSettlement->name : '', array( 'size' => 100, 'maxlength' => 255, 'id' => 'start_name', 'class' => 'g_autocomplete', 'placeholder' => 'введите город')); ?>

								<?php echo CHtml::hiddenField('start_settlement', isset($startSettlement->id_settlement) ? $startSettlement->id_settlement : '', array('data-start-id' => 1)); ?>
                                <div class="errorMessage" style="display:none;"></div></div><div class="block-clearfix"></div>
                        </div>
                        <div id="id_sa_start-wrapper" class="sa row clearfix" style="display:none;">
                            <?php echo $form->labelEx($model, 'id_sa_start'); ?>
                                <?php echo $form->dropDownList($model, 'id_sa_start', $sa_1 ? CHtml::listData($sa_1, 'id_sa', 'name') : array()); ?>
                                <?php echo $form->error($model, 'id_sa_start'); ?>
                        </div>
                        <div id="from_place-row" class="row">
                                <?php echo $form->labelEx($model, 'from_place'); ?>
                            <div class="inner"><?php echo $form->textField($model, 'from_place', array('size' => 60, 'maxlength' => 100, 'data-start-place' => 1, 'placeholder' => 'введите район, улицу')); ?>
                                <?php echo $form->error($model, 'from_place'); ?>
                                <div class="errorMessage" style="display:none;"></div></div><div class="block-clearfix"></div>
                        </div>

                        <div id="date_from-wrapper" class="row">
                                <?php echo CHtml::label(Yii::t('poputchik', 'Дата'), 'PoputchikOrder_date_from', array('required' => true)); ?>
                            <div class="inner"><?php
                                $form->widget('zii.widgets.jui.CJuiDatePicker', array(
                                    'model' => $model,
                                    'attribute' => 'date_from',
                                    'language' => 'ru',
                                    'htmlOptions' => array(
                                        'size' => '10', // textField size
                                        'maxlength' => '10', // textField maxlength
                                    ),
                                    'options' => array(
                                        'dateFormat' => 'dd.mm.yy',
                                    ),
                                ));
                                ?>
<?php echo $form->error($model, 'date_from'); ?>
                                <div class="errorMessage" style="display:none;"></div></div><div class="block-clearfix"></div>
                        </div>
                        <div id="time_from-row" class="row">
<?php echo $form->labelEx($model, 'time_from_1'); ?>
<?php echo $form->textField($model, 'time_from_1'); ?>&nbsp;&mdash;&nbsp;<?php echo $form->textField($model, 'time_from_2'); ?>
<?php echo $form->error($model, 'time_from_1'); ?>
                        </div>

                    </td></tr></table>
        </fieldset>

        <fieldset id="s2" name="s2">

            <div class="legend"><?= Yii::t('poputchik', "Куда") ?></div>
            <table><tr><td class="img"><img src="/images/poputchik/s2.png" /></td>
                    <td class="data">
                        <div id="end-ss-wrapper">

                            <div id="end_settlement-wrapper" class="row">
                            <?php echo CHtml::label(Yii::t('poputchik', 'Населённый пункт'), 'end_settlement', array( 'required' => true)); ?>
                                <div class="inner">
                                  <?php echo  CHtml::textField('settlement_name_2', isset($endSettlement->name) ? $endSettlement->name : '', array('placeholder'=>'введите город', 'size' => 100, 'maxlength' => 255, 'id' => 'end_name', 'class' => 'g_autocomplete')); ?>
                                <?php echo CHtml::hiddenField('end_settlement', isset($endSettlement->id_settlement) ? $endSettlement->id_settlement : false, array('data-end-id' => 1)); ?>
                                    <div class="errorMessage" style="display:none;"></div></div><div class="block-clearfix"></div>
                            </div>
                        </div>
                        <div id="id_sa_end-wrapper" class="sa row clearfix" style="display:none;">
                            <?php echo $form->labelEx($model, 'id_sa_end'); ?>
                            <?php echo $form->dropDownList($model, 'id_sa_end', $sa_1 ? CHtml::listData($sa_1, 'id_sa', 'name') : array()); ?>
                            <?php echo $form->error($model, 'id_sa_end'); ?>
                        </div>
                        <div id="to_place-row" class="row">
                            <?php echo $form->labelEx($model, 'to_place'); ?>
                            <div class="inner"><?php echo $form->textField($model, 'to_place', array('size' => 60, 'maxlength' => 100, 'data-end-place' => 1, 'placeholder' => 'введите район, улицу')); ?>
                            <?php echo $form->error($model, 'to_place'); ?>
                                <div class="errorMessage" style="display:none;"></div></div><div class="block-clearfix"></div>
                        </div>

                        <div id="date_to-wrapper" class="row">
                            <?php echo $form->labelEx($model, 'date_to'); ?>
                            <?php
                            $form->widget('zii.widgets.jui.CJuiDatePicker', array(
                                'model' => $model,
                                'attribute' => 'date_to',
                                'language' => 'ru',
                                'htmlOptions' => array(
                                    'size' => '10', // textField size
                                    'maxlength' => '10', // textField maxlength
                                ),
                                'options' => array(
                                    'dateFormat' => 'dd.mm.yy',
                                ),
                            ));
                            ?>

                            <?php echo $form->error($model, 'date_to'); ?>
                        </div>

                        <div id="date_to_offset-wrapper" class="row">
                            <?php echo $form->labelEx($model, 'date_to_offset'); ?>
<?php echo $form->textField($model, 'date_to_offset'); ?>
<?php echo $form->error($model, 'date_to_offset'); ?>
                        </div>

                        <div id="time_to-row" class="row">
<?php echo $form->labelEx($model, 'time_to_1'); ?>
<?php echo $form->textField($model, 'time_to_1'); ?>&nbsp;&mdash;&nbsp;<?php echo $form->textField($model, 'time_to_2'); ?>
                    <?php echo $form->error($model, 'time_to_1'); ?>
                        </div>

                        <div id="reverse-row" class="row">
                    <?php echo $form->checkBox($model, 'reverse'); ?><?php echo $form->labelEx($model, 'reverse'); ?>
                            <div class="container-clearfix" style="height: 1px;"><div class="block-clearfix">&nbsp;</div></div>
                        </div>
                    </td></tr></table>
        </fieldset>
        <div id="reverse-time-wrapper" >
            <fieldset id="reverse-time-1" class="reverse-time">
                <div class="legend"><?= Yii::t('poputchik', "Отъезд обратно") ?></div>
                <div id="date_reverse-wrapper" class="row">
                    <?php echo $form->labelEx($model, 'date_reverse'); ?>
                    <?php
                    $form->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model' => $model,
                        'attribute' => 'date_reverse',
                        'language' => 'ru',
                        'htmlOptions' => array(
                            'size' => '10', // textField size
                            'maxlength' => '10', // textField maxlength
                        ),
                        'options' => array(
                            'dateFormat' => 'dd.mm.yy',
                        ),
                    ));
                    ?>
                    <?php echo $form->error($model, 'date_reverse'); ?>
                </div>
                <div id="date_reverse_offset-wrapper" class="row">
                    <label><?= Yii::t('poputchik', "Через") ?></label>
<?php echo $form->textField($model, 'date_reverse_offset'); ?>
                    <label><?= Yii::t('poputchik', "дней поеду обратно") ?></label>
                    <?php echo $form->error($model, 'date_reverse_offset'); ?>
                    <div class="clearfix">&nbsp;</div>
                </div>

                <div id="time_r_from-row" class="row">
                    <?php echo $form->labelEx($model, 'time_r_from_1'); ?>
                    <?php echo $form->textField($model, 'time_r_from_1'); ?>&nbsp;&mdash;&nbsp;<?php echo $form->textField($model, 'time_r_from_2'); ?>
                    <?php echo $form->error($model, 'time_r_from_1'); ?>
                </div>
            </fieldset>
            <fieldset id="reverse-time-2" class="reverse-time">
                <div class="legend"><?= Yii::t('poputchik', "Приезд обратно") ?></div>
                <div id="date_reverse_to-wrapper" class="row">
                    <?php echo $form->labelEx($model, 'date_reverse_to'); ?>
                    <?php
                    $form->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model' => $model,
                        'attribute' => 'date_reverse_to',
                        'language' => 'ru',
                        'htmlOptions' => array(
                            'size' => '10', // textField size
                            'maxlength' => '10', // textField maxlength
                        ),
                        'options' => array(
                            'dateFormat' => 'dd.mm.yy',
                        ),
                    ));
                    ?>

<?php echo $form->error($model, 'date_reverse_to'); ?>
                </div>
                <div id="date_reverse_to_offset-wrapper" class="row">
<?php echo $form->labelEx($model, 'date_reverse_to_offset'); ?>
<?php echo $form->textField($model, 'date_reverse_to_offset'); ?>
<?php echo $form->error($model, 'date_reverse_to_offset'); ?><div class="clearfix">&nbsp;</div>
                </div>
                <div id="time_r_to-row" class="row">
<?php echo $form->labelEx($model, 'time_r_to_1'); ?>
<?php echo $form->textField($model, 'time_r_to_1'); ?>&nbsp;&mdash;&nbsp;<?php echo $form->textField($model, 'time_r_to_2'); ?>
<?php echo $form->error($model, 'time_r_to_1'); ?>
                </div>
            </fieldset>
        </div>
        <div class="block-clearfix"></div>

        <fieldset id="rims" name="rims" class="collapse<?php if (isset($rims['rim_sid']) && !count($rims['rim_sid'])) print " collapsed"; ?>">
            <div class="legend"><?= Yii::t('poputchik', "Промежуточные пункты в порядке следования") ?></div>
            <div class="inner">
                <table><tr><td class="img"><img src="/images/poputchik/rims.png" /></td>
                        <td class="data">


                            <div id="settlement-row" class="row">
                                    <?php echo CHtml::label(Yii::t('poputchik', 'Населённый пункт'), 'settlement', array('required' => true)); ?>
                                <div class="inner">

								<?php echo  CHtml::textField('rim_settlement_name','',  array('placeholder'=>'Через', 'size' => 50, 'maxlength' => 255, 'id' => 'rim_settlement_name', 'class' => 'g_autocomplete')); ?>


                                            <?php echo CHtml::hiddenField('rim_settlement_id'); ?>
                                            <?php echo CHtml::hiddenField('rim_settlement_full_name'); ?>
                                    <div class="errorMessage" style="display:none;"></div></div><div class="block-clearfix"></div>
                            </div>
                            <div id="add_settlement-row" class="row">
                                <input id="add_settlement" type="button" disabled="disabled" value="<?= Yii::t('poputchik', "Добавить") ?>" />
                            </div>
                            <div id="route-paths-row" class="row">
                                <table id="route-paths" class="route-settlements-table">

                                    <?php
                                    if (isset($rims['rim_sid'])) {
                                        for ($i = 0; $i < count($rims['rim_sid']); $i++):
                                            ?>
                                            <tr>
                                                <td class="name">
        <?= urldecode($rims['rim_sname'][$i]) ?>
                                                    <input type="hidden" name="rim_sname[]" value="<?= $rims['rim_sname'][$i] ?>" />
                                                    <input type="hidden" name="rim_sid[]" value="<?= $rims['rim_sid'][$i] ?>" /></td>
                                                <td class="up-button button"><a href="#"><img src="/images/up-arrow.png" /></a></td>
                                                <td class="down-button button"><a href="#"><img src="/images/down-arrow.png" /></a></td>
                                                <td class="del-button button"><a href="#"><img src="/images/del-icon.png" /></a></td>
                                            </tr>
                                    <?php
                                endfor;
                            }
                            ?>
                                </table>
                            </div>
                        </td></tr></table>
            </div>
        </fieldset>

        <fieldset id="rims-sp" name="rims_sp" class="collapse<?php if (isset($rims['rim_sp_sid']) && !count($rims['rim_sp_sid'])) print " collapsed"; ?>">
            <div class="legend"><?= Yii::t('poputchik', 'Возможен заезд в другие города по пути следования') ?></div>
            <div class="inner">
                <table><tr><td class="img"><img src="/images/poputchik/rims-sp.png" /></td>
                        <td class="data">
                            <?php echo $form->hiddenField($model, 'transit'); ?>
                            <?php /*
                              <div id="country_sp-row" class="row">
                              <?php echo CHtml::label(Yii::t('poputchik', 'Страна'),'country_sp'); ?>
                              <?php echo CHtml::dropDownList('country_sp', $id_country, CHtml::listData($countries, 'id_country', 'name'),
                              array(
                              'empty' => Yii::t('poputchik', "Выберите страну"),
                              /*'ajax' => array(
                              'type'=>'POST', //request type
                              'url'=>CController::createUrl('regions/getRegionsByCountry'), //url to call.
                              'update'=>'#region_sp', //selector to update
                              'data' => array(
                              'id_country' => 'js:this.value',
                              ),
                              )	//leave out the data key to pass all form values through
                              )
                              ) ; ?>
                              </div>
                              <?/*<div id="region_sp-row" class="row">
                              <?php echo CHtml::label(Yii::t('poputchik', 'Регион'),'region_sp'); ?>
                              <?php echo CHtml::dropDownList('region_sp', $id_region, array()); ?>
                              </div>
                             */ ?>
                            <div id="rim_sp_settlement_name-row" class="row">
                                    <?php echo CHtml::label(Yii::t('poputchik', 'Населённый пункт'), 'rim_sp_settlement_name', array('required' => true)); ?>
                                <div class="inner"><?php
                                    $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                                        'name' => 'rim_sp_settlement_name',
                                        'source' => 'js:function(request, response) {
					$.getJSON("' . $this->createUrl('settlements/autocomplete') . '", {
						term: request.term.split(/,s*/).pop(),
						country_id: $("#country_sp").val() 
					}, response);
				}',
                                        // additional javascript options for the autocomplete plugin
                                        'options' => array(
                                            'minLength' => '1',
                                            'showAnim' => 'fold',
                                            'delay' => 500,
                                            'select' => 'js: function(event, ui) {
						this.value = ui.item.value;
						// записываем полученный id в скрытое поле
						$("#rim_sp_settlement_id").val(ui.item.id);
						$("#rim_sp_settlement_full_name").val(ui.item.label + "<br><span class=\"desc\">" + ui.item.desc + "</span>");
						$("#add_settlement_sp").removeAttr("disabled");
						return false;
					}',
                                            'response' => 'js: autoCompleteResponse',
                                        ),
                                        'htmlOptions' => array(
                                            'size' => '50',
                                        ),
                                    ));
                                    ?>
<?php echo CHtml::hiddenField('rim_sp_settlement_id'); ?>
<?php echo CHtml::hiddenField('rim_sp_settlement_full_name'); ?>
                                    <div class="errorMessage" style="display:none;"></div></div><div class="block-clearfix"></div>
                            </div>
                            <div id="add_settlement_sp-row" class="row">
                                <input id="add_settlement_sp" type="button" disabled="disabled" value="<?= Yii::t('poputchik', "Добавить") ?>" />
                            </div>
                            <div id="route-paths-sp-row" class="row">
                                <table id="route-paths-sp" class="route-settlements-table">
                                    <?php
                                    if (isset($rims['rim_sp_sid'])) {
                                        for ($i = 0; $i < count($rims['rim_sp_sid']); $i++):
                                            ?>
                                            <tr>
                                                <td class="name">
        <?= urldecode($rims['rim_sp_sname'][$i]) ?>
                                                    <input type="hidden" name="rim_sp_sname[]" value="<?= $rims['rim_sp_sname'][$i] ?>" />
                                                    <input type="hidden" name="rim_sp_sid[]" value="<?= $rims['rim_sp_sid'][$i] ?>" /></td>
                                                <td class="up-button button"><a href="#"><img src="/images/up-arrow.png" /></a></td>
                                                <td class="down-button button"><a href="#"><img src="/images/down-arrow.png" /></a></td>
                                                <td class="del-button button"><a href="#"><img src="/images/del-icon.png" /></a></td>
                                            </tr>
        <?php
    endfor;
}
?>
                                </table>
                            </div>
                        </td></tr></table>
            </div>
        </fieldset>

        <fieldset id="auto-wrapper">
            <div class="legend"><?= Yii::t('poputchik', 'Авто, места') ?></div>
            <div id="auto-type-model">
                <div id="type_auto-row" class="row">
                    <div class="clearfix"><?php echo $form->labelEx($model, 'type_auto'); ?></div>
                    <a id="ta-1" href="#" name="PoputchikOrder[type_auto]" rel="11" class="radio<?php if ($model->type_auto == 11) print ' active'; ?>"><?= Yii::t('poputchik', "Легковой") ?></a>
                    <a id="ta-2" href="#" name="PoputchikOrder[type_auto]" rel="13" class="radio<?php if ($model->type_auto == 13) print ' active'; ?>"><?= Yii::t('poputchik', "Пассажирский") ?></a>
                    <a id="ta-3" href="#" name="PoputchikOrder[type_auto]" rel="12" class="radio<?php if ($model->type_auto == 12) print ' active'; ?>"><?= Yii::t('poputchik', "Грузовой") ?></a>
                    <input type="radio" style="display: none;" name="PoputchikOrder[type_auto]" value="11"<?php if ($model->type_auto == 11) print ' checked="checked"'; ?> />
                    <input type="radio" style="display: none;" name="PoputchikOrder[type_auto]" value="12"<?php if ($model->type_auto == 12) print ' checked="checked"'; ?> />
                    <input type="radio" style="display: none;" name="PoputchikOrder[type_auto]" value="13"<?php if ($model->type_auto == 13) print ' checked="checked"'; ?> />
                </div>

                <div id="id_brand-row" class="row">
                    <?php
                    echo $form->dropDownList($model, 'id_brand', CHtml::listData($brands_auto, 'id_brand', 'brand'), array(
                            /* 'ajax' => array(
                              'type'=>'POST', //request type
                              'url'=>CController::createUrl('autoModels/getModelsByBrand'), //url to call.
                              'update'=>'#PoputchikOrder_id_model', //selector to update
                              'data' => array(
                              'id_brand' => 'js:this.value',
                              ),
                              )		//leave out the data key to pass all form values through */
                            )
                    );
                    ?>
                    <?php echo $form->error($model, 'id_brand'); ?>
                </div>
                <div id="id_model-row" class="row clearfix">
<?php echo $form->dropDownList($model, 'id_model', $models_auto ? CHtml::listData($models_auto, 'id_model', 'model') : array()); ?>
<?php echo $form->error($model, 'id_model'); ?>
                </div>
            </div>
            <div id="auto-places-sum">
                <div class="row">
                    <?php echo $form->labelEx($model, 'free_places_count'); ?>
<?php echo $form->textField($model, 'free_places_count'); ?>
<?php echo $form->error($model, 'free_places_count'); ?>
                </div>
                <div id="PoputchikOrder_sum-row" class="row">
                <?php echo $form->labelEx($model, 'sum'); ?>
                    <?php echo $form->textField($model, 'sum'); ?>&nbsp;<?= Yii::t('poputchik', 'рублей') ?>
                    <div id="type_sum-wrapper" class="clearfix"><?php echo $form->radioButtonList($model, 'type_sum', CHtml::listData($types_sum, 'IdRef', 'NameRef')); ?></div>
<?php echo $form->error($model, 'sum'); ?>
                </div>
            </div>
        </fieldset>
        <div class="block-clearfix"> </div>
        <div id="contacts-wrapper">
            <div id="name-row" class="row">
                <?php echo $form->labelEx($model, 'name'); ?>
                <div class="inner"><?php echo $form->textField($model, 'name', array('value' => isset($user_data['first_name']) ? $user_data['first_name'] : '', 'size' => 50, 'maxlength' => 50)); ?>
<?php echo $form->error($model, 'name'); ?>
                    <div class="errorMessage" style="display:none;"></div></div><div class="block-clearfix"></div>
            </div>

            <div class="row">
<?php echo $form->labelEx($model, 'phone'); ?>
                <div class="inner"><?php echo $form->textField($model, 'phone', array('value' => isset($user_data['phone']) ? $user_data['phone'] : '', 'size' => 20, 'maxlength' => 20)); ?>
            <?php echo $form->error($model, 'phone'); ?>
                    <div class="errorMessage" style="display:none;"></div></div><div class="block-clearfix"></div>
            </div>
            <div class="row">
                <span class='label'>E-mail</span>
<?php

    echo CHTML::emailField('email', isset($user_data['email']) ? $user_data['email'] : '', array('size' => 20, 'id' => 'email'));

?>
                <div class="errorMessage" style="display:none;"></div>


                <div class="block-clearfix"> </div>
            </div>
        </div>


        <div id="comment-row" class="row">
                <?php echo $form->labelEx($model, 'comment'); ?>
                <?php echo $form->textArea($model, 'comment', array('rows' => 8, 'cols' => 50, 'style' => 'resize:none')); ?>
                <?php echo $form->error($model, 'comment'); ?>
        </div>

        <div id="finish-wrapper">
            <div class="row">
                <div class="clearfix"><?php echo $form->labelEx($model, 'date_available'); ?></div>
<?php
$form->widget('zii.widgets.jui.CJuiDatePicker', array(
    'model' => $model,
    'attribute' => 'date_available',
    'language' => 'ru',
    'htmlOptions' => array(
        'size' => '10', // textField size
        'maxlength' => '10', // textField maxlength
    ),
    'options' => array(
        'dateFormat' => 'dd.mm.yy',
    ),
));
?>
<?php echo $form->error($model, 'date_available'); ?>
            </div>
            <input type="hidden" name="test" value="1">
            <div class="row buttons">
                <div class="inner"><?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('common', 'Создать') : Yii::t('common', 'Сохранить')); ?><div class="errorMessage" style="display:none;"></div></div><div class="block-clearfix"></div>
            </div>
        </div>
        <div class="block-clearfix"> </div>

	<div class="row row-photo">
		<?php echo CHtml::label(Yii::t('common', 'Аватарка'),'photo'); ?>
		<?php echo CHtml::fileField('photo'); ?>

		<span style="color:rgb(153, 153, 153);">Формат файла: jpg, png, gif<br>Максимальный размер: <?=get_cfg_var('upload_max_filesize')?></span>


	</div>


    </div>
    <span class="required">Поля отмеченные звездочкой (*) обязательны для заполнения.</span>
<?php $this->endWidget(); ?>
</div><!-- form -->
<div class="clearfix"></div>
<?php
Yii::app()->clientScript->registerScript('autocomplete', "
  jQuery('body').on('change','#country_1',function(){ ajax_selectbox_load($(this),'/regions/getRegionsByCountry', {'id_country':this.value}, '#region_1', function () { first_gray($('#region_1')); }); first_gray($(this)); });
  jQuery('body').on('change','#region_1',function(){first_gray($(this)); });
  jQuery('body').on('change','#country_2',function(){ajax_selectbox_load($(this),'/regions/getRegionsByCountry', {'id_country':this.value}, '#region_2', function () { first_gray($('#region_2')); } ); first_gray($(this));});
  jQuery('body').on('change','#region_2',function(){first_gray($(this)); });
  jQuery('body').on('change','#country',function(){ajax_selectbox_load($(this),'/regions/getRegionsByCountry', {'id_country':this.value}, '#region');});
  jQuery('body').on('change','#country_sp',function(){ajax_selectbox_load($(this),'/regions/getRegionsByCountry', {'id_country':this.value}, '#region_sp');});
  jQuery('body').on('change','#PoputchikOrder_id_brand',function(){ajax_selectbox_load($(this),'/autoModels/getModelsByBrand', {'id_brand':this.value}, '#PoputchikOrder_id_model', function () { first_gray($('#PoputchikOrder_id_model'),1547); } ); first_gray($(this),0);});
 /* jQuery('#settlement_name_1').focus(function(){
            $(this).autocomplete('search', $(this).val());
  }).data('autocomplete')._renderItem = function( ul, item ) {
    return $('<li></li>')
      .data('item.autocomplete', item)
      .append( '<a>' + item.label + '<br><span class=\"desc\">' + item.desc + '</span></a>' )
      .appendTo(ul);
  };
  jQuery('#settlement_name_2').focus(function(){
            $(this).autocomplete('search', $(this).val());
  }).data('autocomplete')._renderItem = function( ul, item ) {
    return $('<li></li>')
      .data('item.autocomplete', item)
      .append( '<a>' + item.label + '<br><span class=\"desc\">' + item.desc + '</span></a>' )
      .appendTo(ul);
  };

  jQuery('#rim_settlement_name').focus(function(){
            $(this).autocomplete('search', $(this).val());
  }).data('autocomplete')._renderItem = function( ul, item ) {
    return $('<li></li>')
      .data('item.autocomplete', item)
      .append( '<a>' + item.label + '<br><span class=\"desc\">' + item.desc + '</span></a>' )
      .appendTo(ul);
  }; */
  jQuery('#rim_sp_settlement_name').focus(function(){
            $(this).autocomplete('search', $(this).val());
  }).data('autocomplete')._renderItem = function( ul, item ) {
    return $('<li></li>')
      .data('item.autocomplete', item)
      .append( '<a>' + item.label + '<br><span class=\"desc\">' + item.desc + '</span></a>' )
      .appendTo(ul);
  };
  $('#add_settlement').click(function() {
	$('#route-paths').append(get_route_settlement_tr());
	$('#add_settlement').attr('disabled','disabled');
	$('#rim_settlement_name').val('');
	$('#route-paths-row').show();
  });
  $('#add_settlement_sp').click(function() {
	$('#route-paths-sp').append(get_route_settlement_tr('_sp'));
	$('#add_settlement_sp').attr('disabled','disabled');
	$('#rim_sp_settlement_name').val('');
	$('#route-paths-sp-row').show();
  });
  ", CClientScript::POS_READY
);
?>