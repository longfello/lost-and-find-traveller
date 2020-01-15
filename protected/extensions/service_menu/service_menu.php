
<?php
class service_menu extends CWidget
{
	public $buttons=  array();
	
	public function init()
    {
        $file=dirname(__FILE__).DIRECTORY_SEPARATOR.'service_menu.css';
      
		$cs=Yii::app()->clientScript;
        $cs->registerCssFile(Yii::app()->getAssetManager()->publish($file));
        parent::init();
    }
	

    public function run()
	   {	
		
		foreach($this->buttons as $but_id){
			$class='button_1_5';
			switch($but_id['idx']){
				case '4':
					$class='green_button';
				break;
				case '8':
					$class='narrow_button left';
				break;
				case '9':
					$class='narrow_button right';
				break;
				
			
			}
			
			echo "<a href=".$but_id['href']."><div class='service_menu $class menu_icon".$but_id['idx']."' ></div></a>";
		 
		}
		
	
    }
}
?>