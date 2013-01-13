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
     * $params
     *
     * @var mixed
     *
     * @access public
     */
    public $params;

    /**
     * init
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function init() {

        /* Initialize action controller here */
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

        /* @var Application_Model_DbTable_Shop [Shop] */
        $shop = new Application_Model_DbTable_Shop();

        /* @var [g_chart_ads] [Annunci totali] */
        $this->view->g_chart_ads = $shop->fetchAll();

        /* @var [g_chart_active] [Annunci attivi] */
        $this->view->g_chart_active = $shop->fetchAll( 'status = 1' );

        /** @var [g_chart_suspended] [Annunci sospesi] */
        $this->view->g_chart_suspended = $shop->fetchAll( 'status = 0' );

        /**
         * ultimi annunci inseriti
         * ultimi modificati nello stesso giorno
         * annunci in scadenza
         */
        $this->view->last_ads = $shop->LastInsertAdminShop();
        $this->view->view_ads = $shop->LastEditAdminShop();
        $this->view->expir_ads = $shop->LastExpirAdminShop();

        /* @var Application_Model_DbTable_User [User] */
        $user = new Application_Model_DbTable_User();

        /* @var [g_chart_user] [totale degli utenti registrati] */
        $this->view->g_chart_user = $user->fetchAll();

        /* @var Application_Model_DbTable_Gallery [Gallery] */
        $gallery = new Application_Model_DbTable_Gallery();

        /* @var [g_chart_image] [totale delle immagini] */
        $this->view->g_chart_image = $gallery->fetchAll();

        /* @var [type] [authentication] */
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();

        $this->view->identity = $identity;
        $this->view->notfound = $this->params->label_not_found;
    }


}
