<?php

class ShopController extends Zend_Controller_Action
{

    /**
     * $params
     *
     * @var mixed
     *
     * @access public
     *
     *
     *
     */
    public $params = null;

    /**
     * init
     *
     * @access public
     *
     * @return mixed Value.
     *
     *
     *
     */
    public function init()
    {
        $this->params = Plugin_Common::getParams();
    }

    /**
     * indexAction
     *
     * @access public
     *
     * @return mixed Value.
     *
     *
     *
     */
    public function indexAction()
    {
        // action body
    }

    /**
     * newAction
     *
     * @access public
     *
     * @return mixed Value.
     *
     *
     *
     */
    public function newAction()
    {
        // action body
    }

    /**
     * editAction
     *
     * @access public
     *
     * @return mixed Value.
     *
     *
     *
     */
    public function editAction()
    {
        $id = $this->_getParam('id', 0);
        $shop = new Application_Model_DbTable_Shop();
        $row = $shop->getAdminShopInfo($id);

        $form = new Application_Form_Shop();
        $this->view->editForm = $form->editAdmin();

        if($this->getRequest()->getPost())
        {
            $form_data = $this->getRequest()->getPost();
            if($form->isValid($form_data))
            {
                $category = $form->getValue('category');
                $sub_category = $form->getValue('sub_category');
                $region = $form->getValue('region');
                $province = $form->getValue('province');
                $city = $form->getValue('city');
                $type = $form->getValue('type');
                $title = $form->getValue('title');
                $price = $form->getValue('price');
                $description = $form->getValue('description');
                $status = $form->getValue('status');

                $shop->updateShopAdmin($id, $category, $sub_category, $region, $province, $city, $type, $title, $price, $description, $status);
                $this->view->successForm = $this->params->label_success;
                $this->view->headMeta()->appendHttpEquiv('refresh','3; url=/shop/list');
                
            } else {
                $form->populate($form_data);
            }

        } else {
            $form->populate($row);    
        }     
    }

    public function listAction()
    {
        $list_shop = new Application_Model_DbTable_Shop();
        $this->view->shop = $list_shop->fullShop();
        $this->view->notfound = $this->params->label_not_found;
        $this->view->type_ads = $this->params->type_ads->toArray();
    }

    public function detailAction()
    {
        // action body
    }

    public function deleteAction()
    {
        // action body
    }

    public function galleryAction()
    {
        // action body
    }

    public function adsAction()
    {
        // action body
    }

    public function modificationAction()
    {
        // action body
    }

    public function jsonAction()
    {
        // action body
    }


}





