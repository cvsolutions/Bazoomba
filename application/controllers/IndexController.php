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
     * $params
     *
     * @var mixed
     *
     * @access public
     */
    public $params = null;

    /**
     * init
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function init() {
        $this->params = Plugin_Common::getParams();
    }

    /**
     * indexAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function indexAction() {
        $last_Ads = new Application_Model_DbTable_Shop();
        $this->view->type_ads = $this->params->type_ads->toArray();
        $this->view->type_user = $this->params->type_user->toArray();
        $this->view->shop = $last_Ads->LastHomeShop();
    }


}

