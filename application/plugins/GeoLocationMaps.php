<?php

class Plugin_GeoLocationMaps extends Zend_Controller_Plugin_Abstract
{
    /**
     * @return mixed
     */
    private function _Check_IP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {

            /**
             * check ip from share internet
             */
            return $_SERVER['HTTP_CLIENT_IP'];

        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

            /**
             * to check ip is pass from proxy
             */
            return $_SERVER['HTTP_X_FORWARDED_FOR'];

        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * @return array
     * @throws ErrorException
     */
    private function _Load_Xml()
    {
        // $ip = $this->_Check_IP();
        $ip = '79.53.101.25';

        if (empty($ip)) {
            throw new ErrorException('IP Address Not Found');
        }

        $services = sprintf('http://services.ipaddresslabs.com/iplocation/locateip?key=SAKT8SAXER362234SVNZ&ip=%s', $ip);
        $xml = new Zend_Config_Xml($services);

        $data = array(
            'region'    => $xml->geolocation_data->region_name,
            'latitude'  => $xml->geolocation_data->latitude,
            'longitude' => $xml->geolocation_data->longitude
        );

        // print_r($xml);
        // print_r($data);
        return $data;
    }

    /**
     * @return array
     */
    public function Region_Code()
    {
        return $this->_Load_Xml();
    }

}
