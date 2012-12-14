<?php

/**
 * CategoryController
 *
 * @uses     Zend_Controller_Action
 *
 * @category Category
 * @package  Bazoomba.it
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
    public $params;

    /**
     * $category
     *
     * @var mixed
     *
     * @access public
     */
    public $category;

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

        /* @var Application_Model_DbTable_Category [Category] */
        $this->category = new Application_Model_DbTable_Category();

        /* @var Application_Form_Category [Form] */
        $this->form = new Application_Form_Category();
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
     * addAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function addAction() {

        /* preparo il form */
        $this->form->id->setValue( rand( 11111, 99999 ) );
        $this->form->image->addFilter( 'Rename', sprintf( '%s.jpg', uniqid() ) );

        /* @var [categoryForm] [stampo il form a video] */
        $this->view->categoryForm = $this->form;

        if ( $this->getRequest()->getPost() ) {

            /* @var [form_data] [Recupero dei dati da un form] */
            $form_data = $this->getRequest()->getPost();

            if ( $this->form->isValid( $form_data ) ) {

                /* assegno le variabili */
                $id = $this->form->getValue( 'id' );
                $name = $this->form->getValue( 'name' );
                $image = $this->form->getValue( 'image' );
                $parent = $this->form->getValue( 'parent' );

                /* inserimento record nel database */
                $this->category->inserNewCategory( $id, $name, $image, $parent );
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
        $row = $this->category->getCategoryInfo( $id );

        $imageDB = $row['image'] > 0 ? $row['image'] : sprintf( '%s.jpg', uniqid() );
        $this->form->image->addFilter( 'Rename', $imageDB );
        $this->view->categoryForm = $this->form;

        if ( $this->getRequest()->getPost() ) {

            /* @var [form_data] [Recupero dei dati da un form] */
            $form_data = $this->getRequest()->getPost();

            if ( $this->form->isValid( $form_data ) ) {

                /* assegno le variabili */
                $name = $this->form->getValue( 'name' );
                $image = $this->form->getValue( 'image' ) ? $this->form->getValue( 'image' ) : $row['image'];
                $parent = $this->form->getValue( 'parent' );

                /* aggiorno i record */
                $this->category->updateCategory( $id, $name, $image, $parent );
                $this->view->successForm = $this->params->label_success;
                $this->view->headMeta()->appendHttpEquiv( 'refresh', '3; url=/category/list' );

            } else {

                /* ripopolo il form */
                $this->form->populate( $form_data );
            }

        } else {

            /* ripopolo il form */
            $this->form->populate( $row );
        }
    }

    /**
     * listAction
     * recupero la lista di tutte le categorie
     * controllo se sono attribuite le sottocategorie
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function listAction() {
        $this->view->category = $this->category->full_List();
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
    public function deleteAction() {

        /* @var [id] [recupero l'ID] */
        $id = $this->_getParam( 'id', 0 );

        /* elimino i record dal database */
        $this->category->deleteCategory( $id );
        $this->_redirect( '/category/list' );
    }


}
