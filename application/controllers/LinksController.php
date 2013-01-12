<?php

/**
 *
 */
class LinksController extends Zend_Controller_Action
{

    /**
     * @var
     */
    private $_mapping;

    public function init()
    {
        /* disable Layout */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $this->_mapping = new Zend_Session_Namespace('ADS_Mapping');
    }

    /**
     * Mapping URL's
     */
    public function indexAction()
    {
        $url = $this->_getParam('url', 0);
        $location = $this->_getParam('location', 0);

        if($this->getRequest())
        {
            if(isset($this->_mapping->remember))
            {
                $this->redirect($url);
                exit();

            } else {
                $this->_mapping->remember = rand();
                $Links = new Application_Model_DbTable_Links();
                $Links->Mapping_Url($url, $location);
                $this->redirect($url);
                exit();
            }
        }
    }


}

