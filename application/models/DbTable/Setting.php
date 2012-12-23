<?php

/**
 * Application_Model_DbTable_Setting
 *
 * @uses     Zend_Db_Table_Abstract
 *
 * @category Setting
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class Application_Model_DbTable_Setting extends Zend_Db_Table_Abstract
{
    /**
     * $_name
     *
     * @var string
     *
     * @access protected
     */
    protected $_name = 'ads_settings';

    /**
     * $_primary
     *
     * @var string
     *
     * @access protected
     */
    protected $_primary = 'id';

    /**
     * getSettings
     * Recupero le informazioni di configurazione
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getSettings() {
        $row = $this->fetchRow();
        return $row->toArray();
    }

    /**
     * updateSettings
     * Aggiornamento delle informazioni
     *
     * @param mixed   $title       Meta Tag Titolo.
     * @param mixed   $description Meta Tag Description.
     * @param mixed   $keywords    Meta Tag Keywords.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateSettings( $title, $description, $keywords, $off_line ) {
        $arrayName = array(
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'off_line' => $off_line
        );
        return $this->update( $arrayName, 'id = 1' );
    }


}
