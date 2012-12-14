<?php

/**
 * CronjobController
 *
 * @uses     Zend_Controller_Action
 *
 * @category Cronjob
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class CronjobController extends Zend_Controller_Action
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
     * preDispatch
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function preDispatch() {

        /* disable Layout */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );
    }

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
        // action body
    }

    /**
     * expirationAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function expirationAction() {
        // action body
    }

    /**
     * eliminatesAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function eliminatesAction() {
        // action body
    }


}
