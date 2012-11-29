<?php

/**
* IndexController
*
* @uses     Zend_Controller_Action
*
* @category Index
* @package  Bazoomba.it
* @author   Concetto Vecchio
* @license  
* @link     
*/
class IndexController extends Zend_Controller_Action
{

    /**
     * init
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function init()
    {
        /* Initialize action controller here */
    }

    /**
     * indexAction
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function indexAction()
    {
        $last_Ads = new Application_Model_DbTable_Shop();
        $this->view->shop = $last_Ads->LastHomeShop();
    }


}

