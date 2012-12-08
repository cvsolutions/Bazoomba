<?php
/**
 * /tmp/phptidy-sublime-buffer.php
 *
 * @package default
 */


class Application_Model_DbTable_Provinces extends Zend_Db_Table_Abstract
{

    protected $_name = 'ads_provinces';
    protected $_primary = 'id';

    /**
     *
     *
     * @param unknown $id
     * @return unknown
     */
    public function getProvinceInfo($id) {
        $row = $this->fetchRow(sprintf('id = %d', $id));
        if (!$row) {
            $params = Plugin_Common::getParams();
            throw new Exception($params->label_no_id, 1);
        }
        return $row->toArray();
    }


    /**
     *
     *
     * @param unknown $region
     * @return unknown
     */
    public function Parent_Provinces($region) {
        return $this->fetchAll(sprintf('region = %d', $region));
    }



}
