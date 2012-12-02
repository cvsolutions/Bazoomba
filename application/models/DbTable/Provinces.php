<?php

class Application_Model_DbTable_Provinces extends Zend_Db_Table_Abstract
{

    protected $_name = 'ads_provinces';
    protected $_primary = 'id';

    public function getProvinceInfo($id)
    {
        $row = $this->fetchRow(sprintf('id = %d', $id));
        if(!$row)
        {
            $params = Plugin_Common::getParams();
            throw new Exception($params->label_no_id, 1);
        }
        return $row->toArray();
    }

    public function Parent_Provinces($region)
    {
        return $this->fetchAll(sprintf('region = %d', $region));
    }

    public function Other_Category($id)
    {
        // return $this->fetchAll(sprintf('id != %d AND parent = 0', $id));
    }


}

