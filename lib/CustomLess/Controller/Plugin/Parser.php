<?php

namespace CustomLess\Controller\Plugin;

use Pimcore\Controller\Plugin as PimcorePlugin;
use Pimcore\Config as PimcoreConfig;
use CustomLess\Tool\Helper as LessHelper;

class Parser extends PimcorePlugin\Less {

    static public function getLessJSPath() {
        return "/plugins/CustomLess/static/js/lib/less.min.js";
    }

    static public function getScriptTag() {
        return "\n" .
                '<script type="text/javascript">' .
                    'var less = {"env": "development"};' . 
                '</script>' . 
                '<script type="text/javascript" src="' . 
                    self::getLessJSPath() . 
                '"></script>' .
                "\n";
    }

    public function routeStartup(\Zend_Controller_Request_Abstract $request) {
        $this->conf = PimcoreConfig::getSystemConfig();

        if($request->getParam('disable_less_compiler') || $_COOKIE["disable_less_compiler"]){
            return $this->disable();
        }
    }

    public function dispatchLoopShutdown() {
        parent::dispatchLoopShutdown();
    }

    protected function frontend () {
        $body = $this->getResponse()->getBody();
        $body = LessHelper::processHtml($body);

        $this->getResponse()->setBody($body);
    }

    protected function editmode () {
        $body = $this->getResponse()->getBody();

        $html = str_get_html($body);

        if($html) {
            $head = $html->find("head",0);
            if($head) {
                $head->innertext = $head->innertext . self::getScriptTag();

                $body = $html->save();
                $this->getResponse()->setBody($body);
            }

            $html->clear();
            unset($html);
        }
    }

}

