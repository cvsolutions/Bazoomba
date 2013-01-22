<?php

/**
 * ShopController
 *
 * @uses     Zend_Controller_Action
 *
 * @category Shop
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class ShopController extends Zend_Controller_Action
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
    public function init()
    {
        /* Initialize action controller here */
        $this->params = Plugin_Common::getParams();

        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();
        $this->view->identity = $identity;
    }

    /**
     * indexAction
     *
     * @access public
     *
     * @return mixed Value.
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
     */
    public function newAction()
    {
        $form = new Application_Form_Shop();
        $this->view->newShop = $form->newShop();
    }

    /**
     * editAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function editAction()
    {
        $id = $this->_getParam('id', 0);

        $shop = new Application_Model_DbTable_Shop();
        $row = $shop->getAdminShopInfo($id);

        $user = new Application_Model_DbTable_User();
        $UserInfo = $user->getAdminInfo($row['user']);

        $form = new Application_Form_Shop();
        $this->view->editForm = $form->editAdmin();

        if ($this->getRequest()->getPost()) {
            $form_data = $this->getRequest()->getPost();

            if ($form->isValid($form_data)) {
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

                switch ($status) {

                    case 0:
                        Plugin_Common::getMail(
                            array(
                                 'email'    => $UserInfo['email'],
                                 'reply'    => $this->params->noreplay,
                                 'subject'  => sprintf('Annuncio sospeso "%s"', $row['title']),
                                 'template' => 'shop_suspended.phtml',
                                 'params'   => array(
                                     'from_email' => $this->params->from_email,
                                     'title'      => $row['title'],
                                     'id'         => $row['id']
                                 )
                            )
                        );
                        break;

                    case 1:
                        Plugin_Common::getMail(
                            array(
                                 'email'    => $UserInfo['email'],
                                 'reply'    => $this->params->noreplay,
                                 'subject'  => sprintf('Annuncio pubblicato "%s"', $row['title']),
                                 'template' => 'shop_published.phtml',
                                 'params'   => array(
                                     'from_email' => $this->params->from_email,
                                     'title'      => $row['title'],
                                     'id'         => $row['id']
                                 )
                            )
                        );
                        break;

                    case 2:
                        // code...
                        break;
                }

                $shop->updateShopAdmin(
                    $id, $category, $sub_category, $region, $province, $city, $type, $title, $price, $description,
                    $status
                );

                $this->view->successForm = $this->params->label_success;
                $this->view->headMeta()->appendHttpEquiv('refresh', '3; url=/shop/list');

            } else {
                $form->populate($form_data);
            }

        } else {
            $form->populate($row);
        }
    }

    /**
     * listAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function listAction()
    {

        $search = $this->_getParam('search', 0);
        $q = $this->_getParam('q', 0);

        $list_shop = new Application_Model_DbTable_Shop();
        $this->view->shop = $list_shop->fullShop(array('search' => $search, 'q' => $q));
        $this->view->notfound = $this->params->label_not_found;
        $this->view->type_ads = $this->params->type_ads->toArray();
    }

    /**
     * detailAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function detailAction()
    {

        $id = $this->_getParam('id', 0);

        $shop = new Application_Model_DbTable_Shop();
        $ShopInfo = $shop->getAdminShopInfo($id);

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
        $this->view->gallery = $Gallery->fetchAll(sprintf('shop = %d', $id));

        $User = new Application_Model_DbTable_User();
        $UserInfo = $User->getAdminInfo($ShopInfo['user']);
        $this->view->user = $UserInfo;

        $this->view->row = $ShopInfo;
        $this->view->type_ads = $this->params->type_ads->toArray();
        $this->view->type_user = $this->params->type_user->toArray();
        $this->view->status = $this->params->status->toArray();
        $this->view->alert = $this->params->alert->toArray();
        $this->view->notfound = $this->params->label_not_found;
    }

    /**
     * deleteAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function deleteAction()
    {

        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();

        $id_ads = $this->_getParam('ads', 0);
        $type = $this->_getParam('item', 0);
        $title_shop = $this->_getParam('shop', 0);

        $shop = new Application_Model_DbTable_Shop();
        $ShopInfo = $shop->getSiteShopInfo($id_ads);

        if ($ShopInfo['user'] == $identity->id) {

            /** salvo la motivazione*/
            $delete = new Application_Model_DbTable_Delete();
            $query = $delete->save_delete($title_shop, $identity->id, $type);

            /**elimino le foto fisicamente e dal db*/
            $gallery = new Application_Model_DbTable_Gallery();
            $gallery->Delete_Media_Ads($id_ads);

            /**elimino l'annuncio*/
            $shop->Delete_Ads($id_ads);

            $this->_redirect('shop/my');
        }
    }

    /**
     * galleryAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function galleryAction()
    {
        // action body
    }

    /**
     * adsAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function adsAction()
    {


        Plugin_Common::Chech_Off_Line();
        $id = $this->_getParam('show', 0);

        $shop = new Application_Model_DbTable_Shop();
        $ShopInfo = $shop->getSiteShopInfo($id);

        $shop->updateVisits($id);

        $this->view->othersAdsPage = $shop->othersAdsPage($ShopInfo['user']);

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

        $User = new Application_Model_DbTable_User();
        $UserInfo = $User->getAdminInfo($ShopInfo['user']);
        $this->view->user = $UserInfo;
        $this->view->avatar = Plugin_Common::Control_Image('avatar', $UserInfo['avatar']);

        $Page = new Application_Model_DbTable_Page();
        $PageInfo = $Page->getMyPage($ShopInfo['user'], null);
        $this->view->page = $PageInfo;
        $this->view->logo = Plugin_Common::Control_Image('logo', $PageInfo['logo']);

        $this->view->notfound = $this->params->label_not_found_ads;

        $this->view->row = $ShopInfo;
        $this->view->random_geo = $shop->RandomGeoIPShop($id, $ShopInfo['latitude'], $ShopInfo['longitude']);
        $this->view->type_ads = $this->params->type_ads->toArray();
        $this->view->type_user = $this->params->type_user->toArray();

        $form = new Application_Form_Shop();
        $this->view->ReplyForm = $form->Reply_Advertiser();

        if ($this->getRequest()->getPost()) {
            $form_data = $this->getRequest()->getPost();
            if ($form->isValid($form_data)) {
                $name = $form->getValue('name');
                $mail = $form->getValue('mail');
                $description = $form->getValue('description');

                Plugin_Common::getMail(
                    array(
                         'email'    => $UserInfo['email'],
                         'reply'    => $mail,
                         'subject'  => sprintf('%s - Risposta a: %s', $this->params->from_email, $dd),
                         'template' => 'reply_advertiser.phtml',
                         'params'   => array(
                             'site'        => $this->params->from_email,
                             'title'       => $ShopInfo['title'],
                             'id'          => $ShopInfo['id'],
                             'description' => $description
                         )
                    )
                );

                $this->view->successForm = $this->params->label_success;

            } else {
                $form->populate($form_data);
            }
        }
    }

    /**
     * modificationAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function modificationAction()
    {
        // action body
    }

    /**
     * jsonAction
     * // http://bazoomba/shop/json/?q=iphone
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function jsonAction()
    {

        /* disable Layout */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        /* recupero i parametri */
        $category = $this->_getParam('category', 0);
        $region = $this->_getParam('region', 0);
        $q = $this->_getParam('q', 0);

        $Shop = new Application_Model_DbTable_Shop();
        $ads = $Shop->fullShopFilter(
            array(
                 'type'     => 'global',
                 'category' => $category,
                 'region'   => $region,
                 'q'        => $q
            )
        );

        $data = array();
        foreach ($ads as $row) {
            $result = array(
                'id'       => $row['id'],
                'title'    => $row['title'],
                'price'    => $row['price'],
                'photo'    => $row['photo'],
                'user'     => $row['user'],
                'province' => $row['province']
            );
            array_push($data, $result);
        }

        echo Zend_Json::encode($data);
    }

    /**
     * mediaAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function mediaAction()
    {
        $id_ads = $this->_getParam('id', 0);
        $this->view->id_ads = $id_ads;
        $shop = new Application_Model_DbTable_Shop();
        $ads = $shop->getAdminShopInfo($id_ads);


        if ($ads['step'] == 3) {
            $this->_redirect('/');
        }

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
                    $title = $form->getValue('title');
                    $id = $this->_getParam('id');

                    $media = new Application_Model_DbTable_Gallery();
                    $media->addMedia($id, $image, $title, 0);

                    $shop->updateStep($id_ads, 2);

                    $this->_redirect('/shop/media/id/' . $id);
                } else {
                    $form->populate($form_data);
                }
            }
        } else {
            $this->_redirect('/');
        }
    }

    /**
     * publicAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function publicAction()
    {

        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();
        $this->view->identity = $identity;

        $id_ads = $this->_getParam('id', 0);

        $shop = new Application_Model_DbTable_Shop();
        $row = $shop->getAdminShopInfo($id_ads);

        if ($row['step'] == 3) {
            $this->_redirect('/');
        }

        // controlla se l'annuncio appartiene all'utente loggato
        if (count($shop->controlAds($id_ads)) == 1) {

            $email_admin = Plugin_Common::getParams();
            Plugin_Common::getMail(
                array(
                     'email'    => $email_admin->admin_email,
                     'reply'    => $this->params->noreplay,
                     'subject'  => '[DA CONFERMARE] Nuova Annuncio su Bazoomba.it',
                     'template' => 'shop_confirm_admin.phtml',
                     'params'   => array(
                         'title'       => $row['title'],
                         'description' => $row['description'],
                         'id'          => $row['id']
                     )
                )
            );

            $shop->updateStep($id_ads, 3);
            $this->view->headMeta()->appendHttpEquiv('refresh', '3; url=/');
        }
    }

    /**
     * myAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function myAction()
    {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();
        $this->view->identity = $identity;

        $shop = new Application_Model_DbTable_Shop();
        $this->view->myshop = $shop->myshop($identity->id);

    }

    /**
     * searchAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function searchAction()
    {
        $region = new Application_Model_DbTable_Region();
        $this->view->region = $region->fetchAll();

        $category = new Application_Model_DbTable_Category();
        $this->view->category = $category->Parent_With_Category(0);

        $this->view->type_ads = $this->params->type_ads->toArray();
        $this->view->status = $this->params->status->toArray();
    }

    /**
     * statusAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function statusAction()
    {
        $type = $this->_getParam('type', 0);
        $id = $this->_getParam('id', 0);

        $shop = new Application_Model_DbTable_Shop();
        $ShopInfo = $shop->getAdminShopInfo($id);

        $user = new Application_Model_DbTable_User();
        $UserInfo = $user->getAdminInfo($ShopInfo['user']);

        switch ($type) {

            case 0:
                Plugin_Common::getMail(
                    array(
                         'email'    => $UserInfo['email'],
                         'reply'    => $this->params->noreplay,
                         'subject'  => sprintf('Annuncio sospeso "%s"', $ShopInfo['title']),
                         'template' => 'shop_suspended.phtml',
                         'params'   => array(
                             'from_email' => $this->params->from_email,
                             'title'      => $ShopInfo['title'],
                             'id'         => $ShopInfo['id']
                         )
                    )
                );
                $shop->updateStatus($id, 0);
                break;

            case 1:
                Plugin_Common::getMail(
                    array(
                         'email'    => $UserInfo['email'],
                         'reply'    => $this->params->noreplay,
                         'subject'  => sprintf('Annuncio pubblicato "%s"', $ShopInfo['title']),
                         'template' => 'shop_published.phtml',
                         'params'   => array(
                             'from_email' => $this->params->from_email,
                             'title'      => $ShopInfo['title'],
                             'id'         => $ShopInfo['id']
                         )
                    )
                );
                $shop->updateStatus($id, 1);
                break;

            case 2:
                // code...
                break;
        }

        $this->view->successForm = $this->params->label_success;
        $this->view->headMeta()->appendHttpEquiv('refresh', '3; url=/shop/list');
    }

}
