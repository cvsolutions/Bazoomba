<?php

/**
* Application_Model_DbTable_Shop
*
* @uses     Zend_Db_Table_Abstract
*
* @category ADS Shop
* @package  Bazoomba.it
* @author   Concetto Vecchio
* @license
* @link
*/
class Application_Model_DbTable_Shop extends Zend_Db_Table_Abstract
{
    /**
     * $_name
     *
     * @var string
     *
     * @access protected
     */
    protected $_name = 'ads_shop';


    /**
     * $_primary
     *
     * @var string
     *
     * @access protected
     */
    protected $_primary = 'id';

    /**
     * getAdminShopInfo
     *
     * @param mixed $id ID ADS Shop.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getAdminShopInfo($id)
    {
        $row = $this->fetchRow(sprintf('id = %d', $id));
        if(!$row)
        {
            $params = Plugin_Common::getParams();
            throw new Exception($params->label_no_id, 1);
        }
        return $row->toArray();
    }

    public function getSiteShopInfo($id)
    {
        $row = $this->fetchRow(sprintf('id = %d AND status = 1', $id));
        if(!$row)
        {
            $params = Plugin_Common::getParams();
            throw new Exception($params->label_no_id, 1);
        }
        return $row->toArray();
    }


    /**
     * fullShop
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function fullShop()
    {
        $query = $this->getDefaultAdapter()->select();
        $query->from('ads_shop', array(
            'id',
            'code',
            'type',
            'title',
            'price',
            'status',
            'registered'
            ));
        $query->join('ads_category', 'ads_shop.category = ads_category.id', array('category' => 'name'));
        $query->join('ads_region', 'ads_shop.region = ads_region.id', array('region' => 'name'));
        $query->join('ads_user', 'ads_shop.user = ads_user.id', array('user' => 'name'));
        // echo $query->assemble();
        return $this->getDefaultAdapter()->fetchAll($query);
    }

    /**
     * LastHomeShop
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function LastHomeShop()
    {
        $query = $this->getDefaultAdapter()->select();
        $query->from('ads_shop', array(
            'id',
            'type',
            'title',
            'description',
            'category',
            'region',
            'price',
            'registered'
            ));
        $query->join('ads_category', 'ads_shop.category = ads_category.id', array('name_category' => 'name'));
        $query->join('ads_region', 'ads_shop.region = ads_region.id', array('name_region' => 'name'));
        $query->join('ads_user', 'ads_shop.user = ads_user.id', array('user' => 'name'));
        $query->joinLeft('ads_gallery', 'ads_shop.id = ads_gallery.shop', array('photo' => 'image'));
        $query->where('ads_shop.status = 1');
        $query->order('registered DESC');
        $query->where('ads_gallery.status = 1');
        $query->group('ads_shop.id');
        $query->limit('0, 10');
        // echo $query->assemble();
        return $this->getDefaultAdapter()->fetchAll($query);
    }

    /**
     * fullShopFilter
     *
     * @param array $params Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function fullShopFilter($params = array())
    {
        $query = $this->getDefaultAdapter()->select();
        $query->from('ads_shop', array(
            'id',
            'type',
            'title',
            'description',
            'price',
            'registered'
            ));
        $query->join('ads_category', 'ads_shop.category = ads_category.id', array('category' => 'name'));
        $query->join('ads_provinces', 'ads_shop.province = ads_provinces.id', array('province' => 'name'));
        $query->join('ads_region', 'ads_shop.region = ads_region.id', array('region' => 'name'));
        $query->join('ads_user', 'ads_shop.user = ads_user.id', array('user' => 'name'));
        $query->joinLeft('ads_gallery', 'ads_shop.id = ads_gallery.shop', array('photo' => 'image'));

        switch ($params['type'])
        {
            case 'label':
            $query->where(sprintf("MATCH(ads_shop.title, ads_shop.description) AGAINST('%s' IN BOOLEAN MODE)", $params['q']));
            break;

            case 'category':
            $query->where(sprintf('ads_shop.category = %d', $params['id']));
            break;

            case 'sub_category':
            $query->where(sprintf('ads_shop.sub_category = %d', $params['id']));
            break;

            case 'region':
            $query->where(sprintf('ads_shop.region = %d', $params['id']));
            break;

            case 'province':
            $query->where(sprintf('ads_shop.province = %d', $params['id']));
            break;
        }

        $query->where('ads_shop.status = 1');
        $query->where('ads_gallery.status = 1');
        $query->group('ads_shop.id');
        $query->order('registered DESC');
        // echo $query->assemble();
        return $this->getDefaultAdapter()->fetchAll($query);
    }

    /**
     * updateShopAdmin
     *
     * @param mixed $id           ID annuncio (ADS).
     * @param mixed $category     Categoria.
     * @param mixed $sub_category Sotto Categoria.
     * @param mixed $region       Regione.
     * @param mixed $province     Provincia.
     * @param mixed $city         Città / Comune.
     * @param mixed $type         Tipo di annuncio.
     * @param mixed $title        Titolo.
     * @param mixed $price        Prezzo.
     * @param mixed $description  Descrizione.
     * @param mixed $status       Stato.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateShopAdmin($id, $category, $sub_category, $region, $province, $city, $type, $title, $price, $description, $status)
    {
        $arrayName = array(
            'category' => $category,
            'sub_category' => $sub_category,
            'region' => $region,
            'province' => $province,
            'city' => $city,
            'type' => $type,
            'title' => $title,
            'price' => $price,
            'description' => $description,
            'status' => $status
            );
        return $this->update($arrayName, sprintf('id = %d', $id));
    }


    public function updateStep($id, $status)
    {
        $arrayName = array(
            'step' => $status
            );
        return $this->update($arrayName, sprintf('id = %d', $id));
    }

    /**
     * newShop
     *
     * @param mixed $type   Tipologia di Account.
     * @param mixed $name   Nome & Cognome.
     * @param mixed $email  Email.
     * @param mixed $phone  Telefono.
     * @param mixed $pwd    Password.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function newShop($id, $category, $sub_category, $region, $province, $city, $type, $title, $description, $price, $latitude, $longitude)
    {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();

        $arrayNewShop = array(
            'id' => $id,
            'user' => $identity->id,
            'code' => strtoupper(Plugin_Common::getRandom(6)),
            'category' => $category,
            'sub_category' => $sub_category,
            'region' => $region,
            'province' => $province,
            'city'=> $city,
            'type'=> $type,
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'registered' => time(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'status' => 0,
            'terms' => 1,
            'step' => 1
        );
        return $this->insert($arrayNewShop);
    }

    public function controlAds($id_ads)
    {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();

        $query = $this->getDefaultAdapter()->select();
        $query->from('ads_shop', array(
            'id',
            'user'
            ));
        $query->where('user = ?', $identity->id);
        $query->where('id = ?', $id_ads);
        $query->where('status = 0');
        return $this->getDefaultAdapter()->fetchAll($query);
    }


}

