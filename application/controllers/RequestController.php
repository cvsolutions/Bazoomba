<?php

class RequestController extends Zend_Controller_Action
{
    public $params;
    public $token;
    public $email;
    public $id;
    public $Request;

    public function init()
    {
        /* Initialize action controller here */
        $this->params = Plugin_Common::getParams();

        $this->token = $this->_getParam('token', 0);
        $this->email = $this->_getParam('email', 0);
        $this->id = $this->_getParam('id', 0);

        $this->Request = new Application_Model_DbTable_Request();
    }

    public function indexAction()
    {
        // action body
    }

    public function subscribeAction()
    {

        $form = new Application_Form_Request();
        $this->view->SubscribeForm = $form->Subscribe();

        if ($this->getRequest()->getPost()) {

            $form_data = $this->getRequest()->getPost();

            if ($form->isValid($form_data)) {

                $FunctionRequest = new Application_Model_FunctionRequest();

                $name = $form->getValue('name');
                $email = $form->getValue('email');
                $category = $form->getValue('category');
                $region = $form->getValue('region');
                $tags = $FunctionRequest->Create_TagsToString($form->getValue('tags'));

                $this->Request->New_Request($name, $email, $category, $region, $tags);

                $this->view->successForm = $this->params->label_success;
                $this->view->headMeta()->appendHttpEquiv('refresh', '3; url=/');

            } else {

                $form->populate($form_data);
            }
        }
    }

    public function editAction()
    {
        if ($this->id) {
            $row = $this->Request->Info_Request($this->id);
            $status = $row['status'] == 1 ? 0 : 1;
            $this->Request->Update_Status_Request($row['id'], $status);
            $this->redirect(sprintf('request/mysearch/email/%s/token/%s', $this->email, $this->token));
        }
    }

    public function deleteAction()
    {
        if ($this->id) {
            $row = $this->Request->Info_Request($this->id);
            $this->Request->Delete_Request($row['id']);
            $this->redirect('/');
        }
    }

    public function mysearchAction()
    {
        if ($this->token) {

            $row = $this->Request->Check_Token_Request($this->email, $this->token);

            if ($row['token'] == $this->token) {

                $ListRequest = $this->Request->List_Token_Request($this->email);
                $this->view->user = $row;
                $this->view->list = $ListRequest;
                $this->view->notfound = $this->params->label_not_found;
                $this->view->status = $this->params->status->toArray();
                $this->view->btn_class = array(1 => 'success', 0 => 'danger');

            } else {
                $this->redirect('user/notauthorized');
            }

        } else {
            throw new ErrorException($this->params->label_error_token);
        }
    }


}


