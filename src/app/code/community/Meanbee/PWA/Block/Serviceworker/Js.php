<?php

class Meanbee_PWA_Block_Serviceworker_Js extends Mage_Core_Block_Template
{
    const VERSION = "v1";

    public function _construct()
    {
        parent::_construct();

        $this->addData(array(
            "cache_lifetime" => 60*60*24*364,
        ));
    }

    /**
     * Get the service worker version string.
     *
     * @return string
     */
    public function getVersion()
    {
        return implode("-", array(
            static::VERSION,
            time(),
        ));
    }

    /**
     * Get the Offline Notification page URL.
     *
     * @return string
     */
    public function getOfflinePageUrl()
    {
        return $this->getConfig()->getOfflinePageUrl();
    }

    /**
     * Get a list of URLs blacklisted from cache or offline viewing.
     *
     * @return array
     */
    public function getOfflineUrlBlacklist()
    {
        return $this->getConfig()->getOfflineUrlBlacklist();
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
