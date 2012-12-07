<?php
/**
 * /tmp/phptidy-sublime-buffer.php
 *
 * @package default
 */


class Application_Model_DbTable_Page extends Zend_Db_Table_Abstract
{

    protected $_name = 'ads_page';

    protected $_primary = 'id';

    /**
     *
     *
     * @param unknown $id
     * @param unknown $type
     * @return unknown
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
     *
     *
     * @param unknown $id
     * @param unknown $logo
     * @param unknown $description
     * @param unknown $site
     * @param unknown $phone
     * @param unknown $address
     * @param unknown $lat
     * @param unknown $lon
     * @return unknown
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
            'registered' => time(),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->insert($arrayNewPage);
    }


    /**
     *
     *
     * @param unknown $id
     * @param unknown $description
     * @param unknown $site
     * @param unknown $phone
     * @param unknown $address
     * @param unknown $lat
     * @param unknown $lon
     * @return unknown
     */
    public function updatePage($id, $description, $site, $phone, $address, $lat, $lon) {
        $arrayUpdate = array(
            'description' => $description,
            'telephone' => $phone,
            'site' => $site,
            'address' => $address,
            'latitude' => $lat,
            'longitude' => $lon,
            'modified' => time(),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update($arrayUpdate, sprintf('user = %d', $id));
    }


    /**
     *
     *
     * @param unknown $id
     * @param unknown $image
     * @return unknown
     */
    public function updateLogo($id, $image) {
        $arrayLogo = array(
            'logo' => $image,
            'modified' => time(),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update($arrayLogo, sprintf('user = %d', $id));
    }


}
