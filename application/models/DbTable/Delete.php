<?php

class Application_Model_DbTable_Delete extends Zend_Db_Table_Abstract
{

    protected $_name = 'ads_delete';

    public function save_delete($shop, $user, $type)
    {
        $array = array(
            'shop' => $shop,
            'user' => $user,
            'type' => $type,
            'registered' => new Zend_Db_Expr('NOW()')
        );
        return $this->insert($array);
    }
}

