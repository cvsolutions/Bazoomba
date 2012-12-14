<?php

/**
 * ContentController
 *
 * @uses     Zend_Controller_Action
 *
 * @category Content
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class ContentController extends Zend_Controller_Action
{

    /**
     * init
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function init() {
        /* Initialize action controller here */
        Plugin_Common::Chech_Off_Line();
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
     * privacyAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function privacyAction() {
        // action body
    }

    /**
     * aboutAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function aboutAction() {
        // action body
    }

    /**
     * termsAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function termsAction() {
        // action body
    }


}
