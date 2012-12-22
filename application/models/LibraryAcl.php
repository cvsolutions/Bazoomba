<?php

/**
 * Application_Model_LibraryAcl
 *
 * @uses     Zend_Acl
 *
 * @category Login User
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class Application_Model_LibraryAcl extends Zend_Acl
{

    /**
     * __construct
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function __construct() {

        /** define Roles */
        $this->addRole( new Zend_Acl_Role( 'guest' ) ); // not authenicated
        $this->addRole( new Zend_Acl_Role( 'user' ), 'guest' ); // authenticated as member inherit guest privilages
        $this->addRole( new Zend_Acl_Role( 'admin' ), 'guest' ); // authenticated as admin inherit member privilages

        /** define Resources */
        $this->add( new Zend_Acl_Resource( 'error' ) );
        $this->add( new Zend_Acl_Resource( 'index' ) );
        $this->add( new Zend_Acl_Resource( 'shop' ) );
        $this->add( new Zend_Acl_Resource( 'login' ) );
        $this->add( new Zend_Acl_Resource( 'setting' ) );
        $this->add( new Zend_Acl_Resource( 'dashboard' ) );
        $this->add( new Zend_Acl_Resource( 'category' ) );
        $this->add( new Zend_Acl_Resource( 'user' ) );
        $this->add( new Zend_Acl_Resource( 'account' ) );
        $this->add( new Zend_Acl_Resource( 'gallery' ) );
        $this->add( new Zend_Acl_Resource( 'filter' ) );
        $this->add( new Zend_Acl_Resource( 'ajax' ) );
        $this->add( new Zend_Acl_Resource( 'page' ) );
        $this->add( new Zend_Acl_Resource( 'content' ) );
        $this->add( new Zend_Acl_Resource( 'cronjob' ) );
        $this->add( new Zend_Acl_Resource( 'request' ) );

        /** assign privileges */
        $this->allow( 'guest', array( 'index', 'error' ) );
        $this->allow( 'guest', 'login', array( 'index', 'notauthorized' ) );
        $this->allow( 'guest', 'shop', array( 'index', 'ads' ) );
        $this->allow( 'guest', 'ajax', array( 'index', 'autocomplete','newuser','controlemail') );
        $this->allow( 'guest', 'user', array( 'index', 'lostpassword', 'resetpassword', 'new', 'confirm' ) );
        $this->allow( 'guest', 'filter', array( 'index', 'search', 'category', 'region', 'subcategory', 'province' ) );
        $this->allow( 'guest', 'content', array( 'index', 'about', 'privacy', 'terms' ) );
        $this->allow( 'guest', 'cronjob', array( 'expiration', 'eliminates', '' ) );
        $this->allow( 'guest', 'request', array( 'index', 'subscribe', 'edit', 'delete' ) );

        $this->allow( 'user', 'user', array( 'index', 'logout' ) );
        $this->allow( 'user', 'login', array( 'index', 'notauthorized' ) );
        $this->allow( 'user', 'account', array( 'index', 'edit', 'editpassword', 'avatar', 'logout' ) );
        $this->allow( 'user', 'shop', array( 'index', 'new', 'modification', 'media', 'public', 'my' ) );
        $this->allow( 'user', 'ajax', array( 'index', 'newshop', 'province', 'city', 'subcategory' ) );
        $this->allow( 'user', 'page', array( 'index', 'new', 'edit', 'picture','gallery', 'deletegallery') );

        $this->allow( 'admin', 'setting' );
        $this->allow( 'admin', 'dashboard' );
        $this->allow( 'admin', 'login', array( 'add', 'list', 'edit', 'delete', 'logout', 'notauthorized' ) );
        $this->allow( 'admin', 'shop', array( 'index', 'edit', 'list', 'gallery', 'delete', 'search', 'detail', 'status' ) );
        $this->allow( 'admin', 'category', array( 'add', 'list', 'edit', 'delete' ) );
        $this->allow( 'admin', 'user', array( 'index', 'edit', 'list', 'delete' ) );
        $this->allow( 'admin', 'gallery', array( 'index', 'list', 'status', 'delete' ) );
    }

}
