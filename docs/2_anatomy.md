# Anatomy of a Magmi Datapump

The itemholder keeps all the products inside it.

So all products will be build before actually imported into the magento database.

A product can be anything that extends [````ProductAbstract````](https://github.com/lsv/magmi-datapump/blob/master/src/Datapump/Product/ProductAbstract.php)

I have made some of the basics products that I normally need. They are

* [````Simple````](https://github.com/lsv/magmi-datapump/blob/master/src/Datapump/Product/Simple.php) : A plain simple product
* [````Configurable````](https://github.com/lsv/magmi-datapump/blob/master/src/Datapump/Product/Configurable.php) : A configurable product that required simple products
* [````Stock````](https://github.com/lsv/magmi-datapump/blob/master/src/Datapump/Product/Stock.php) : A Stock product which only needs a SKU and a quantity
* [````Imageproduct````](https://github.com/lsv/magmi-datapump/blob/master/src/Datapump/Product/Imageproduct.php) : This is a product which only needs a SKU

## All products

All products requires a [````RequiredData````](https://github.com/lsv/magmi-datapump/blob/master/src/Datapump/Product/Data/RequiredData.php) object to be inserted into the constructor.

The RequiredData object has many of the standard Magento attributes

## Simple

The simple product requires the following attributes else it wont get validated

* 'Sku'
* 'Description'
* 'ShortDescription'
* 'Name'
* 'Weight'
* 'Price'
* 'Tax'
* 'Qty'

### Creating a simple product

````
$data = new RequiredData();
$data
    ->setSku('sku')
    ->setDescription('long description')
    ->setShortDescription('short description')
    ->setName('name')
    ->setWeight(100)
    ->setPrice(100)
    ->setTax('Taxable Goods')
    ->setQty(100);

$product = new Simple($data);
````

Now we actually have our product, and it could be inserted into Magento - but we could do some more.

````
$product->set('description', 'even longer description');
// Just overwriting the description with a longer text
````

## Configurable

The configurable product is a bit special as this needs simple products added and requires a attribute code for associate the simple products.

The confiurable product requires the following attributes else it wont get validated

* 'Sku'
* 'Description'
* 'ShortDescription'
* 'Name'

Lets create our configurable product first

````
$data = new RequiredData();
$data
    ->setSku('config')
    ->setDescription('long config description')
    ->setShortDescription('short config description')
    ->setName('config name');

$config = new Configurable($data, 'color');
// Color is the configurable attribute key, which will be required on the simple products
````

Lets create 2 simple products and insert them into our configurable product

````
$data = new RequiredData();
$data
    ->setSku('sku')
    ->setDescription('long description')
    ->setShortDescription('short description')
    ->setName('name')
    ->setWeight(100)
    ->setPrice(100)
    ->setTax('Taxable Goods')
    ->setQty(100);

$simple1 = new Simple(clone $data); // LOOKOUT FOR THE CLONE HERE!!
// Now lets change the SKU and add our
$simple1->set('sku', 'simple1')
    ->set('color', 'blue'); // Now we added our configurable attribute on our simple product

$simple2 = new Simple(clone $data);
$simple2->set('sku', 'simple2')
    ->set('color', 'red');

// Now we have our simple products, lets add them to our configurable product

$config->addSimpleProduct($simple1)
    ->addSimpleProduct($simple2);
````

## Stock

Out stock product only need two attributes, though we still need to use the requireddata

````
$data = new RequiredData;
$data
    ->setSku('sku')
    ->setQty(10)

$stock = new Stock($data);
````

## Imageproduct

@todo write documentation for image product

## ImageHolder

Now we have created our products but we havent added nor imported it into Magento yet.

Lets do this now.

Our [````ItemHolder````](https://github.com/lsv/magmi-datapump/blob/master/src/Datapump/Product/ItemHolder.php) object requires a logger, we could just parse in our [````Logger````](https://github.com/lsv/magmi-datapump/blob/master/src/Datapump/Logger/Logger.php) but we would like the sweet power of monolog, so we start by creating our very own logger object.

````
<?php
// src/Acme/Logger/Log.php

namespace Acme\Logger;
use Datapump\Logger\Logger as LoggerInterface;
use Monolog\Logger as Monologger;

class Log implements LoggerInterface
{

    private $logger;

    public function __construct(Monologger $logger)
    {
        $this->logger = $logger;
    }

    public function log($data, $type)
    {
        $this->logger->addInfo(sprintf('[%s] %s)', $type, $data));
    }

}
?>
````

With that created we can now create our ItemHolder object

````
$log = new Monolog\Logger('name');
$log->pushHandler(new \Monolog\Handler\StreamHandler('path/to/your.log', Logger::WARNING));

$holder = new ItemHolder(new \Acme\Logger\Log($log));
$holder->addProduct($product); // The simple product we created
$holder->addProduct($config); // Adding our configurable product (we do not add the simple products assiciated with the configurable product)

// Now we have our products added to our holder, now we need to setup Magmi.
// Lets require the files from Magmi

require __DIR__ . '/path/to/magmi/inc/magmi_defs.php';
require __DIR__ . '/path/to/magmi/integration/inc/magmi_datapump.php';

$holder->setMagmi(
    \Magmi_DataPumpFactory::getDataPumpInstance("productimport"),
    'default', // The Magmi profile name
    ItemHolder::MAGMI_CREATE_UPDATE
);

// Now Magmi is initialized, so now we can actually import the products

$holder->import();
````