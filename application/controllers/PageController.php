<?php

/**
 * PageController
 *
 * @uses     Zend_Controller_Action
 *
 * @category Page
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 *
 */

class PageController extends Zend_Controller_Action
{

    /**
     * init
     *
     * @access public
     *
     * @return mixed Value.
     *
     */
    public function init()
    {

    }

    /**
     * indexAction
     *
     * @access public
     *
     * @return mixed Value.
     *
     */
    public function indexAction()
    {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();
        $this->view->identity = $identity;

        if ( $identity->type == 1 )
            $this->_redirect( '/account' );

        $page = new Application_Model_DbTable_Page();
        if ( count( $page->getMyPage( $identity->id, 'count' ) ) == 0 )
            $this->_redirect( '/page/new' );
        $this->view->page = $page->getMyPage( $identity->id, null );
    }

    /**
     * newAction
     *
     * @access public
     *
     * @return mixed Value.
     *
     */
    public function newAction()
    {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();
        $this->view->identity = $identity;

        if ( $identity->type == 1 )
            $this->_redirect( '/account' );

        $page = new Application_Model_DbTable_Page();
        if ( count( $page->getMyPage( $identity->id, 'count' ) ) > 0 )
            $this->_redirect( '/page' );

        $form = new Application_Form_Page();
        $newpage = $form->newPage();
        $newpage->image->addFilter( 'Rename', sprintf( '%s.jpg', uniqid() ) );
        $this->view->newForm = $newpage;

        if ( $this->getRequest()->getPost() ) {
            $form_data = $this->getRequest()->getPost();
            if ( $form->isValid( $form_data ) ) {

                $logo = $form->getValue( 'image' );
                $site = $this->_getParam( 'site' );
                $description = $this->_getParam( 'description' );
                $phone = $this->_getParam( 'telephone' );
                $address = $this->_getParam( 'address' );
                $lat = $this->_getParam( 'latitude' );
                $lon = $this->_getParam( 'longitude' );

                $media = new Application_Model_DbTable_Page();
                $media->newPage( $identity->id, $logo, $description, $site, $phone, $address, $lat, $lon );

                $this->_redirect( '/page' );
            } else {
                $form->populate( $form_data );
            }
        }
    }

    /**
     * editAction
     *
     * @access public
     *
     * @return mixed Value.
     *
     */
    public function editAction()
    {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();
        $this->view->identity = $identity;

        $page = new Application_Model_DbTable_Page();
        if ( count( $page->getMyPage( $identity->id, 'count' ) ) == 0 )
            $this->_redirect( '/page/new' );
        $row = $page->getMyPage( $identity->id, null );


        $form = new Application_Form_Page();
        $this->view->editForm = $form->editData();

        if ( $this->getRequest()->getPost() ) {
            $form_data = $this->getRequest()->getPost();
            if ( $form->isValid( $form_data ) ) {
                $site = $this->_getParam( 'site' );
                $description = $this->_getParam( 'description' );
                $phone = $this->_getParam( 'telephone' );
                $address = $this->_getParam( 'address' );
                $lat = $this->_getParam( 'latitude' );
                $lon = $this->_getParam( 'longitude' );

                $page->updatePage( $identity->id, $description, $site, $phone, $address, $lat, $lon );
                $this->_redirect( '/page' );
            } else {
                $form->populate( $form_data );
            }
        } else {
            $form->populate( $row );
        }
    }

    /**
     * pictureAction
     *
     * @access public
     *
     * @return mixed Value.
     *
     */
    public function pictureAction()
    {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();
        $this->view->identity = $identity;

        if ( $identity->type == 1 )
            $this->_redirect( '/account' );

        $page = new Application_Model_DbTable_Page();
        $row = $page->getMyPage( $identity->id, null );

        $form = new Application_Form_Page();
        $picture = $form->editPicture();
        $picture->image->addFilter( 'Rename', sprintf( '%s.jpg', uniqid() ) );
        $this->view->editPicture = $picture;
        $this->view->picture = $row['logo'];

        if ( $this->getRequest()->getPost() ) {
            $form_data = $this->getRequest()->getPost();
            if ( $form->isValid( $form_data ) ) {

                if ( $row['logo'] > 0 ) unlink( sprintf( '%s/uploaded/logo/%s', $_SERVER['DOCUMENT_ROOT'], $row['logo'] ) );

                $image = $form->getValue( 'image' );

                $page->updateLogo( $identity->id, $image );

                $this->_redirect( '/page' );
            } else {
                $form->populate( $form_data );
            }
        }
    }

    /**
     * galleryAction
     *
     * @access public
     *
     * @return mixed Value.
     *
     */
    public function galleryAction()
    {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();
        $this->view->identity = $identity;

        if ( $identity->type == 1 ) $this->_redirect( '/account' );

        $page = new Application_Model_DbTable_Page();
        if ( count( $page->getMyPage( $identity->id, 'count' ) ) == 0 )$this->_redirect( '/page/new' );
        $infoPage = $page->getMyPage( $identity->id, null );

        $form = new Application_Form_Page();
        $formGallery = $form->gallery();
        $formGallery->image->addFilter( 'Rename', sprintf( '%s.jpg', uniqid() ) );
        $this->view->galleryForm = $formGallery;

        $image = new Application_Model_DbTable_Gallery();
        $this->view->gallery = $image->galleryPage( $infoPage['id'] );

        if ( $this->getRequest()->getPost() ) {
            $form_data = $this->getRequest()->getPost();
            if ( $form->isValid( $form_data ) ) {
                $image = $form->getValue( 'image' );

                $gallery = new Application_Model_DbTable_Gallery();
                $gallery->addMedia( $infoPage['id'], $image, 1 );

                $this->_redirect( '/page/gallery' );
            } else {
                $form->populate( $form_data );
            }
        }
    }

    /**
     * deletegalleryAction
     *
     * @access public
     *
     * @return mixed Value.
     *
     */
    public function deletegalleryAction()
    {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();
        $this->view->identity = $identity;

        $page = new Application_Model_DbTable_Page();
        if ( count( $page->getMyPage( $identity->id, 'count' ) ) == 0 )$this->_redirect( '/page/new' );
        $infoPage = $page->getMyPage( $identity->id, null );

        $item = $this->_getParam( 'item', 0 );

        $gallery = new Application_Model_DbTable_Gallery();
        $gallery->deleteGalleryPage( $item, $infoPage['id'] );

        $this->_redirect( '/page/gallery' );
    }

    public function siteAction()
    {
        $id = $this->_getParam( 'item', 0 );
        $Page = new Application_Model_DbTable_Page();
        $PageInfo = $Page->getInfoPage($id);
        $this->view->page = $PageInfo;

        $User = new Application_Model_DbTable_User();
        $this->view->user = $User->getAdminInfo( $PageInfo['user'] );

        $this->view->logo = Plugin_Common::Control_Image('logo', $PageInfo['logo']);

        $image = new Application_Model_DbTable_Gallery();
        $this->view->gallery = $image->galleryPage( $PageInfo['id'] );

        $shop = new Application_Model_DbTable_Shop();
        $this->view->ads = $shop->othersAdsPage($PageInfo['user']);
    }


}

