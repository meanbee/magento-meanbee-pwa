<?php

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;

$installer->startSetup();

/**
 * Add the Offline Notification CMS page
 */

$offline_page_identifier = "offline";

// Switch to the Admin store scope for updates
$current_store = Mage::app()->getStore();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$cms_page = Mage::getModel("cms/page")->load($offline_page_identifier);

if (!$cms_page->getId()) {
    $cms_page->clearInstance()
        ->setData(array(
            "identifier"      => $offline_page_identifier,
            "title"           => "Offline",
            "content_heading" => "You are offline!",
            "content"         => <<<EOC
<p>It appears you don't currently have a network connection. However, you can still view pages that you have visited before and continue browsing the catalog as it was when you last saw it. Keep in mind that prices and offers may be out of date - we will show you the updated catalog once you are back online.</p>
<p>Some actions such as managing your user account, adding products to your basket and checking out are unavailable while offline. If you with to place an order, please try again once your network connection is restored.</p>
EOC
        ,
            "root_template"   => "one_column",
            "is_active"       => 1,
            "stores"          => array(0),
        ))
        ->save();
}

// Return back to the previous store scope
Mage::app()->setCurrentStore($current_store);

$installer->endSetup();
