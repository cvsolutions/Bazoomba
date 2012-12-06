<?php

/**
* Application_Model_DbTable_User
*
* @uses     Zend_Db_Table_Abstract
*
* @category User
* @package  Bazoomba.it
* @author   Concetto Vecchio
* @license
* @link
*/
class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

    /**
     * $_name
     *
     * @var string
     *
     * @access protected
     */
    protected $_name = 'ads_user';

    /**
     * $_primary
     *
     * @var string
     *
     * @access protected
     */
    protected $_primary = 'id';

    /**
     * getAdminInfo
     *
     * @param mixed $id ID Account User.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getAdminInfo($id)
    {
        $row = $this->fetchRow(sprintf("id = %d", $id));
        if(!$row)
        {
            $params = Plugin_Common::getParams();
            throw new Exception($params->label_no_id, 1);
        }
        return $row->toArray();
    }

    /**
     * getEmailInfo
     *
     * @param mixed $email Email / Username.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getEmailInfo($email)
    {
        $row = $this->fetchRow(sprintf("email = '%s' AND status = 1", $email));
        if(!$row)
        {
            $params = Plugin_Common::getParams();
            throw new Exception($params->label_no_id, 1);
        }
        return $row->toArray();
    }

    /**
     * getSerialKeyInfo
     *
     * @param mixed $id        ID Account User.
     * @param mixed $serialkey Serial Validate Account.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getSerialKeyInfo($id, $serialkey)
    {
        $row = $this->fetchRow(sprintf("id = %d AND serialkey = '%s'", $id, $serialkey));
        if(!$row)
        {
            $params = Plugin_Common::getParams();
            throw new Exception($params->label_no_id, 1);
        }
        return $row->toArray();
    }

    /**
     * getInfoSerialKey
     *
     * @param mixed $serialkey Serial Validate Account.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getInfoSerialKey($serialkey)
    {
        $row = $this->fetchRow(sprintf("serialkey = '%s'", $serialkey));
        if(!$row)
        {
            $params = Plugin_Common::getParams();
            throw new Exception($params->label_no_id, 1);
        }
        return $row->toArray();
    }

    /**
     * updatePassword
     *
     * @param mixed $id        ID Account User.
     * @param mixed $serialkey Serial Validate Account.
     * @param mixed $pwd       New Password.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updatePassword($id, $serialkey, $pwd)
    {
        $arrayName = array('pwd' => sha1($pwd));
        return $this->update($arrayName, sprintf("id = %d AND serialkey = '%s'", $id, $serialkey));
    }

    /**
     * updateAdminUser
     *
     * @param mixed $id     ID Account.
     * @param mixed $type   Tipologia di Account.
     * @param mixed $name   Nome & Cognome.
     * @param mixed $status Stato Account.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateAdminUser($id, $type, $name, $status)
    {
        $arrayName = array(
            'type' => $type,
            'name' => $name,
            'status' => $status
            );
        return $this->update($arrayName, sprintf('id = %d', $id));
    }

    public function updateAvatar($id, $avatar)
    {
        $arrayAvatar = array(
            'avatar' => $avatar
            );
        return $this->update($arrayAvatar, sprintf('id = %d', $id));
    }

    /**
     * newUser
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
    public function newUser($type, $name, $email, $phone, $phone_show, $pwd, $serialkey, $iva, $name_company)
    {
        $arrayNewUser = array(
            'type' => $type,
            'name' => $name,
            'email' => $email,
            'telephone' => $phone,
            'phone_show'=> $phone_show,
            'pwd' => sha1($pwd),
            'serialkey' => $serialkey,
            'vat' => $iva,
            'name_company' => $name_company,
            'role' => 'user',
            'status' => 0,
            'newsletter' => 0,
            'registered' => time(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
        );
        return $this->insert($arrayNewUser);
    }

    /**
     * confirmUser
     *
     * @param mixed $serialkey Serial Validate Account.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function confirmUser($serialkey)
    {
        $arrayConfirm = array(
            'status' => 1
            );
        return $this->update($arrayConfirm, sprintf('serialkey = %d', $serialkey));
    }

    /**
     * updateEditUser
     *
     * @param mixed $type   Tipologia di Account.
     * @param mixed $name   Nome & Cognome.
     * @param mixed $email  Email.
     * @param mixed $phone  Telefono.
     * @param mixed $phone_show    Mostra Telefono.
     * @param mixed $iva    P.Iva.
     * @param mixed $name_company    Ragione Sociale.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateEditUser($id, $type, $name, $email, $phone, $phone_show, $iva, $name_company)
    {
        $arrayName = array(
            'type' => $type,
            'name' => $name,
            'email' => $email,
            'telephone' => $phone,
            'phone_show' => $phone_show,
            'vat' => $iva,
            'name_company' => $name_company
            );
        return $this->update($arrayName, sprintf('id = %d', $id));
    }

    /**
     * updateEditUser
     *
     * @param mixed $type   Tipologia di Account.
     * @param mixed $name   Nome & Cognome.
     * @param mixed $email  Email.
     * @param mixed $phone  Telefono.
     * @param mixed $phone_show    Mostra Telefono.
     * @param mixed $iva    P.Iva.
     * @param mixed $name_company    Ragione Sociale.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updatePasswordUser($id, $pwd)
    {
        $arrayName = array(
            'pwd' => sha1($pwd)
            );
        return $this->update($arrayName, sprintf('id = %d', $id));
    }


}

