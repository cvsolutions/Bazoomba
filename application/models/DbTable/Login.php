<?php

/**
* Application_Model_DbTable_Login
*
* @uses     Zend_Db_Table_Abstract
*
* @category Login
* @package  Bazoomba.it
* @author   Concetto Vecchio
* @license  
* @link     
*/
class Application_Model_DbTable_Login extends Zend_Db_Table_Abstract
{

    /**
     * $_name
     *
     * @var string
     *
     * @access protected
     */
	protected $_name = 'ads_login';

    /**
     * $_primary
     *
     * @var string
     *
     * @access protected
     */
	protected $_primary = 'id';

    /**
     * getLoginInfo
     * 
     * @param mixed $id ID Account User.
     *
     * @access public
     *
     * @return mixed Value.
     */
	public function getLoginInfo($id)
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
     * inserNewUser
     * 
     * @param mixed $id       ID Operatore.
     * @param mixed $name     Nome & Cognome.
     * @param mixed $usermail Email.
     * @param mixed $pwd      Password.
     * @param mixed $status   Stato Account.
     *
     * @access public
     *
     * @return mixed Value.
     */
	public function inserNewUser($id, $name, $usermail, $pwd, $status)
	{
		$arrayName = array(
			'id' => $id,
			'name' => $name,
			'usermail' => $usermail,
			'pwd' => sha1($pwd),
			'role' => 'admin',
			'notify' => 1,
			'status' => $status
			);
		return $this->insert($arrayName);
	}

    /**
     * updateUser
     * 
     * @param mixed $id       ID Operatore.
     * @param mixed $name     Nome & Cognome.
     * @param mixed $usermail Email.
     * @param mixed $pwd      Password.
     * @param mixed $status   Stato Account.
     *
     * @access public
     *
     * @return mixed Value.
     */
	public function updateUser($id, $name, $usermail, $pwd, $status)
	{
		$user = $this->getLoginInfo($id);
		$arrayName = array(
			'name' => $name,
			'usermail' => $usermail,
			'pwd' => empty($pwd) ? $user['pwd'] : sha1($pwd),
			'status' => $status
			);
		return $this->update($arrayName, sprintf('id = %d', $id));
	}

    /**
     * deleteUser
     * 
     * @param mixed $id ID User.
     *
     * @access public
     *
     * @return mixed Value.
     */
	public function deleteUser($id)
	{
		$this->delete(sprintf('id = %d', $id));
	}


}

