<?php

/**
 * Application_Model_OptionSelect
 *
 * @uses
 *
 * @category HTML Select
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class Application_Model_OptionSelect {

	/**
	 * appendStatus
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function appendStatus() {
		$params = Plugin_Common::getParams();
		return $params->status->toArray();
	}

	/**
	 * appendTypeUser
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function appendTypeUser() {
		$params = Plugin_Common::getParams();
		return $params->type_user->toArray();
	}

	/**
	 * appendTypeAds
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function appendTypeAds() {
		$params = Plugin_Common::getParams();
		return $params->type_ads->toArray();
	}

	/**
	 * appendParentCategory
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function appendParentCategory() {
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select();
		$select->from( 'ads_category', array( 'id', 'name' ) );
		$select->where( 'parent = 0' );
		$select->order( 'name ASC' );
		$result = $db->fetchAll( $select );
		$arrayName[0] = '-';
		foreach ( $result as $row ) {
			$arrayName[$row['id']] = $row['name'];
		}
		return $arrayName;
	}

	/**
	 * appendSubCategory
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function appendSubCategory( $category = null ) {
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select();
		$select->from( 'ads_category', array( 'id', 'name' ) );
		$select->order( 'name ASC' );
		if ( isset( $category ) ) {
			$select->where( sprintf( 'parent = %d', $category ) );
		} else {
			$select->where( 'parent != 0' );
		}
		$result = $db->fetchAll( $select );
		$arrayName[0] = '-';
		foreach ( $result as $row ) {
			$arrayName[$row['id']] = $row['name'];
		}
		return $arrayName;
	}

	/**
	 * appendRegion
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function appendRegion() {
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select();
		$select->from( 'ads_region', array( 'id', 'name' ) );
		$result = $db->fetchAll( $select );
		$arrayName[0] = '-';
		foreach ( $result as $row ) {
			$arrayName[$row['id']] = $row['name'];
		}
		return $arrayName;
	}

	/**
	 * appendProvinces
	 *
	 * @param mixed   $region Description.
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function appendProvinces( $region = null ) {
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select();
		$select->from( 'ads_provinces', array( 'id', 'name' ) );
		if ( $region ) $select->where( 'region = ?', $region );
		$result = $db->fetchAll( $select );
		$arrayName[0] = '-';
		foreach ( $result as $row ) {
			$arrayName[$row['id']] = $row['name'];
		}
		return $arrayName;
	}

	/**
	 * appendCity
	 *
	 * @param mixed   $region    Description.
	 * @param mixed   $provinces Description.
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function appendCity( $region = null, $provinces = null ) {
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select();
		$select->from( 'ads_city', array( 'id', 'name' ) );
		if ( $region ) $select->where( 'region = ?', $region );
		if ( $provinces ) $select->where( 'provinces = ?', $provinces );
		$result = $db->fetchAll( $select );
		$arrayName[0] = '-';
		foreach ( $result as $row ) {
			$arrayName[$row['id']] = $row['name'];
		}
		return $arrayName;
	}

}
