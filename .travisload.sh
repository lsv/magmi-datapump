#!/bin/sh

MAGMI_VERSION=0.7.18;
MAGENTO=1.7.0.2

DB_USER=travis
DB_PASS=travis
DB_NAME=magento_travis

MAGENTO_LOGIN=travis
MAGENTO_PASS=travis

if [ ! -d magento ]; then
    wget "http://www.magentocommerce.com/downloads/assets/$MAGENTO/magento-$MAGENTO.tar.gz"
    tar xf "magento-$MAGENTO.tar.gz"
    chmod 777 -R magento
    cp .travis/magento/local.xml magento/app/etc
    rm "magento-$MAGENTO.tar.gz"
fi

if [ -d magmi ]; then
    sudo rm -rf magmi
fi

cp -R ".travis/magmi_$MAGMI_VERSION" magmi
mysql -u $DB_USER -p$DB_PASS $DB_NAME < ./.travis/magento/magento.sql