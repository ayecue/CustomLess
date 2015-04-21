<?php

namespace CustomLess;

use Pimcore\Tool as PimcoreTool;
use CustomLess\Config as LessConfig;
use CustomLess\Controller\Plugin\Parser as LessParser;

class Plugin extends Config {
    const PLUGIN_STACK_INDEX = 1003;

	public static function install() {
		parent::install();

		return "Plugin successfully installed.";
    }

    public static function uninstall() {
		return "Plugin successfully uninstalled.";
    }

	public static function isInstalled() {
        return parent::isInstalled();
	}

	public function preDispatch() {
	 	// Pimcore CDN is not enabled by default in Pimcore.php                  
		if(!isset($_SERVER['HTTP_SECURE']) && PimcoreTool::isFrontend()){
			//die('Ende');
			$less = new LessParser();

            // 805 means trigger this plugin later than other plugins (with lower numbers)
			$instance = \Zend_Controller_Front::getInstance();

			$instance->registerPlugin($less,self::PLUGIN_STACK_INDEX);
		}
	}
}

