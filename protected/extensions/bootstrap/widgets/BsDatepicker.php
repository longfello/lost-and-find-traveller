<?php

class BsDatepicker extends CWidget
{
	/**
	 * @var array the HTML attributes for the widget container.
	 */
	public $htmlOptions = array();
	public $name = '';
	public $label = '';
	public $value = '';
	public $format = 'DD-MM-YYYY';

	/**
	 * Runs the widget.
	 */
	public function run()
	{
		echo $this->createButton();
	}

	/**
	 * Creates the button element.
	 * @return string the created button.
	 */
	protected function createButton(){
        $id = uniqid('dp_');
        return <<<html
    <div class="row-fluid">
        <div class='col-sm-6'>
            <label for="{$id}">{$this->label}</label>
            <div class="form-group">
                <div class='input-group date'>
                    <input  id='{$id}' type='time' class="form-control" name="{$this->name}" value="{$this->value}"/>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(function () {
                $('#{$id}').datetimepicker({
                    locale: 'ru',
                    format: '{$this->format}'
                });
            });
        </script>
    </div>
html;

	}
}
