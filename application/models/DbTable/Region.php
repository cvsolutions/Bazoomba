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
     * Recupero tutte le informazioni sulla regione
     *
     * @param mixed   $id ID Regione.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getRegionInfo($id)
    {
        $row = $this->fetchRow(sprintf('id = %d', $id));
        if (!$row) {
            $params = Plugin_Common::getParams();
            throw new Exception($params->label_no_id, 1);
        }
        return $row->toArray();
    }

    /**
     * Other_Region
     * Tutte le regione d'Italia, tranne quella selezionata
     *
     * @param mixed   $id ID Regione.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function Other_Region($id)
    {
        return $this->fetchAll(sprintf('id != %d', $id));
    }

    /**
     * @param $latitude
     * @param $longitude
     *
     * @return array
     * @throws Exception
     */
    public function Region_GeoCode($latitude, $longitude)
    {
        $query = sprintf("TRUNCATE ( 6363 * sqrt( POW( RADIANS('%s') - RADIANS(ads_region.latitude) , 2 ) + POW( RADIANS('%s') - RADIANS(ads_region.longitude) , 2 ) ) , 3 ) < 150", $latitude, $longitude);
        $row = $this->fetchRow($query);
        if (!$row) {
            return array('name' => 'Italia');
        }
        return $row->toArray();
    }


}
