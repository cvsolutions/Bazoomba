<?php

/**
* LoginController
*
* @uses     Zend_Controller_Action
*
* @category Login
* @package  Bazzomba.it
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
	public $params = null;

    /**
     * init
     * 
     * @access public
     *
     * @return mixed Value.
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
     */
	public function indexAction()
	{
		$form = new Application_Form_Login();
		$this->view->loginForm = $form->login();

		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity())
		{
			$this->_redirect('/dashboard');
		}

		if ($this->getRequest()->getPost())
		{
			$form_data = $this->getRequest()->getPost();

			if($form->isValid($form_data))
			{
				$db = Zend_Db_Table::getDefaultAdapter();
				$adapter = new Zend_Auth_Adapter_DbTable($db);
				$adapter->setTableName('ads_login');
				$adapter->setIdentityColumn('usermail');
				$adapter->setCredentialColumn('pwd');
				$adapter->setCredentialTreatment('SHA1(?) AND status = 1');

				$username = $form->getValue('usermail');
				$password = $form->getValue('pwd');

				$adapter->setIdentity($username);
				$adapter->setCredential($password);

				$result = $auth->authenticate($adapter);

				if ($result->isValid())
				{
					$user = $adapter->getResultRowObject();
					$auth->getStorage()->write($user);
					$this->_redirect('/dashboard');
				} else {
					$this->view->loginError = $this->params->label_check_user;
				}

			} else {
				$form->populate($form_data);
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
	public function logoutAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		$this->_redirect('/login/notauthorized');
	}

    /**
     * notauthorizedAction
     * 
     * @access public
     *
     * @return mixed Value.
     */
	public function notauthorizedAction()
	{
        // action body
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
		$list_user = new Application_Model_DbTable_Login();
		$this->view->user = $list_user->fetchAll();
		$this->view->notfound = $this->params->label_not_found;
	}

    /**
     * addAction
     * 
     * @access public
     *
     * @return mixed Value.
     */
	public function addAction()
	{
		$form = new Application_Form_Login();
		$new_login = $form->add();
		$new_login->id->setValue(rand(11111, 99999));
		$new_login->usermail->addValidator(new Zend_Validate_Db_NoRecordExists('ads_login','usermail'));
		$this->view->loginForm = $new_login;

		if($this->getRequest()->getPost())
		{
			$form_data = $this->getRequest()->getPost();
			if($form->isValid($form_data))
			{
				$id = $form->getValue('id');
				$name = $form->getValue('name');
				$usermail = $form->getValue('usermail');
				$pwd = $form->getValue('pwd');
				$status = $form->getValue('status');

				$user = new Application_Model_DbTable_Login();
				$user->inserNewUser($id, $name, $usermail, $pwd, $status);
				$this->view->successForm = $this->params->label_success;

			} else {
				$form->populate($form_data);
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
	public function editAction()
	{
		$id = $this->_getParam('id', 0);
		$user = new Application_Model_DbTable_Login();

		$form = new Application_Form_Login();
		$new_login = $form->add();
		$new_login->pwd->setRequired(false);
		$new_login->usermail->addValidator(new Zend_Validate_Db_NoRecordExists(
			'ads_login',
			'usermail', 
			array(
				'field' => 'id',
				'value' => $id
				)));

		$this->view->loginForm = $new_login;

		if($this->getRequest()->getPost())
		{
			$form_data = $this->getRequest()->getPost();
			if($form->isValid($form_data))
			{
				$name = $form->getValue('name');
				$usermail = $form->getValue('usermail');
				$pwd = $form->getValue('pwd');
				$status = $form->getValue('status');

				$user->updateUser($id, $name, $usermail, $pwd, $status);
				$this->view->successForm = $this->params->label_success;
				$this->view->headMeta()->appendHttpEquiv('refresh','3; url=/login/list');
			} else {
				$form->populate($form_data);
			}

		} else {
			$form->populate($user->getLoginInfo($id));	
		}
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
        $id = $this->_getParam('id', 0);
        $user = new Application_Model_DbTable_Login();
        $user->deleteUser($id);
        $this->_redirect('/login/list');
	}


}
