<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initAutoload()
	{
		$moduleLoader = new Zend_Application_Module_Autoloader(array(
			'namespace' => '',
			'basePath' => APPLICATION_PATH
			));
		return $moduleLoader;
	}

	protected function _initPlugins()
	{
		$acl = new Application_Model_LibraryAcl();
		$auth = Zend_Auth::getInstance();

		$front = Zend_Controller_Front::getInstance();
		$front->registerPlugin(new Plugin_AccessCheck($acl, $auth));
	}

}
