#!/bin/sh

MAGMI_RELEASE=0.7;
MAGMI_VERSION=0.7.18;
MAGMI_EXTRA=20130131;
MAGENTO=1.7.0.2

DB_USER=travis
DB_PASS=travis
DB_NAME=magento_travis

MAGENTO_LOGIN=travis
MAGENTO_PASS=travis01

DB_EXISTS=`echo "use $DB_NAME;show tables;" | mysql -u $DB_USER -p$DB_PASS`

if [ ! -d magmi ]; then
    if [ ! -f "magmi_$MAGMI_VERSION.zip" ]; then
        wget "http://downloads.sourceforge.net/project/magmi/magmi-$MAGMI_RELEASE/releases/magmi_$MAGMI_VERSION.zip"
    fi

    if [ ! -f "magmi_extra_plugins_$MAGMI_EXTRA.zip" ]; then
        wget "http://downloads.sourceforge.net/project/magmi/magmi-$MAGMI_RELEASE/plugins/packages/magmi_extra_plugins_$MAGMI_EXTRA.zip"
    fi

    unzip "magmi_$MAGMI_VERSION.zip"
    unzip "magmi_extra_plugins_$MAGMI_EXTRA.zip"

    mv extra/general/* magmi/plugins/base/general
    mv extra/itemprocessors/* magmi/plugins/base/itemprocessors

    cp -R .travis/magmi/configuration/travis magmi/conf
    cp .travis/magmi/configuration/magmi.ini magmi/conf

    rm -rf extra
    rm "magmi_$MAGMI_VERSION.zip"
    rm "magmi_extra_plugins_$MAGMI_EXTRA.zip"
fi

if [ ! -d magento ]; then
    wget "http://www.magentocommerce.com/downloads/assets/$MAGENTO/magento-$MAGENTO.tar.gz"
    tar xf "magento-$MAGENTO.tar.gz"
    chmod 777 -R magento
    cp .travis/magento/local.xml magento/app/etc
    rm "magento-$MAGENTO.tar.gz"
fi

mysql -u $DB_USER -p$DB_PASS $DB_NAME < ./.travis/magento/magento.sql