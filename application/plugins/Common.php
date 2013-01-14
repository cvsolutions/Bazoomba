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
     * Recupero le informazioni del file di configurazione .ini
     *
     * @access public
     * @static
     *
     * @return mixed Value.
     */
    public static function getParams() {
        $config = new Zend_Config_Ini( APPLICATION_PATH . '/configs/common.ini', 'common' );
        // $test = $config->params->status->toArray();
        return $config->params;
    }

    /**
     * getMail
     * Preparo la funzione per inviare le email
     * dal sistema
     *
     * @param array   $params Data Array().
     *
     * @access public
     * @static
     *
     * @return mixed Value.
     */
    public static function getMail( $params = array() ) {
        $layout = new Zend_Layout( array(
                'layoutPath' => sprintf( '%s/layouts/scripts/', APPLICATION_PATH )
            ) );
        $layout->setLayout( 'email' );

        $view = new Zend_View();
        $view->setScriptPath( sprintf( '%s/views/scripts/email/', APPLICATION_PATH ) );

        $view->assign( 'params', $params['params'] );

        $layout->content = $view->render( $params['template'] );
        $TPL = $layout->render();

        $mail = new Zend_Mail( 'utf-8' );
        $mail->setBodyHtml( $TPL );

        $data = Plugin_Common::getParams();
        $mail->setFrom( $data->admin_email, $data->from_email );
        $mail->addTo( $params['email'] );
        $mail->setReplyTo( $params['reply'] );
        $mail->setSubject( $params['subject'] );
        return $mail->send();
    }

    /**
     * getRandom
     * Genero una string in modo casuale
     *
     * @param int     $length Lunghezza della stringa.
     *
     * @access public
     * @static
     *
     * @return mixed Value.
     */
    public static function getRandom( $length = 8 ) {
        $letters = '123456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKMNPQRSTUVWXYZ';
        $string = '';
        $lettersLength = strlen( $letters ) - 1;
        for ( $i = 0; $i < $length; $i++ ) {
            $string .= strtolower( $letters[rand( 0, $lettersLength )] );
        }
        return $string;
    }


    /**
     * getId
     * Creo un ID univoco
     * per ogni inserimento di record nel database
     *
     * @param mixed   $table Tabella del databse.
     *
     * @access public
     * @static
     *
     * @return mixed Value.
     */
    public static function getId( $table ) {
        $new = mt_rand( 10000, 99999 );
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
        ->from( sprintf( '%s', $table ), array( 'id' ) )
        ->where( 'id = ?', $new );
        $rows = count( $db->fetchAll( $select ) );
        if ( $rows > 0 ) {
            $this->getId();
        } else {
            return $new;
        }
    }

    /**
     * Imposto il sito in modalità OFF
     * Chech_Off_Line
     * Imposto il sito in modalità OFF
     *
     * @access public
     * @static
     *
     * @return mixed Value.
     */
    public static function Chech_Off_Line() {
        $setting = new Application_Model_DbTable_Setting();
        $info = $setting->getSettings();
        if ( $info['off_line'] == 1 ) {
            $this->redirect( '/index/offline' );
        }
    }

     /**
     * Control_Image
     *
     * @access public
     *
     * @return mixed Value.
     */
    public static function Control_Image($directory, $image)  {
        $file = sprintf('%s/uploaded/%s/%s', $_SERVER['DOCUMENT_ROOT'], $directory, $image);
        $no_image = 'no_image.jpg';

        if($image AND $file AND file_exists($file)) {
            return $image;
        } else {
             return $no_image;
            
        }
    }


}
