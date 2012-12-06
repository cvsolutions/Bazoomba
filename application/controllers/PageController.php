<?php

class PageController extends Zend_Controller_Action
{

    public function init()
    {
        // action
    }

    public function indexAction()
    {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();
        $this->view->identity = $identity;

        if($identity->type == 1) $this->_redirect('/account');

        $page = new Application_Model_DbTable_Page();
        if(count($page->getMyPage($identity->id, 'count')) == 0) $this->_redirect('/page/new');

        $this->view->page = $page->getMyPage($identity->id, null);
    }

    public function newAction()
    {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();
        $this->view->identity = $identity;

        if($identity->type == 1) $this->_redirect('/account');

        $page = new Application_Model_DbTable_Page();
        if(count($page->getMyPage($identity->id, 'count')) > 0) $this->_redirect('/page');

        $form = new Application_Form_Page();
        $newpage = $form->newPage();
        $newpage->image->addFilter('Rename', sprintf('%s.jpg', uniqid()));
        $this->view->newForm = $newpage;

        if ($this->getRequest()->getPost())
        {
               $form_data = $this->getRequest()->getPost();
               if ($form->isValid($form_data)) {

                   $logo = $form->getValue('image');
                   $site = $this->_getParam('site');
                   $description = $this->_getParam('description');
                   $phone = $this->_getParam('telephone');

                   $media = new Application_Model_DbTable_Page();
                   $media->newPage($identity->id, $logo, $description, $site, $phone);

                   $this->_redirect('/page');
               } else {
                   $form->populate($form_data);
               }
        }
    }

}
