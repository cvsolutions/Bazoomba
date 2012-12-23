<?php

/**
 * Application_Model_DbTable_Provinces
 *
 * @uses     Zend_Db_Table_Abstract
 *
 * @category Provinces
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class Application_Model_DbTable_Provinces extends Zend_Db_Table_Abstract
{

    /**
     * $_name
     *
     * @var string
     *
     * @access protected
     */
    protected $_name = 'ads_provinces';

    /**
     * $_primary
     *
     * @var string
     *
     * @access protected
     */
    protected $_primary = 'id';

    /**
     * getProvinceInfo
     * Recupero tutte le informazioni della provincia
     *
     * @param mixed   $id ID Provincia.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getProvinceInfo( $id ) {
        $row = $this->fetchRow( sprintf( 'id = %d', $id ) );
        if ( !$row ) {
            $params = Plugin_Common::getParams();
            throw new Exception( $params->label_no_id, 1 );
        }
        return $row->toArray();
    }

    /**
     * Parent_Provinces
     * Recupero tutte le province della regione selezionata
     *
     * @param mixed   $region ID Regione.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function Parent_Provinces( $region ) {
        return $this->fetchAll( sprintf( 'region = %d', $region ) );
    }



}
