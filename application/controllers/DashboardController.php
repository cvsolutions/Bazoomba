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
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();

        $shop = new Application_Model_DbTable_Shop();
        $this->view->g_chart_ads = $shop->fetchAll();
        $this->view->g_chart_active = $shop->fetchAll('status = 1');
        $this->view->g_chart_suspended = $shop->fetchAll('status = 0');
        $this->view->last_ads = $shop->LastInsertAdminShop();
        $this->view->view_ads = $shop->LastEditAdminShop();
        $this->view->expir_ads = $shop->LastExpirAdminShop();

        $user = new Application_Model_DbTable_User();
        $this->view->g_chart_user = $user->fetchAll();

        $gallery = new Application_Model_DbTable_Gallery();
        $this->view->g_chart_image = $gallery->fetchAll();

        $this->view->identity = $identity;
        $this->view->notfound = $this->params->label_not_found;
    }


}
