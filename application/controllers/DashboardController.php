<?php

/**
* DashboardController
*
* @uses     Zend_Controller_Action
*
* @category Dashboard
* @package  Bazoomba.it
* @author   Concetto Vecchio
* @license  
* @link     
*/
class DashboardController extends Zend_Controller_Action
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
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();
        $this->view->identity = $identity;
    }


}

