<?php
/**
 * /tmp/phptidy-sublime-buffer.php
 *
 * @package default
 */


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
     *
     * @access public
     *
     * @param mixed   $id ID Account User.
     * @return mixed Value.
     */
    public function getAdminInfo($id) {
        $row = $this->fetchRow(sprintf("id = %d", $id));
        if (!$row) {
            $params = Plugin_Common::getParams();
            throw new Exception($params->label_no_id, 1);
        }
        return $row->toArray();
    }


    /**
     * getEmailInfo
     *
     *
     * @access public
     *
     * @param mixed   $email Email / Username.
     * @return mixed Value.
     */
    public function getEmailInfo($email) {
        $row = $this->fetchRow(sprintf("email = '%s' AND status = 1", $email));
        if (!$row) {
            $params = Plugin_Common::getParams();
            throw new Exception($params->label_no_id, 1);
        }
        return $row->toArray();
    }


    /**
     * getSerialKeyInfo
     *
     *
     * @access public
     *
     * @param mixed   $id        ID Account User.
     * @param mixed   $serialkey Serial Validate Account.
     * @return mixed Value.
     */
    public function getSerialKeyInfo($id, $serialkey) {
        $row = $this->fetchRow(sprintf("id = %d AND serialkey = '%s'", $id, $serialkey));
        if (!$row) {
            $params = Plugin_Common::getParams();
            throw new Exception($params->label_no_id, 1);
        }
        return $row->toArray();
    }


    /**
     * getInfoSerialKey
     *
     *
     * @access public
     *
     * @param mixed   $serialkey Serial Validate Account.
     * @return mixed Value.
     */
    public function getInfoSerialKey($serialkey) {
        $row = $this->fetchRow(sprintf("serialkey = '%s'", $serialkey));
        if (!$row) {
            $params = Plugin_Common::getParams();
            throw new Exception($params->label_no_id, 1);
        }
        return $row->toArray();
    }


    /**
     * updatePassword
     *
     *
     * @access public
     *
     * @param mixed   $id        ID Account User.
     * @param mixed   $serialkey Serial Validate Account.
     * @param mixed   $pwd       New Password.
     * @return mixed Value.
     */
    public function updatePassword($id, $serialkey, $pwd) {
        $arrayName = array(
            'pwd' => sha1($pwd),
            'modified' => time(),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update($arrayName, sprintf("id = %d AND serialkey = '%s'", $id, $serialkey));
    }


    /**
     * updateAdminUser
     *
     *
     * @access public
     *
     * @param mixed   $id     ID Account.
     * @param mixed   $type   Tipologia di Account.
     * @param mixed   $name   Nome & Cognome.
     * @param mixed   $status Stato Account.
     * @return mixed Value.
     */
    public function updateAdminUser($id, $type, $name, $status) {
        $arrayName = array(
            'type' => $type,
            'name' => $name,
            'status' => $status,
            'modified' => time(),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update($arrayName, sprintf('id = %d', $id));
    }


    /**
     *
     *
     * @param unknown $id
     * @param unknown $avatar
     * @return unknown
     */
    public function updateAvatar($id, $avatar) {
        $arrayAvatar = array(
            'avatar' => $avatar
        );
        return $this->update($arrayAvatar, sprintf('id = %d', $id));
    }


    /**
     * newUser
     *
     *
     * @access public
     *
     * @param mixed   $type         Tipologia di Account.
     * @param mixed   $name         Nome & Cognome.
     * @param mixed   $email        Email.
     * @param mixed   $phone        Telefono.
     * @param unknown $phone_show
     * @param mixed   $pwd          Password.
     * @param unknown $serialkey
     * @param unknown $iva
     * @param unknown $name_company
     * @return mixed Value.
     */
    public function newUser($type, $name, $email, $phone, $phone_show, $pwd, $serialkey, $iva, $name_company) {
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
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->insert($arrayNewUser);
    }


    /**
     * confirmUser
     *
     *
     * @access public
     *
     * @param mixed   $serialkey Serial Validate Account.
     * @return mixed Value.
     */
    public function confirmUser($serialkey) {
        $arrayConfirm = array(
            'status' => 1,
            'modified' => time(),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update($arrayConfirm, sprintf('serialkey = %d', $serialkey));
    }


    /**
     * updateEditUser
     *
     *
     * @access public
     *
     * @param unknown $id
     * @param mixed   $type         Tipologia di Account.
     * @param mixed   $name         Nome & Cognome.
     * @param mixed   $email        Email.
     * @param mixed   $phone        Telefono.
     * @param mixed   $phone_show   Mostra Telefono.
     * @param mixed   $iva          P.Iva.
     * @param mixed   $name_company Ragione Sociale.
     * @return mixed Value.
     */
    public function updateEditUser($id, $type, $name, $email, $phone, $phone_show, $iva, $name_company) {
        $arrayName = array(
            'type' => $type,
            'name' => $name,
            'email' => $email,
            'telephone' => $phone,
            'phone_show' => $phone_show,
            'vat' => $iva,
            'name_company' => $name_company,
            'modified' => time(),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update($arrayName, sprintf('id = %d', $id));
    }


    /**
     * updateEditUser
     *
     *
     * @access public
     *
     * @param unknown $id
     * @param unknown $pwd
     * @return mixed Value.
     */
    public function updatePasswordUser($id, $pwd) {
        $arrayName = array(
            'pwd' => sha1($pwd),
            'modified' => time(),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update($arrayName, sprintf('id = %d', $id));
    }


    /**
     *
     *
     * @param unknown $date
     * @param unknown $id
     * @return unknown
     */
    public function updateAccessUser($date, $id) {
        $arrayName = array(
            'last_login' => $date,
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update($arrayName, sprintf('id = %d', $id));
    }



}
