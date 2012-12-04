<?php

/**
* CategoryController
*
* @uses     Zend_Controller_Action
*
* @category Category
* @package  Bazzomba.it
* @author   Concetto Vecchio
* @license  
* @link     
*/
class CategoryController extends Zend_Controller_Action
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
        // action body
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
        $form = new Application_Form_Category();
        $form->id->setValue(rand(11111, 99999));
        $form->image->addFilter('Rename', sprintf('%s.jpg', uniqid()));
        $this->view->categoryForm = $form;

        if($this->getRequest()->getPost())
        {
            $form_data = $this->getRequest()->getPost();
            if($form->isValid($form_data))
            {
                $id = $form->getValue('id');
                $name = $form->getValue('name');
                $image = $form->getValue('image');
                $parent = $form->getValue('parent');

                $category = new Application_Model_DbTable_Category();
                $category->inserNewCategory($id, $name, $image, $parent);
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
        $Category = new Application_Model_DbTable_Category();
        $row = $Category->getCategoryInfo($id);

        $form = new Application_Form_Category();
        $imageDB = $row['image'] > 0 ? $row['image'] : sprintf('%s.jpg', uniqid());
        $form->image->addFilter('Rename', $imageDB);
        $this->view->categoryForm = $form;

        if($this->getRequest()->getPost())
        {
            $form_data = $this->getRequest()->getPost();
            if($form->isValid($form_data))
            {
                $name = $form->getValue('name');
                $image = $form->getValue('image') ? $form->getValue('image') : $row['image'];
                $parent = $form->getValue('parent');

                $Category->updateCategory($id, $name, $image, $parent);
                $this->view->successForm = $this->params->label_success;
                $this->view->headMeta()->appendHttpEquiv('refresh','3; url=/category/list');

            } else {
                $form->populate($form_data);
            }

        } else {
            $form->populate($row);  
        }
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
        $list_cat = new Application_Model_DbTable_Category();
        $this->view->category = $list_cat->full_List();
        $this->view->alert = $this->params->alert->toArray();
        $this->view->notfound = $this->params->label_not_found;
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
        $category = new Application_Model_DbTable_Category();
        $category->deleteCategory($id);
        $this->_redirect('/category/list');
    }


}
