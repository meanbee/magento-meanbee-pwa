<?php

class Meanbee_PWA_Block_Serviceworker_Register extends Mage_Core_Block_Template
{
    /**
     * Check if PWA features are enabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->getConfig()->isEnabled();
    }

    /**
     * Get the service worker JS url.
     *
     * @return string
     */
    public function getServiceworkerJsUrl()
    {
        return $this->_getUrlModel()->getDirectUrl("serviceworker.js");
    }

    /**
     * Get the configuration helper.
     *
     * @return Meanbee_PWA_Helper_Config
     */
    protected function getConfig()
    {
        return Mage::helper("meanbee_pwa/config");
    }
}
