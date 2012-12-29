<?php

/**
* Application_Model_DbTable_Request
*
* @uses     Zend_Db_Table_Abstract
*
* @category Request
* @package  Bazoomba.it
* @author   Concetto Vecchio
* @license  
* @link     
*/
class Application_Model_DbTable_Request extends Zend_Db_Table_Abstract
{

    /**
     * $_name
     *
     * @var string
     *
     * @access protected
     */
	protected $_name = 'ads_request';

    /**
     * $_primary
     *
     * @var string
     *
     * @access protected
     */
	protected $_primary = 'id';

    /**
     * New_Request
     * Inserimento nuova richiesta
     * 
     * @param mixed $name     Nome & Cognome.
     * @param mixed $email    Indirizzo Email.
     * @param mixed $category Categoria.
     * @param mixed $region   Regione.
     * @param mixed $tags     Parole chiavi.
     *
     * @access public
     *
     * @return mixed Value.
     */
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

    /**
     * Delete_Request_Suspended
     * Elimino tutte le richieste NON più attive
     * 
     * @access public
     *
     * @return mixed Value.
     */
	 public function Delete_Request_Suspended() {
        return $this->delete( 'status = 0' );
    }


}
