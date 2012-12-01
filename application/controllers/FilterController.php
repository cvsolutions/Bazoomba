<?php

class FilterController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function regionAction()
    {
        $id = $this->_getParam('id', 0);
        $shop = new Application_Model_DbTable_Shop();
        $this->view->list = $shop->fullShopFilter(array('type' => 'region', 'id' => $id));
    }

    public function categoryAction()
    {
        $id = $this->_getParam('id', 0);
        $shop = new Application_Model_DbTable_Shop();
        $this->view->list = $shop->fullShopFilter(array('type' => 'category', 'id' => $id));
    }


}





