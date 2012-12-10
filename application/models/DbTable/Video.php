<?php

class Application_Model_DbTable_Video extends Zend_Db_Table_Abstract
{

    protected $_name = 'ads_video';

    protected $_primary = 'id';

    public function newVideo($shop, $url, $video) {

        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();

        $arrayVideo = array(
            'user' => $identity->id,
            'shop' => $shop,
            'type' => $video,
            'registered' => new Zend_Db_Expr('NOW()'),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'status' => 1
        );
        return $this->insert($arrayVideo);
    }

}

