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

    public function newPage($id, $logo, $description, $site, $phone)
    {
        $arrayNewPage = array(
            'user' => $id,
            'logo' => $logo,
            'description' => $description,
            'telephone' => $phone,
            'site' => $site,
            'status' => 0,
            'registered' => time(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
        );
        return $this->insert($arrayNewPage);
    }
}
