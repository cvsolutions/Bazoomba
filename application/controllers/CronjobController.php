<?php

class CronjobController extends Zend_Controller_Action
{

    public $params = null;

    public function preDispatch() {

        /* disable Layout */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );
    }

    public function init() {
        
        /* Initialize action controller here */
        $this->params = Plugin_Common::getParams();
    }

    public function indexAction() {
        // action body
    }

    public function expirationAction() {
        // action body
    }

    public function eliminatesAction() {
        // action body
    }

    public function remembernewadsAction() {
        // action body
    }


}
