#!/usr/bin/env bash

set -e # Exit on error

if [ $(find $MAGE_ROOT_DIR -maxdepth 0 -type d -empty 2>/dev/null) ]; then
    # Install Magento
    /n98-magerun.phar install                               \
        --installationFolder=$MAGE_ROOT_DIR                 \
        --magentoVersionByName="magento-mirror-1.9.2.4"     \
        --installSampleData=no                              \
        --dbHost=db                                         \
        --dbUser=magento                                    \
        --dbPass=magento                                    \
        --dbName=magento                                    \
        --useDefaultConfigParams=yes                        \
        --baseUrl="https://pwa-magento.docker/"

    chgrp -R 33 $MAGE_ROOT_DIR/media $MAGE_ROOT_DIR/var
    find $MAGE_ROOT_DIR/media $MAGE_ROOT_DIR/var -type d -exec chmod 775 {} +
    find $MAGE_ROOT_DIR/media $MAGE_ROOT_DIR/var -type f -exec chmod 664 {} +

    cd $MAGE_ROOT_DIR
    modman init
    modman link /src
fi

cd $MAGE_ROOT_DIR
modman deploy src

magerun sys:setup:run
magerun config:set dev/template/allow_symlink 1
