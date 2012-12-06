<?php

 /**
  * Plugin_Common
  *
  * @uses     Zend_Controller_Plugin_Abstract
  *
  * @category Common
  * @package  Bazoomba
  * @author   Concetto Vecchio
  * @license
  * @link
  */
 class Plugin_Common extends Zend_Controller_Plugin_Abstract {

     /**
      * getParams
      *
      * @access public
      * @static
      *
      * @return mixed Value.
      */
     public static function getParams() {
         $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/common.ini', 'common');
         // $test = $config->params->status->toArray();
         return $config->params;
     }

     /**
      * getMail
      *
      * @param array $params Data Array().
      *
      * @access public
      * @static
      *
      * @return mixed Value.
      */
     public static function getMail($params = array()) {
         $layout = new Zend_Layout(array(
                           'layoutPath' => sprintf('%s/layouts/scripts/', APPLICATION_PATH)
                 ));
         $layout->setLayout('email');

         $view = new Zend_View();
         $view->setScriptPath(sprintf('%s/views/scripts/email/', APPLICATION_PATH));

         $view->assign('params', $params['params']);

         $layout->content = $view->render($params['template']);
         $TPL = $layout->render();

         $mail = new Zend_Mail('utf-8');
         $mail->setBodyHtml($TPL);

         $data = Plugin_Common::getParams();
         $mail->setFrom($data->admin_email, $data->from_email);
         $mail->addTo($params['email']);
         $mail->setReplyTo($params['reply']);
         $mail->setSubject($params['subject']);
         return $mail->send();
     }

     /**
      * getRandomPWD
      *
      * @param array $params Data Array().
      *
      * @access public
      * @static
      *
      * @return mixed Value.
      */
     public static function getRandom($length = 8) {
         $letters = '123456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKMNPQRSTUVWXYZ';
         $string = '';
         $lettersLength = strlen($letters) - 1;
         for ($i = 0; $i < $length; $i++) {
             $string .= strtolower($letters[rand(0, $lettersLength)]);
         }
         return($string);
     }
     
     /**
      * getId
      *
      * @param array $params Data Array().
      *
      * @access public
      * @static
      *
      * @return mixed Value.
      */
     public static function getId($table) {
         $new = mt_rand(10000, 99999);
         $db = Zend_Db_Table::getDefaultAdapter();
         $select = $db->select()
                     ->from(sprintf('%s',$table), array('id'))
                     ->where('id = ?', $new);
         $rows = count($db->fetchAll($select));
         if($rows > 0) {
             $this->getId();
         } else {
             return $new;
         }
     }

 }