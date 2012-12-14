<?php

/**
 * LoginController
 *
 * @uses     Zend_Controller_Action
 *
 * @category Login
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class LoginController extends Zend_Controller_Action
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
	 * $login
	 *
	 * @var mixed
	 *
	 * @access public
	 */
	public $login;

	/**
	 * $form
	 *
	 * @var mixed
	 *
	 * @access public
	 */
	public $form;

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

		/* @var Application_Model_DbTable_Login [Login] */
		$this->login = new Application_Model_DbTable_Login();

		/* @var Application_Form_Login [Form] */
		$this->form = new Application_Form_Login();
	}

	/**
	 * indexAction
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function indexAction() {

		$this->view->loginForm = $this->form->login();

		/* @var [auth] [authentication] */
		$auth = Zend_Auth::getInstance();
		if ( $auth->hasIdentity() ) {

			/**
			 * controllo se ho effettuato il login
			 * in precedenza, in tal caso eseguo direttamente
			 * l'accesso alla dashboard
			 */
			$this->_redirect( '/dashboard' );
		}

		if ( $this->getRequest()->getPost() ) {

			/* @var [form_data] [Recupero dei dati da un form] */
			$form_data = $this->getRequest()->getPost();

			/**
			 * convalida degli input
			 * controllo credenziali login
			 * redirect /dashboard
			 */
			if ( $this->form->isValid( $form_data ) ) {

				/* @var [db] [Default Connect] */
				$db = Zend_Db_Table::getDefaultAdapter();

				/* @var Zend_Auth_Adapter_DbTable [Database Table Authentication] */
				$adapter = new Zend_Auth_Adapter_DbTable( $db );
				$adapter->setTableName( 'ads_login' );
				$adapter->setIdentityColumn( 'usermail' );
				$adapter->setCredentialColumn( 'pwd' );
				$adapter->setCredentialTreatment( 'SHA1(?) AND status = 1' );

				/* recupero le credenziali di accesso */
				$username = $this->form->getValue( 'usermail' );
				$password = $this->form->getValue( 'pwd' );

				$adapter->setIdentity( $username );
				$adapter->setCredential( $password );

				/* @var [result] [authenticate] */
				$result = $auth->authenticate( $adapter );

				if ( $result->isValid() ) {

					/* isValid login / redirect */
					$user = $adapter->getResultRowObject();
					$auth->getStorage()->write( $user );
					$this->_redirect( '/dashboard' );
				} else {

					/* login NON valido */
					$this->view->loginError = $this->params->label_check_user;
				}

			} else {

				/* ripopolo il form */
				$this->form->populate( $form_data );
			}
		}
	}

	/**
	 * logoutAction
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function logoutAction() {

		/**
		 * elimino i dati in sessione
		 * effettuo il logout dal sistema
		 * eseguo il redirect verso il sito
		 */
		Zend_Auth::getInstance()->clearIdentity();
		$this->_redirect( '/login/notauthorized' );
	}

	/**
	 * notauthorizedAction
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function notauthorizedAction() {
		// action body
	}

	/**
	 * listAction
	 * la lista completa di tutti gli operatori
	 * possono essere modificati / cancellati
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function listAction() {
		$this->view->user = $this->login->fetchAll();
		$this->view->notfound = $this->params->label_not_found;
	}

	/**
	 * addAction
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function addAction() {

		/**
		 * creo il form per aggiungere
		 * un nuovo operatore al sistema
		 */
		$new_login = $this->form->add();
		$new_login->id->setValue( rand( 11111, 99999 ) );

		/**
		 * verifica l'indirizzo email
		 * mostra a video un alert che l'utente
		 * è gia registrato sul database
		 */
		$new_login->usermail->addValidator( new Zend_Validate_Db_NoRecordExists( 'ads_login', 'usermail' ) );

		$this->view->loginForm = $new_login;

		if ( $this->getRequest()->getPost() ) {

			/* @var [form_data] [Recupero dei dati da un form] */
			$form_data = $this->getRequest()->getPost();

			if ( $this->form->isValid( $form_data ) ) {

				/* assegno le variabili  */
				$id = $this->form->getValue( 'id' );
				$name = $this->form->getValue( 'name' );
				$usermail = $this->form->getValue( 'usermail' );
				$pwd = $this->form->getValue( 'pwd' );
				$status = $this->form->getValue( 'status' );

				/* aggiungo i record nel database */
				$this->login->inserNewUser( $id, $name, $usermail, $pwd, $status );
				$this->view->successForm = $this->params->label_success;

			} else {

				/* ripopolo il form */
				$this->form->populate( $form_data );
			}
		}
	}

	/**
	 * editAction
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function editAction() {

		/* @var [id] [recupero l'ID] */
		$id = $this->_getParam( 'id', 0 );
		$new_login = $this->form->add();
		$new_login->pwd->setRequired( false );

		/**
		 * verifico l'indirizzo email
		 * controllo se esiste nel database
		 * escludo il mio indirizzo email registrato in precedenza
		 */
		$new_login->usermail->addValidator( new Zend_Validate_Db_NoRecordExists(
				'ads_login',
				'usermail',
				array(
					'field' => 'id',
					'value' => $id
				) ) );

		/* @var [loginForm] [creo il form htlm] */
		$this->view->loginForm = $new_login;

		if ( $this->getRequest()->getPost() ) {

			/* @var [form_data] [Recupero dei dati da un form] */
			$form_data = $this->getRequest()->getPost();

			if ( $this->form->isValid( $form_data ) ) {

				/* assegno le variabili */
				$name = $this->form->getValue( 'name' );
				$usermail = $this->form->getValue( 'usermail' );
				$pwd = $this->form->getValue( 'pwd' );
				$status = $this->form->getValue( 'status' );

				/* aggiorno i record */
				$this->login->updateUser( $id, $name, $usermail, $pwd, $status );
				$this->view->successForm = $this->params->label_success;
				$this->view->headMeta()->appendHttpEquiv( 'refresh', '3; url=/login/list' );

			} else {

				/* ripopolo il form */
				$this->form->populate( $form_data );
			}

		} else {

			/* ripopolo il form */
			$this->form->populate( $this->login->getLoginInfo( $id ) );
		}
	}

	/**
	 * deleteAction
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function deleteAction() {

		/* @var [id] [recupero l'ID] */
		$id = $this->_getParam( 'id', 0 );

		/* elimino i record dal database */
		$this->login->deleteUser( $id );
		$this->_redirect( '/login/list' );
	}


}
