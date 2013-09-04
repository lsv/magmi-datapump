<?php
/**
 * Created by lsv
 * Date: 9/4/13
 * Time: 6:29 AM
 */

namespace Datapump\Tests\Magmi;

use Datapump\Logger\Log;
use Datapump\Product\Data\RequiredData;
use Datapump\Product\ItemHolder;
use Datapump\Product\Simple;
use Datapump\Product\Configurable;

class MagmiTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var RequiredData
     */
    protected $configRequiredData;

    /**
     * @var RequiredData
     */
    protected $simpleRequiredData;

    private $products = array();

    private $itemholder;

    /**
     * @var \PDO
     */
    private $pdo;

    public function __construct()
    {
        $this->pdo = new \PDO(sprintf('mysql:host=%s;dbname=%s', 'localhost', 'magento_travis'), 'travis', 'travis',
            array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8')
        );

        $this->itemholder = new ItemHolder;

        $this->simpleRequiredData = new RequiredData();
        $this->simpleRequiredData->setSku('sku')
            ->setName('name')
            ->setPrice(100)
            ->setQty(100)
            ->setShortDescription('short description')
            ->setDescription('long description')
            ->setTax(1)
            ->setWeight(100);

        $this->configRequiredData = new RequiredData();
        $this->configRequiredData->setSku('config-sku1')
            ->setName('name')
            ->setShortDescription('short description')
            ->setDescription('long description');

        $config = new Configurable($this->configRequiredData, 'color');

        $simpleproduct1 = new Simple(clone $this->simpleRequiredData->setSku('configsimple-sku1'));
        $simpleproduct1->set('color', 'blue');
        $config->addSimpleProduct($simpleproduct1);

        $simpleproduct2 = new Simple(clone $this->simpleRequiredData->setSku('configsimple-sku2'));
        $simpleproduct2->set('color', 'green');
        $config->addSimpleProduct($simpleproduct2);

        $this->itemholder->addProduct($config);

        $this->itemholder->addProduct(new Simple(clone $this->simpleRequiredData->setSku('simple-sku1')));
        $this->itemholder->addProduct(new Simple(clone $this->simpleRequiredData->setSku('simple-sku2')));
        $this->itemholder->addProduct(new Simple(clone $this->simpleRequiredData->setSku('simple-sku3')));
        $this->itemholder->addProduct(new Simple(clone $this->simpleRequiredData->setSku('simple-sku4')));
    }

    public function test_CheckForMagmiIsSetup()
    {
        $this->setExpectedException('Datapump/Exception/MagmiHasNotBeenSetup');
        $this->itemholder->import();
    }

    public function test_CanInjectData()
    {
        $this->itemholder->setMagmi(new \Magmi_ProductImport_DataPump(), 'travis', ItemHolder::MAGMI_CREATE_UPDATE, new Log())->import();
    }

}