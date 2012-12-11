<?php

/**
 * AjaxController
 *
 * @uses     Zend_Controller_Action
 *
 * @category Ajax
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class AjaxController extends Zend_Controller_Action
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
     * preDispatch
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function preDispatch() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );
    }

    /**
     * init
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function init() {
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
     * newshopAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function newshopAction() {
        if ( $this->getRequest()->getPost() ) {
            $form = new Application_Form_Shop();
            $form_data = $this->getRequest()->getPost();

            if ( $form->isValid( $form_data ) ) {

                $id = Plugin_Common::getId( 'ads_shop' );
                $category = $this->_request->getPost( 'category' );
                $sub_category = $this->_request->getPost( 'sub_category' );
                $region = $this->_request->getPost( 'region' );
                $province = $this->_request->getPost( 'province' );
                $city = $this->_request->getPost( 'city' );
                $type = $this->_request->getPost( 'type' );
                $title = $this->_request->getPost( 'title' );
                $description = $this->_request->getPost( 'description' );
                $tags = $this->_request->getPost( 'tags' );
                $price = $this->_request->getPost( 'price' );
                $latitude = $this->_request->getPost( 'latitude' );
                $longitude = $this->_request->getPost( 'longitude' );
                $url = $this->_request->getPost( 'url_video' );
                $vid = $this->_request->getPost( 'video' );

                $video = new Application_Model_DbTable_Video();
                $video->newVideo( $id, $url, $vid );

                $shop = new Application_Model_DbTable_Shop();
                $shop->newShop( $id, $category, $sub_category, $region, $province, $city, $type, $title, $description, $tags, $price, $latitude, $longitude );

                echo Zend_Json::encode( array( 'id' => $id ) );
            }
        }
    }

    /**
     * newuserAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function newuserAction() {

        if ( $this->getRequest()->getPost() ) {
            $form = new Application_Form_Shop();
            $form_data = $this->getRequest()->getPost();
            if ( $form->isValid( $form_data ) ) {

                $id = Plugin_Common::getId( 'ads_user' );
                $type = $this->_request->getPost( 'type' );
                $name = $this->_request->getPost( 'name' );
                $email = $this->_request->getPost( 'email' );
                $phone = $this->_request->getPost( 'telephone' );
                $phone_show = $this->_request->getPost( 'phone_show' );
                $pwd = $this->_request->getPost( 'pwd' );
                $serialkey = sha1( time().$email.$id );
                $vat = $this->_request->getPost( 'vat' );
                $name_company = $this->_request->getPost( 'name_company' );

                $user = new Application_Model_DbTable_User();
                $user->newUser( $id, $type, $name, $email, $phone, $phone_show, $pwd, $serialkey, $vat, $name_company );

                Plugin_Common::getMail( array(
                        'email' => $email,
                        'reply' => $this->params->noreplay,
                        'subject' => 'Nuova Registrazione',
                        'template' => 'newuser.phtml',
                        'params' => array(
                            'name' => $name,
                            'serialkey' => $serialkey
                        )
                    ) );
                echo Zend_Json::encode( array( 'result' => $this->params->label_confirm_registration; ) );
            }
        }
    }

    /**
     * controlemailAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function controlemailAction() {
        $email = $this->_request->getPost( 'email' );
        $user = new Application_Model_DbTable_User();
        if ( $user->controlemail( $email ) > 0 ) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    /**
     * provinceAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function provinceAction() {
        $select = new Application_Model_OptionSelect();
        $province = '';
        foreach ( $select->appendProvinces( $_REQUEST['id_reg'] ) as $key => $value ) {
            $province .= sprintf( '<option value="%d">%s</option>', $key, $value );
        }
        echo $province;
    }

    /**
     * cityAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function cityAction() {
        $select = new Application_Model_OptionSelect();
        $city = '';
        foreach ( $select->appendCity( 0, $_REQUEST['id_pro'] ) as $key => $value ) {
            $city .= sprintf( '<option value="%d">%s</option>', $key, $value );
        }
        echo $city;
    }

    /**
     * subcategoryAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function subcategoryAction() {
        $select = new Application_Model_OptionSelect();
        $subcategory = '';
        foreach ( $select->appendSubCategory( $_REQUEST['id_cat'] ) as $key => $value ) {
            $subcategory .= sprintf( '<option value="%d">%s</option>', $key, $value );
        }
        echo $subcategory;
    }

    /**
     * autocompleteAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function autocompleteAction() {
        $term = $this->_getParam( 'term', 0 );
        $Shop = new Application_Model_DbTable_Shop();
        $ads = $Shop->fullShopFilter( array( 'type' => 'global', 'q' => $term ) );
        $data = array();
        foreach ( $ads as $row ) {
            $result = array(
                'id' => $row['id'],
                'label' => $row['title'],
                'type' => 'autocomplete',
            );
            array_push( $data, $result );
        }

        echo Zend_Json::encode( $data );
        $this->_helper->viewRenderer->setNoRender( true );
    }


}
