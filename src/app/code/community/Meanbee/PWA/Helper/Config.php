<?php

class Meanbee_PWA_Helper_Config extends Mage_Core_Helper_Abstract
{
    const XML_PATH_ENABLED = "web/pwa/enabled";
    const XML_PATH_OFFLINE_CMS_PAGE = "web/pwa/offline_cms_page";
    const XML_PATH_OFFLINE_URL_BLACKLIST = "web/pwa/offline_url_blacklist";

    /**
     * Check if PWA features have been enabled in System Configuration.
     *
     * @param Mage_Core_Model_Store|int|null $store
     *
     * @return bool
     */
    public function isEnabled($store = null)
    {
        return Mage::getStoreConfigFlag(static::XML_PATH_ENABLED, $store);
    }

    /**
     * Get the URL to the Offline Notification page.
     *
     * Warning: Always returns the URL in the scope of the current store!
     *
     * @return string
     */
    public function getOfflinePageUrl()
    {
        return Mage::helper("cms/page")->getPageUrl(Mage::getStoreConfig(static::XML_PATH_OFFLINE_CMS_PAGE));
    }

    /**
     * Get the list of URLs blacklisted from cache or offline viewing.
     *
     * @return array
     */
    public function getOfflineUrlBlacklist()
    {
        $base_url = Mage::app()->getStore()->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);

        $paths = explode("\n", Mage::getStoreConfig(static::XML_PATH_OFFLINE_URL_BLACKLIST));

        return array_values(array_filter(array_map(function ($path) use ($base_url) {
            $path = trim($path);
            return $path ? $base_url . $path : null;
        }, $paths)));
    }
}
