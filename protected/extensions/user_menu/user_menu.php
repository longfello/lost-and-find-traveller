
<?php
class user_menu extends CWidget
{
	public $active_button=  "";
	
	public function init()
    {
      /*   $file=dirname(__FILE__).DIRECTORY_SEPARATOR.'service_menu.css';
      
		$cs=Yii::app()->clientScript;
        $cs->registerCssFile(Yii::app()->getAssetManager()->publish($file)); */
        parent::init();
    }
	

    public function run()
	   {	
	   
	
		$html="";
		$html.="<ul class='user_menu' >";
		$html.="<li><a class='".($this->active_button=="profile"?"active":"")."'  href='/user/profile/edit'>".Yii::t('general', 'Мой профиль')."</a>";
		$html.="<li>".Yii::t('general', 'Мои объявления');
		$html.="<ul>";
		$html.="	<li><a class='".($this->active_button=="my_orders"?"active":"")."' href='/general/cabinet'>".Yii::t('poputchik', 'В Попутчике')."</a>";
		//$html.="	<li><a class='".($this->active_button=="buro"?"active":"")."'  href='/$language/LostFound/my'>".Yii::t('poputchik', 'В Бюро находок')."</a>";
		$html.="</ul>";
		$html.="</ul>";
		echo $html;
		
		 
		}
		
	
    
}
?>