<link rel="stylesheet" type="text/css" href="/css/contact.css" />
<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */
 Yii::app()->clientScript->registerMetaTag('', 'keywords');
 Yii::app()->clientScript->registerMetaTag('Форма обратной связи', 'description');
 $this->pageTitle = 'Свяжитесь с нами | INFOtoway.ru';
/*$this->pageTitle = Yii::app()->name . ' - Свяжитесь с нами';*/

?>
<div class="contact">
    <h1>Связаться</h1>

    <?php if (Yii::app()->user->hasFlash('contact')): ?>

        <div class="flash-success">
            <?php echo Yii::app()->user->getFlash('contact'); ?>
        </div>

    <?php else: ?>

        <p>
            Если вы не нашли ответа на свой вопрос в разделе<br> "<a href="http://<?=SERVICE_POPUTCHIK?>.<?=Yii::app()->params['domain']?>/faq" title="Часто задаваемые вопросы">Часто задаваемые вопросы</a>", напишите нам, использую форму для обратной связи. Обычно мы отвечаем в течение 24 часов.
        </p>

        <div class="form">

            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'contact-form',
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
                    ));
            ?>
    <?php echo $form->errorSummary($model); ?>
            <div class="control-group">
                <label for="contact_categoryFilter" class=" control-label">Я</label>


                <div class="controls contact-radio-container">
                    <?php
                    foreach ($model->typeLabel as $key => $val) {
                        ?>
                        <button class="radio" data-input-id="<?= $key ?>">
                        <?= $val ?>
                        </button>
                        <?php
                    }
                    ?>

    <?php echo $form->hiddenField($model, 'type'); ?>
                </div>
            </div>

            <div class="control-group">
                <div class="controls">
                    <script type="text/javascript">
                        var registry = new Object();
                        registry.cat = new Array();
    <?php
    foreach ($model->typeLabel as $key => $val) {
        ?>
                                     registry.cat['<?= $key ?>'] = new Array();
        <?php
    }
    ?>

    <?php
    $category = $model->getCategoryOptions();
    foreach ($category as $cat) {
        ?>
                                    registry.cat['<?= $cat['group'] ?>'][<?= $cat['id'] ?>] = '<?= $cat['text'] ?>'
        <?php
    }
    ?>
                    </script>
                    <script type="text/javascript" src="/js/contact.js"></script>
                    <select id="contact_category" name="ContactForm[category]" class="select-contact input-block-level margin-bottom">
                    </select>
                </div>
            </div>
            <div class="row">
                <?php echo $form->textField($model, 'subject', array('placeholder' => 'Тема', 'disabled' => 'disabled')); ?>
                <?php echo $form->error($model, 'subject'); ?>
            </div>
            <div class="row">
                <?php echo $form->textArea($model, 'body', array('rows' => 6, 'cols' => 50, 'placeholder' => 'Сообщение', 'disabled' => 'disabled')); ?>
                <?php echo $form->error($model, 'body'); ?>
            </div>
            <div class="row">

                <?php echo $form->textField($model, 'email', array('placeholder' => 'Email', 'disabled' => 'disabled')); ?>
                <?php echo $form->error($model, 'email'); ?>
            </div>

            <div class="row">

                <?php echo $form->textField($model, 'phone', array('placeholder' => 'Телефон', 'disabled' => 'disabled')); ?>
                <?php echo $form->error($model, 'phone'); ?>
            </div>


            <div class="row buttons">
                <?php echo CHtml::submitButton('Отправить'); ?>
            </div>

            <?php $this->endWidget(); ?>

        </div><!-- form -->

    <?php endif; ?>
</div>