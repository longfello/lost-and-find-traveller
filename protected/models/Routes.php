<?php

/**
 * This is the model class for table "routes".
 *
 * The followings are the available columns in table 'routes':
 * @property integer $id_route
 * @property integer $start_settlement
 * @property integer $end_settlement
 */
class Routes extends CActiveRecord
{
	public $rPaths = array();
	public $rSettlements = array();
	public $direction = array();
	public $start_settlement_search;
	public $end_settlement_search;
	public $not_moderate_search;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'routes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('start_settlement, end_settlement', 'required'),
			array('start_settlement, end_settlement', 'numerical', 'integerOnly'=>true),
			array('is_active', 'default', 'setOnEmpty' => true, 'value' => '1'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_route, start_settlement, end_settlement, start_settlement_search, end_settlement_search, not_moderate_search', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'startSettlement' => array(self::BELONGS_TO, 'Settlements', 'start_settlement'),
			'endSettlement' => array(self::BELONGS_TO, 'Settlements', 'end_settlement'),
			'routePaths' => array(self::HAS_MANY, 'RoutePaths', 'id_route', 'order' => 'routePaths.step'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_route' => 'Id Route',
			'start_settlement' => 'Начальный пункт',
			'end_settlement' => 'Конечный пункт',
			'start_settlement_search' => 'Начальный пункт',
			'end_settlement_search' => 'Конечный пункт',
			'not_moderate_search' => 'Требуют модерации',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
//print_r($this);die();
		$criteria=new CDbCriteria;
		
		$criteria->with = array('startSettlement', 'endSettlement', 'routePaths', 'routePaths.idPath');
		
		$criteria->compare('id_route',$this->id_route);
		$criteria->compare('startSettlement.name',$this->start_settlement_search, true);
		$criteria->compare('endSettlement.name',$this->end_settlement_search, true);
		
		$criteria2 = new CDbCriteria;
		
		$criteria2->with = array('startSettlement', 'endSettlement', 'routePaths', 'routePaths.idPath');
		$criteria2->compare('startSettlement.name',$this->end_settlement_search, true);
		$criteria2->compare('endSettlement.name',$this->start_settlement_search, true);
		
		$criteria->mergeWith($criteria2, 'OR');
		
		if($this->not_moderate_search || !$_GET['ajax']) {
			$criteria->mergeWith(array('condition'=>'t.is_moderate = 0'), 'AND');
		}
		
		$criteria->mergeWith(array('condition'=>'(t.is_active = 1 OR t.is_moderate = 0)'), 'AND');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Routes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function afterValidate()
	{
		$cntRim = count($_POST['rim_sid']);
		if(!$cntRim) { $this->addError('id_route', 'Укажите хотя бы один промежуточный город или добавьте путь вместо маршрута.'); return; }

		if($this->setRPaths($_POST['rim_sid'])) return;
		
		$m_route = $this->findRouteByPaths($this->start_settlement, $this->end_settlement, $this->rPaths);
		if($m_route && $m_route->id_route != $this->id_route)
			$this->addError('id_route', 'Такой маршрут уже существует.');
		//$this->addError('id_route', 'ERROR 2');
	}
	
	public function getAllRoutesBySIDs($sid1, $sid2)
	{
		return Routes::model()->with('routePaths')->findAll('(start_settlement=:s1 AND end_settlement=:s2) OR (start_settlement=:s2 AND end_settlement=:s1)',array(':s1' => $sid1, ':s2' => $sid2));
	}
	
	public function setRPaths($rims)
	{
		$cntRim = count($rims);
		if(!$cntRim) { $this->addError('id_route', 'Укажите хотя бы один промежуточный город или добавьте путь вместо маршрута.'); return; }
		$is_new_path = false;
		for($i = 0; $i <= $cntRim; $i++) {
			$s1 = $i > 0 ? $rims[$i-1] : $this->start_settlement;
			$s2 = $i < $cntRim ? $rims[$i] : $this->end_settlement;
			$m_path = Paths::model()->getPathBySIDs($s1, $s2);
			if($m_path) $dir = $m_path->id_settlement_1 == $s1 ? 0 : 1;
			else { $dir = 0; $is_new_path = true; }
			$this->rPaths[] = array ('path' => $m_path,
								'sid1' => $s1,
								'sid2' => $s2,
								'direction' => $dir,
								'step' => $i+1,
			);
			if($i < $cntRim) $this->rSettlements[] = $rims[$i];
		}
		
		return $is_new_path;
	}
	
	public function findRouteByPaths($sid1, $sid2, $paths)
	{
		$m_routes = $this->getAllRoutesBySIDs($sid1, $sid2);
		if($m_routes)
			foreach($m_routes as $m_route) {
				$cntRP = count($m_route->routePaths);
				if($cntRP != count($paths)) continue;
				if($m_route->start_settlement == $sid1) {
					for($i = 0; $i < $cntRP; $i++) if($m_route->routePaths[$i]->id_path != $paths[$i]['path']->id_path) break;
					if($i == $cntRP) return $m_route;
				} else {
					for($i = 0; $i < $cntRP; $i++) if($m_route->routePaths[$i]->id_path != $paths[$cntRP - $i - 1]['path']->id_path) break;
					if($i == $cntRP) return $m_route;
				}
			}
		return null;
	}
	
	public function findOrCreate()
	{
		$result = false;//print_r($this->rPaths); die();
		$m_route = $this->findRouteByPaths($this->start_settlement, $this->end_settlement, $this->rPaths);
		if(!$m_route) {
			if($this->save()) $result = $this->saveRP();
			$this->direction = 0;
		} else {
			$result = true;
			$this->id_route = $m_route->id_route; 
			$this->direction = $m_route->start_settlement == $this->start_settlement ? 0 : 1;
		}
		return $result;
	}
	
	public function saveRP()
	{
		foreach($this->rPaths as $rPath) {
			if(!$rPath['path']) {
				$m_path = new Paths;
				$m_path->id_settlement_1 = $rPath['sid1'];
				$m_path->id_settlement_2 = $rPath['sid2'];
				if(!$m_path->save()) return false;
				$rPath['path'] = $m_path;
			}
			$rp = new RoutePaths;
			$rp->id_path = $rPath['path']->id_path;
			$rp->id_route = $this->id_route;
			$rp->direction = $rPath['direction'];
			$rp->step = $rPath['step'];
			if(!$rp->save()) return false;
		}
		foreach($this->rSettlements as $ids) {
			$rs = new RouteSettlements;
			$rs->id_route = $this->id_route;
			$rs->id_settlement = $ids;
			if(!$rs->save()) return false;
		}
		return true;
	}

	public function deleteRP() {
		Yii::app()->db->createCommand()->delete('route_paths', 'id_route=:id', array(':id'=>$this->id_route));
		Yii::app()->db->createCommand()->delete('route_settlements', 'id_route=:id', array(':id'=>$this->id_route));
	}
	
	public function getRSettlements($direction) {
		$rSettlements = array();
		if($direction) $routePaths = array_reverse($this->routePaths);
		else $routePaths = $this->routePaths;
		$cnt = count($this->routePaths);
		for($i = 0; $i < $cnt - 1; $i++) {
			if($this->routePaths[$i]->direction) { $rSettlements[] = $this->routePaths[$i]->idPath->startSettlement->name; }
			else { $rSettlements[] = $this->routePaths[$i]->idPath->endSettlement->name; }
		}
		return $rSettlements;
	}
}
