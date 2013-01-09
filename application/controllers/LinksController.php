<?php

/**
 *
 */
class LinksController extends Zend_Controller_Action
{

    /**
     * disable Layout
     */
    public function preDispatch() {

        /* disable Layout */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );
    }

    public function init()
    {
        /* Initialize action controller here */
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
            if(isset($_SESSION['ADS_Mapping']))
            {
                $this->redirect($url);
                exit();
            } else {
                $_SESSION['ADS_Mapping'] = rand();
                $Links = new Application_Model_DbTable_Links();
                $Links->Mapping_Url($url, $location);
                $this->redirect($url);
                exit();
            }
        }
    }


}

