<?php

class RequestController extends Zend_Controller_Action
{
    public $params;

    public function init() {

        /* Initialize action controller here */
        $this->params = Plugin_Common::getParams();
    }

    public function indexAction() {
        // action body
    }

    public function subscribeAction() {

        $form = new Application_Form_Request();
        $this->view->SubscribeForm = $form->Subscribe();

        if ( $this->getRequest()->getPost() ) {

            /* @var [form_data] [Recupero dei dati da un form] */
            $form_data = $this->getRequest()->getPost();

            if ( $form->isValid( $form_data ) ) {

                $FunctionRequest = new Application_Model_FunctionRequest();

                /* assegno le variabili */
                $name = $form->getValue( 'name' );
                $email = $form->getValue( 'email' );
                $category = $form->getValue( 'category' );
                $region = $form->getValue( 'region' );
                $tags = $FunctionRequest->Create_TagsToString( $form->getValue( 'tags' ) );

                /* inserimento record nel database */
                $Request = new Application_Model_DbTable_Request();
                $Request->New_Request( $name, $email, $category, $region, $tags );

                $this->view->successForm = $this->params->label_success;
                $this->view->headMeta()->appendHttpEquiv( 'refresh', '3; url=/' );

            } else {

                /* ripopolo il form */
                $form->populate( $form_data );
            }
        }
    }

    public function editAction() {
        // action body
    }

    public function deleteAction() {
        // action body
    }


}
