<?php

class Meanbee_PWA_Serviceworker_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        return $this->_forward("js");
    }

    public function jsAction()
    {
        $this->loadLayout();
        $this->renderLayout();

        $this->getResponse()->setHeader("Content-Type", "text/javascript");
    }
}
