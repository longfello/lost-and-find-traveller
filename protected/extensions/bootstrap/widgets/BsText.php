<?php

class BsText extends CWidget
{
	/**
	 * @var array the HTML attributes for the widget container.
	 */
	public $htmlOptions = array();
	public $name = '';
	public $label = '';
    public $value = '';

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
        $id = uniqid('te_');
        return <<<html
    <div class="row-fluid">
        <div class='col-sm-6'>
            <label for="{$id}">{$this->label}</label>
            <div class="form-group">
                <input type='text' class="form-control" name="{$this->name}" value="{$this->value}"/>
            </div>
        </div>
    </div>
html;

	}
}
