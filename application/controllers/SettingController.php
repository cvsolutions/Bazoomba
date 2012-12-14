<?php

/**
 * SettingController
 *
 * @uses     Zend_Controller_Action
 *
 * @category Setting
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class SettingController extends Zend_Controller_Action
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
	 * editAction
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function editAction() {

		/* @var Application_Form_Setting [Form] */
		$form = new Application_Form_Setting();
		$this->view->settingForm = $form;

		/* @var Application_Model_DbTable_Setting [Setting] */
		$setting = new Application_Model_DbTable_Setting();

		if ( $this->getRequest()->getPost() ) {

			/* @var [form_data] [Recupero dei dati da un form] */
			$form_data = $this->getRequest()->getPost();

			if ( $form->isValid( $form_data ) ) {

				/* assegno le variabili */
				$title = $form->getValue( 'title' );
				$description = $form->getValue( 'description' );
				$keywords = $form->getValue( 'keywords' );
				$off_line = $form->getValue( 'off_line' );

				/* aggiorno i record */
				$setting->updateSettings( $title, $description, $keywords, $off_line );
				$this->view->successForm = $this->params->label_success;
				$this->view->headMeta()->appendHttpEquiv( 'refresh', '3; url=/dashboard' );
			}

		} else {

			/* ripopolo il form */
			$form->populate( $setting->getSettings() );
		}
	}


}
