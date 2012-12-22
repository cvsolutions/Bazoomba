<?php

class Application_Model_DbTable_Request extends Zend_Db_Table_Abstract
{

	protected $_name = 'ads_request';
	protected $_primary = 'id';

	public function New_Request( $name, $email, $category, $region, $tags ) {
		$arrayNewUser = array(
			'id' => rand( 11111, 99999 ),
			'token' => md5( uniqid( rand(), true ) ),
			'name' => $name,
			'email' => $email,
			'category' => $category,
			'region' => $region,
			'tags' => $tags,
			'registered' => new Zend_Db_Expr( 'NOW()' ),
			'status' => 1,
			'ip_address' => $_SERVER['REMOTE_ADDR']
		);
		return $this->insert( $arrayNewUser );
	}


}
