<?php

/**
 *
 */
class Application_Model_DbTable_Links extends Zend_Db_Table_Abstract
{

    /**
     * @var string
     */
    protected $_name = 'ads_links';

    /**
     * @var string
     */
    protected $_primary = 'id';

    /**
     * @param $url String URL
     * @param $location Mapping Page
     *
     * @return mixed
     */
    public function Mapping_Url($url, $location)
    {
        $arrayName = array(
            'url'        => $url,
            'location'   => $location,
            'registered' => new Zend_Db_Expr('NOW()'),
            'computer'   => $_SERVER['HTTP_USER_AGENT'],
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->insert($arrayName);
    }


}

