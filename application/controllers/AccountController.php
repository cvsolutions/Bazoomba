<?php

/**
 * AccountController
 *
 * @uses     Zend_Controller_Action
 *
 * @category Account
 * @package  Bazzomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 *
 *
 */

class AccountController extends Zend_Controller_Action
{

    /**
     * $params
     *
     * @var mixed
     *
     * @access public
     *
     *
     */
    public $params = null;

    /**
     * init
     *
     * @access public
     *
     * @return mixed Value.
     *
     *
     */
    public function init()
    {
        /* Initialize action controller here */
    }

    /**
     * indexAction
     *
     * @access public
     *
     * @return mixed Value.
     *
     *
     */
    public function indexAction()
    {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();
        $this->view->identity = $identity;
    }

    /**
     * editAction
     *
     * @access public
     *
     * @return mixed Value.
     *
     *
     */
    public function editAction()
    {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();
        $this->view->identity = $identity;

        $user = new Application_Model_DbTable_User();
        $row = $user->getAdminInfo($identity->id);

        $form = new Application_Form_User();
        $edit = $form->editUser();
        $edit->email->addValidator(new Zend_Validate_Db_NoRecordExists(
			'ads_user',
			'email',
			array(
                'field' => 'id',
				'value' => $identity->id
				)));

        $this->view->editForm = $edit;


        if($this->getRequest()->getPost())
        {
            $form_data = $this->getRequest()->getPost();
            if($form->isValid($form_data))
            {
                $type = $form->getValue('type');
                $name = $form->getValue('name');
                $email = $form->getValue('email');
                $phone = $form->getValue('telephone');
                $phone_show = $form->getValue('phone_show');
                $iva = $form->getValue('vat');
                $name_company = $form->getValue('name_company');

                $user->updateEditUser($identity->id, $type, $name, $email, $phone, $phone_show, $iva, $name_company);

                Plugin_Common::getMail(array(
                    'email' => $email,
                    'subject' => 'Modifica Dati',
                    'template' => 'edituser.phtml',
                    'params' => array(
                        'name' => $name
                        )
                    ));

                $this->view->successForm = $this->params->label_success;
                $this->view->headMeta()->appendHttpEquiv('refresh','3; url=/account');

            } else {
                $form->populate($form_data);
            }

        } else {
            $form->populate($row);
        }
    }


    /**
     * editpasswordAction
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function editpasswordAction()
    {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();
        $this->view->identity = $identity;

        $form = new Application_Form_User();
        $this->view->editPassword = $form->editPassword();

        if($this->getRequest()->getPost())
        {
            $form_data = $this->getRequest()->getPost();
            if($form->isValid($form_data))
            {
                $pwd = $form->getValue('confirm');
                $user = new Application_Model_DbTable_User();
                $user->updatePasswordUser($identity->id, $pwd);

                Plugin_Common::getMail(array(
                    'email' => $identity->email,
                    'subject' => 'Modifica Dati',
                    'template' => 'editpassword.phtml',
                    'params' => array(
                        'name' => $identity->name,
                        'pwd' => $pwd,
                        'email' => $identity->email
                        )
                    ));

                $this->view->successForm = $this->params->label_success;
                $this->view->headMeta()->appendHttpEquiv('refresh','3; url=/account');

            }
        }

    }




}

