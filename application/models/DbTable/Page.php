<?php
class Application_Model_DbTable_Page extends Zend_Db_Table_Abstract
{

    protected $_name = 'ads_page';

    protected $_primary = 'id';

    public function getMyPage($id, $type)
    {
        if($type == 'count')
        {
        $row = $this->fetchAll(sprintf('user = %d', $id));
        } else {
        $row = $this->fetchRow(sprintf('user = %d', $id));
        }

        if(!$row)
        {
            $params = Plugin_Common::getParams();
            throw new Exception($params->label_no_id, 1);
        }
        return $row->toArray();
    }

    public function newPage($id, $logo, $description, $site, $phone, $address, $lat, $lon)
    {
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
            'ip_address' => $_SERVER['REMOTE_ADDR'],
        );
        return $this->insert($arrayNewPage);
    }

    public function updatePage($id, $description, $site, $phone, $address, $lat, $lon)
    {
        $arrayUpdate = array(
            'description' => $description,
            'telephone' => $phone,
            'site' => $site,
            'address' => $address,
            'latitude' => $lat,
            'longitude' => $lon,
        );
        return $this->update($arrayUpdate, sprintf('user = %d', $id));
    }

    public function updateLogo($id, $image)
    {
        $arrayLogo = array(
            'logo' => $image
        );
        return $this->update($arrayLogo, sprintf('user = %d', $id));
    }

}
