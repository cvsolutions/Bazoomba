<?php

/**
* Application_Model_DbTable_Region
*
* @uses     Zend_Db_Table_Abstract
*
* @category Region
* @package  Bazoomba.it
* @author   Concetto Vecchio
* @license  
* @link     
*/
class Application_Model_DbTable_Region extends Zend_Db_Table_Abstract
{

    /**
     * $_name
     *
     * @var string
     *
     * @access protected
     */
    protected $_name = 'ads_region';

    /**
     * $_primary
     *
     * @var string
     *
     * @access protected
     */
    protected $_primary = 'id';

    /**
     * getRegionInfo
     * 
     * @param mixed $id Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getRegionInfo($id)
    {
    	$row = $this->fetchRow(sprintf('id = %d', $id));
    	if(!$row)
    	{
    		$params = Plugin_Common::getParams();
    		throw new Exception($params->label_no_id, 1);
    	}
    	return $row->toArray();
    }

    public function Other_Region($id)
    {
        return $this->fetchAll(sprintf('id != %d', $id));
    }


}

