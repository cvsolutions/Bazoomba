<?php

class Application_Model_DbTable_City extends Zend_Db_Table_Abstract
{

    protected $_name = 'ads_city';
    
    protected $_primary = 'id';

    public function getCityInfo($id)
    {
        $row = $this->fetchRow(sprintf('id = %d', $id));
        if(!$row)
        {
            $params = Plugin_Common::getParams();
            throw new Exception($params->label_no_id, 1);
        }
        return $row->toArray();
    }


}

