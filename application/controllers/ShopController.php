<?php

class ShopController extends Zend_Controller_Action
{

    public $params = null;

    public function init()
    {
        $this->params = Plugin_Common::getParams();
    }

    public function indexAction()
    {
        // action body
    }

    public function newAction()
    {
        $form = new Application_Form_Shop();
        $this->view->newShop= $form->newShop();

        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();
        $this->view->identity = $identity;

        if ($this->getRequest()->getPost())
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
                $description = $form->getValue('description');
                $price = $form->getValue('price');
                $latitude = $form->getValue('latitude');
                $longitude = $form->getValue('longitude');

                $shop = new Application_Model_DbTable_Shop();
                $shop->newShop($category, $sub_category, $region, $province, $type, $title, $description, $price, $latitude, $longitude);

                $email_admin = Plugin_Common::getParams();
                Plugin_Common::getMail(array(
                    'email' => $email_admin->admin_email,
                    'subject' => '[DA CONFERMARE] Nuova Annuncio su Bazoomba.it',
                    'template' => 'shop_confirm_admin.phtml',
                    'params' => array(
                        'title' => $title,
                        'description' => $description,
                        )
                    ));

                $this->view->successForm = 'Grazie per aver inserito il tuo annuncio, a breve verrà esaminato.';
                $this->view->headMeta()->appendHttpEquiv('refresh','3; url=/');

            } else {
                $form->populate($form_data);
            }
        }

    }

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

    public function mediaAction()
    {
        // action body
    }


}







