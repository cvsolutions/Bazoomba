<?php

/**
* Application_Model_DbTable_Page
*
* @uses     Zend_Db_Table_Abstract
*
* @category Page
* @package  Bazoomba.it
* @author   Concetto Vecchio
* @license  
* @link     
*/
class Application_Model_DbTable_Page extends Zend_Db_Table_Abstract
{

    /**
     * $_name
     *
     * @var string
     *
     * @access protected
     */
    protected $_name = 'ads_page';

    /**
     * $_primary
     *
     * @var string
     *
     * @access protected
     */
    protected $_primary = 'id';

    /**
     * getMyPage
     * 
     * @param mixed $id   ID User.
     * @param mixed $type Type SQL.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getMyPage($id, $type) {
        if ($type == 'count') {
            $row = $this->fetchAll(sprintf('user = %d', $id));
        } else {
            $row = $this->fetchRow(sprintf('user = %d', $id));
        }

        if (!$row) {
            $params = Plugin_Common::getParams();
            throw new Exception($params->label_no_id, 1);
        }
        return $row->toArray();
    }

    /**
     * newPage
     * 
     * @param mixed $id          ID Page.
     * @param mixed $logo        File Logo.
     * @param mixed $description Description.
     * @param mixed $site        Url http.
     * @param mixed $phone       Telefono.
     * @param mixed $address     Indirizzo.
     * @param mixed $lat         Coordinata.
     * @param mixed $lon         Coordinata.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function newPage($id, $logo, $description, $site, $phone, $address, $lat, $lon) {
        $arrayNewPage = array(
            'user' => $id,
            'logo' => $logo,
            'description' => $description,
            'telephone' => $phone,
            'site' => $site,
            'address' => $address,
            'latitude' => $lat,
            'longitude' => $lon,
            'status' => 0,
            'registered' => new Zend_Db_Expr('NOW()'),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->insert($arrayNewPage);
    }

    /**
     * updatePage
     * 
     * @param mixed $id          ID Page.
     * @param mixed $description Description.
     * @param mixed $site        Url http.
     * @param mixed $phone       Telefono.
     * @param mixed $address     Indirizzo.
     * @param mixed $lat         Coordinata.
     * @param mixed $lon         Coordinata.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updatePage($id, $description, $site, $phone, $address, $lat, $lon) {
        $arrayUpdate = array(
            'description' => $description,
            'telephone' => $phone,
            'site' => $site,
            'address' => $address,
            'latitude' => $lat,
            'longitude' => $lon,
            'modified' => new Zend_Db_Expr('NOW()'),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update($arrayUpdate, sprintf('user = %d', $id));
    }

    /**
     * updateLogo
     * 
     * @param mixed $id    ID Page.
     * @param mixed $image Files Logo.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateLogo($id, $image) {
        $arrayLogo = array(
            'logo' => $image,
            'modified' => new Zend_Db_Expr('NOW()'),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update($arrayLogo, sprintf('user = %d', $id));
    }


}
