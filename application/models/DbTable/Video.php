<?php

/**
* Application_Model_DbTable_Video
*
* @uses     Zend_Db_Table_Abstract
*
* @category Video
* @package  Bazoomba.it
* @author   Concetto Vecchio
* @license  
* @link     
*/
class Application_Model_DbTable_Video extends Zend_Db_Table_Abstract
{

    /**
     * $_name
     *
     * @var string
     *
     * @access protected
     */
    protected $_name = 'ads_video';

    /**
     * $_primary
     *
     * @var string
     *
     * @access protected
     */
    protected $_primary = 'id';

    /**
     * newVideo
     * 
     * @param mixed $shop  Description.
     * @param mixed $url   Description.
     * @param mixed $video Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function newVideo( $shop, $url, $video ) {

        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();

        $arrayVideo = array(
            'user' => $identity->id,
            'shop' => $shop,
            'type' => $video,
            'registered' => new Zend_Db_Expr( 'NOW()' ),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'status' => 1
        );
        return $this->insert( $arrayVideo );
    }

}
