<?php

/**
 * GalleryController
 *
 * @uses     Zend_Controller_Action
 *
 * @category Gallery
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class GalleryController extends Zend_Controller_Action
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
    public function init() {
        /* Initialize action controller here */
        $this->params = Plugin_Common::getParams();
    }

    /**
     * indexAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function indexAction() {
        // action body
    }

    /**
     * listAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function listAction() {
        $shop = $this->_getParam( 'shop', 0 );
        $gallery = new Application_Model_DbTable_Gallery();
        $info_shop = new Application_Model_DbTable_Shop();

        $this->view->gallery = $gallery->fetchAll( sprintf( 'shop = %d', $shop ) );
        $this->view->btn_class = array( 0 => 'alert', 1 => 'success' );
        $this->view->notfound = $this->params->label_not_found;
        $this->view->shop = $info_shop->getAdminShopInfo( $shop );
    }

    /**
     * deleteAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function deleteAction() {
        $id = $this->_getParam( 'id', 0 );
        $ads = $this->_getParam( 'ads', 0 );

        $gallery = new Application_Model_DbTable_Gallery();
        $gallery->deleteImageGallery( $id );
        $this->_redirect( sprintf( '/gallery/list/shop/%d', $ads ) );
    }

    /**
     * editAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function editAction() {
        // action body
    }

    /**
     * statusAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function statusAction() {
        $type = $this->_getParam( 'type', 0 );
        $id = $this->_getParam( 'id', 0 );
        $ads = $this->_getParam( 'ads', 0 );

        $gallery = new Application_Model_DbTable_Gallery();
        $gallery->updateStatusGallery( $id, $type, $ads );
        $this->redirect( sprintf( '/gallery/list/shop/%d', $ads ) );
    }


}
