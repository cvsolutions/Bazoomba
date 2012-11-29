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
		$query->joinLeft('ads_category', 'ads_shop.category = ads_category.id', array(
			'category' => 'name'
			));
		$query->joinLeft('ads_region', 'ads_shop.region = ads_region.id', array(
			'region' => 'name'
			));
		$query->joinLeft('ads_user', 'ads_shop.user = ads_user.id', array(
			'user' => 'name'
			));
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
            'price',
            'status',
            'registered'
            ));
        $query->joinLeft('ads_category', 'ads_shop.category = ads_category.id', array(
            'category' => 'name'
            ));
        $query->joinLeft('ads_region', 'ads_shop.region = ads_region.id', array(
            'region' => 'name'
            ));
        $query->joinLeft('ads_user', 'ads_shop.user = ads_user.id', array(
            'user' => 'name'
            ));
        $query->where('ads_shop.status = 1');
        // $query->where('registered');
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
    public function newShop($category, $sub_category, $region, $province, $type, $title, $description, $price, $latitude, $longitude)
    {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();

        $arrayNewShop = array(
            'user' => $identity->id,
            'code' => strtoupper(Plugin_Common::getRandom(6)),
            'category' => $category,
            'sub_category' => $sub_category,
            'region' => $region,
            'province' => $province,
            //'city'=> $city,
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'registered' => time(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'status' => 0,
        );
        return $this->insert($arrayNewShop);
    }


}

