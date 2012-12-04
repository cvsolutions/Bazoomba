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
        $id = $this->_getParam('show', 0);

        $shop = new Application_Model_DbTable_Shop();
        $ShopInfo = $shop->getSiteShopInfo($id);

        $Category = new Application_Model_DbTable_Category();
        $this->view->category = $Category->getCategoryInfo($ShopInfo['category']);
        $this->view->sub_category = $Category->getCategoryInfo($ShopInfo['sub_category']);

        $Region = new Application_Model_DbTable_Region();
        $this->view->region = $Region->getRegionInfo($ShopInfo['region']);

        $Provinces = new Application_Model_DbTable_Provinces();
        $this->view->province = $Provinces->getProvinceInfo($ShopInfo['province']);

        $City = new Application_Model_DbTable_City();
        $this->view->city = $City->getCityInfo($ShopInfo['city']);

        $Gallery = new Application_Model_DbTable_Gallery();
        $this->view->gallery = $Gallery->fetchAll(sprintf('shop = %d AND status = 1', $id));

        $this->view->row = $shop->getSiteShopInfo($id);
        $this->view->type_ads = $this->params->type_ads->toArray();
    }

    public function modificationAction()
    {
        // action body
    }

    public function jsonAction()
    {
        // action body
    }

    public function mediaAction() {
       $id_ads = $this->_getParam('id', 0);
       $this->view->id_ads = $id_ads;
       $shop = new Application_Model_DbTable_Shop();

       $gallery = new Application_Model_DbTable_Gallery();
       $this->view->gallery = $gallery->fetchAll(sprintf('shop = %d', $id_ads));

         //controlla se l'annuncio appartiene all'utente loggato
       if (count($shop->controlAds($id_ads)) == 1) {

           $form = new Application_Form_Shop();
           $media = $form->addMedia();
           $media->image->addFilter('Rename', sprintf('%s.jpg', uniqid()));
           $this->view->addMedia = $media;

           $auth = Zend_Auth::getInstance();
           $identity = $auth->getStorage()->read();
           $this->view->identity = $identity;

           if ($this->getRequest()->getPost()) {
               $form_data = $this->getRequest()->getPost();
               if ($form->isValid($form_data)) {
                   $image = $form->getValue('image');
                   $id = $this->_getParam('id');

                   $media = new Application_Model_DbTable_Gallery();
                   $media->addMedia($id, $image);
                   $this->_redirect('/shop/media/id/' . $id);
               } else {
                   $form->populate($form_data);
               }
           }
       } else {
           $this->_redirect('/');
       }
   }

   public function publicAction() {

       $auth = Zend_Auth::getInstance();
       $identity = $auth->getStorage()->read();
       $this->view->identity = $identity;

       $id_ads = $this->_getParam('id', 0);

       $shop = new Application_Model_DbTable_Shop();
       $row = $shop->getAdminShopInfo($id_ads);

         //controlla se l'annuncio appartiene all'utente loggato
       if (count($shop->controlAds($id_ads)) == 1)
       {

           $email_admin = Plugin_Common::getParams();
           Plugin_Common::getMail(array(
             'email' => $email_admin->admin_email,
             'subject' => '[DA CONFERMARE] Nuova Annuncio su Bazoomba.it',
             'template' => 'shop_confirm_admin.phtml',
             'params' => array(
               'title' => $row['title'],
               'description' => $row['description'],
               )
             ));
       }
   }


}
