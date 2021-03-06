<?php

/**
 * UserController
 *
 * @uses     Zend_Controller_Action
 *
 * @category User
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class UserController extends Zend_Controller_Action
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

        Plugin_Common::Chech_Off_Line();

        $form = new Application_Form_User();
        $this->view->loginForm = $form->login();

        $auth = Zend_Auth::getInstance();
        if ( $auth->hasIdentity() ) {
            $this->_redirect( '/account' );
        }

        if ( $this->getRequest()->getPost() ) {
            $form_data = $this->getRequest()->getPost();

            if ( $form->isValid( $form_data ) ) {
                $db = Zend_Db_Table::getDefaultAdapter();
                $adapter = new Zend_Auth_Adapter_DbTable( $db );
                $adapter->setTableName( 'ads_user' );
                $adapter->setIdentityColumn( 'email' );
                $adapter->setCredentialColumn( 'pwd' );
                $adapter->setCredentialTreatment( 'SHA1(?) AND status = 1' );

                $username = $form->getValue( 'user' );
                $password = $form->getValue( 'pwd' );

                $adapter->setIdentity( $username );
                $adapter->setCredential( $password );

                $result = $auth->authenticate( $adapter );

                if ( $result->isValid() ) {
                    
                    $user = $adapter->getResultRowObject();
                    $auth->getStorage()->write( $user );

                    $User = new Application_Model_DbTable_User();
                    $row = $User->getEmailInfo( $username );
                    $User->updateAccessUser( new Zend_Db_Expr( 'NOW()' ), $user->id );

                    $Access = new Zend_Session_Namespace( 'LastLogin' );
                    $Access->yourLoginTime = $row['last_login'];

                    $this->_redirect( '/account' );
                } else {
                    $this->view->loginError = $this->params->label_check_user;
                }

            } else {
                $form->populate( $form_data );
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
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect( '/user/notauthorized' );
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
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function listAction() {
        $list_user = new Application_Model_DbTable_User();
        $this->view->user = $list_user->fetchAll( null, array( 'registered DESC' ) );
        $this->view->notfound = $this->params->label_not_found;
        $this->view->type_user = $this->params->type_user->toArray();
    }

    /**
     * deleteAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function deleteAction() {
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
        $id = $this->_getParam( 'id', 0 );
        $user = new Application_Model_DbTable_User();
        $row = $user->getAdminInfo( $id );

        $form = new Application_Form_User();
        $this->view->editForm = $form->editAdmin();

        if ( $this->getRequest()->getPost() ) {
            $form_data = $this->getRequest()->getPost();
            if ( $form->isValid( $form_data ) ) {
                $type = $form->getValue( 'type' );
                $name = $form->getValue( 'name' );
                $status = $form->getValue( 'status' );

                $user->updateAdminUser( $id, $type, $name, $status );
                $this->view->successForm = $this->params->label_success;
                $this->view->headMeta()->appendHttpEquiv( 'refresh', '3; url=/user/list' );

            } else {
                $form->populate( $form_data );
            }

        } else {
            $form->populate( $row );
        }
    }

    /**
     * lostpasswordAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function lostpasswordAction() {

        Plugin_Common::Chech_Off_Line();

        $form = new Application_Form_User();
        $this->view->passwordForm = $form->lostPassword();

        if ( $this->getRequest()->getPost() ) {
            $form_data = $this->getRequest()->getPost();
            if ( $form->isValid( $form_data ) ) {
                $email = $form->getValue( 'user' );

                $user = new Application_Model_DbTable_User();
                $row = $user->getEmailInfo( $email );

                Plugin_Common::getMail( array(
                        'email' => $row['email'],
                        'subject' => 'Richiesta reset password',
                        'template' => 'lostpassword.phtml',
                        'params' => array(
                            'id' => $row['id'],
                            'name' => $row['name'],
                            'serialkey' => $row['serialkey']
                        )
                    ) );

                $this->view->successForm = $this->params->label_change_password;
                $this->view->headMeta()->appendHttpEquiv( 'refresh', '3; url=/' );
                // sleep(3);
                // $this->_redirect('/');
                // $this->_helper->layout()->disableLayout();
                // $this->_helper->viewRenderer->setNoRender(true);

            } else {
                $form->populate( $form_data );
            }
        }
    }

    /**
     * resetpasswordAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function resetpasswordAction() {
        $id = $this->_getParam( 'id', 0 );
        $serialkey = $this->_getParam( 'serialkey', 0 );

        $user = new Application_Model_DbTable_User();
        $row = $user->getSerialKeyInfo( $id, $serialkey );

        $form = new Application_Form_User();
        $this->view->passwordForm = $form->resetPassword();

        if ( $this->getRequest()->getPost() ) {
            $form_data = $this->getRequest()->getPost();
            if ( $form->isValid( $form_data ) ) {
                $pwd = $form->getValue( 'pwd' );

                Plugin_Common::getMail( array(
                        'email' => $row['email'],
                        'subject' => 'Nuove Credenziali portale',
                        'template' => 'resetpassword.phtml',
                        'params' => array(
                            'name' => $row['name'],
                            'email' => $row['email'],
                            'pwd' => $pwd
                        )
                    ) );

                $user->updatePassword( $id, $serialkey, $pwd );
                $this->view->successForm = $this->params->label_success;
                $this->view->headMeta()->appendHttpEquiv( 'refresh', '3; url=/' );

            } else {
                $form->populate( $form_data );
            }
        }
    }

    /**
     * newAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function newAction() {

        Plugin_Common::Chech_Off_Line();

        $auth = Zend_Auth::getInstance();
        if ( $auth->hasIdentity() ) {
            $this->_redirect( '/account' );
        }

        $form = new Application_Form_User();
        $this->view->newUser = $form->newUser();
    }

    /**
     * confirmAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function confirmAction() {
        $serialkey = $this->_getParam( 'serialkey' );
        $user = new Application_Model_DbTable_User();

        if ( $user->confirmUser( $serialkey ) ) {

            $user = new Application_Model_DbTable_User();
            $row = $user->getInfoSerialKey( $serialkey );

            Plugin_Common::getMail( array(
                    'email' => $row['email'],
                    'reply' => $this->params->noreplay,
                    'subject' => 'Registrazione Confermata',
                    'template' => 'confirmuser.phtml',
                    'params' => array(
                        'name' => $row['name'],
                    )
                ) );

            $this->view->successForm = $this->params->label_registration_complete;
            $this->view->headMeta()->appendHttpEquiv( 'refresh', '3; url=/user' );
        }
    }

}
