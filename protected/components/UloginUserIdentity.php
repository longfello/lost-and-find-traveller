<?php

class UloginUserIdentity implements IUserIdentity
{

    private $id;
    private $name;
    private $isAuthenticated = false;
    private $states = array();
    public $errors = array();

    public function __construct()
    {
    }

    public function authenticate($uloginModel = null){
        $criteria = new CDbCriteria;
        $criteria->condition = 'identity=:identity AND network=:network';
        $criteria->params = array(
          ':identity' => $uloginModel->identity
        , ':network' => $uloginModel->network
        );
        $user = UsersIdentity::model()->find($criteria);

        if (null !== $user) {
          $real_user = User::model()->find('id=?', array($user->id));
          $this->id = $user->id;
          $this->name = $real_user->username;
        } else {
          return false;
        }
        $this->isAuthenticated = true;
        return true;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIsAuthenticated()
    {
        return $this->isAuthenticated;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPersistentStates()
    {
        return $this->states;
    }
}