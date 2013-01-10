<?php

/**
 * IndexController
 *
 * @uses     Zend_Controller_Action
 *
 * @category Index
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class IndexController extends Zend_Controller_Action
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
     * $setting
     *
     * @var mixed
     *
     * @access public
     */
    public $setting;

    /**
     * $info
     *
     * @var mixed
     *
     * @access public
     */
    public $info;

    /**
     * init
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function init()
    {

        /* Initialize action controller here */
        $this->params = Plugin_Common::getParams();

        /* @var Application_Model_DbTable_Setting [Setting] */
        $this->setting = new Application_Model_DbTable_Setting();

        /* @var [info] [array con tutte le informazioni della configurazione] */
        $this->info = $this->setting->getSettings();

        /* @var [view] [assign data] */
        $this->view->setting = $this->info;

        /* @var [select] [array con tutte le regioni] */
        $region = new Application_Model_OptionSelect();

        /* @var [view] [assign region] */
        $this->view->region_select = $region->appendRegion();

        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();
        $this->view->identity = $identity;
    }

    private function _IPGeolocationService()
    {

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

        /* Check if your website is down */
        Plugin_Common::Chech_Off_Line();
        $latitude = '';
        $longitude = '';

        if (!isset($_SESSION['GeoLocationMaps'])) {
            $GeoLocation = new Plugin_GeoLocationMaps();
            $geoData = $GeoLocation->Region_Code();
            $geoMaps = new Zend_Session_Namespace('GeoLocationMaps');
            $geoMaps->region = $geoData['region'];
        }

        $Region = new Application_Model_DbTable_Region();
        $info_region = $Region->Region_GeoCode($_SESSION['GeoLocationMaps']['region']);
        $latitude = $info_region['latitude'];
        $longitude = $info_region['longitude'];

        /* @var [view] [assign data] */
        $this->view->type_ads = $this->params->type_ads->toArray();
        $this->view->type_user = $this->params->type_user->toArray();
        $this->view->latitude = $latitude;
        $this->view->longitude = $longitude;
    }

    /**
     * offlineAction
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function offlineAction()
    {

        /* disable Layout */
        $this->_helper->layout()->disableLayout();

        if ($this->info['off_line'] == 0) {
            /* redirect */
            $this->redirect('/');
        }
        $this->view->shop = $last_Ads->LastHomeShop();
        $select = new Application_Model_OptionSelect();
        $this->view->region = $select->appendRegion();

        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();
        $this->view->identity = $identity;
    }


}
