<?php
/**
 * Created by lsv
 * Date: 9/4/13
 * Time: 6:29 AM
 */

namespace Datapump\Tests\Magmi;

use Datapump\Logger\Log;
use Datapump\Product\Data\Category;
use Datapump\Product\Data\RequiredData;
use Datapump\Product\ItemHolder;
use Datapump\Product\Simple;
use Datapump\Product\Configurable;

/**
 * @requires extension mysqli
 */
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
        $this->simpleRequiredData
            ->setShortDescription('short description')
            ->setDescription('long description')
            ->setTax(0)
            ->setWeight(100);

        $this->configRequiredData = new RequiredData();
        $this->configRequiredData
            ->setShortDescription('short description')
            ->setDescription('long description');

        $config = new Configurable(
            clone $this->configRequiredData
                ->setSku('config1')
                ->setName('config1')
        , 'color');

        $category = new Category();
        $category->set('active-config');
        $category->set('inactive-config', false);
        $category->set('level01/level02/level03');
        $config->injectData($category);

        $config->addSimpleProduct(new Simple(
            clone $this->simpleRequiredData
                ->setSku('configsimple-sku1')
                ->setName('configsimple-sku1')
                ->setQty(12)
                ->setPrice(120)
                ->set('color', 'blue')
        ));

        $config->addSimpleProduct(new Simple(
            clone $this->simpleRequiredData
                ->setSku('configsimple-sku2')
                ->setName('configsimple-sku2')
                ->setQty(24)
                ->setPrice(110)
                ->set('color', 'green')
        ));

        $this->itemholder->addProduct($config);

        $this->itemholder->addProduct(new Simple(
            clone $this->simpleRequiredData->setSku('simple-sku1-dontmanagestock')
                ->setQty(null)
                ->setPrice(500)
                ->setName('simple-sku1-dontmanagestock')
        ));
        $this->itemholder->addProduct(new Simple(
            clone $this->simpleRequiredData->setSku('simple-sku2')
                ->setQty(10)
                ->setPrice(400)
                ->setName('simple-sku2')
        ));
        $this->itemholder->addProduct(new Simple(
            clone $this->simpleRequiredData->setSku('simple-sku3')
                ->setQty(-10)
                ->setPrice(300)
                ->setName('simple-sku3')
        ));
        $this->itemholder->addProduct(new Simple(
            clone $this->simpleRequiredData->setSku('simple-sku4')
                ->setQty(0)
                ->setPrice(200)
                ->setName('simple-sku4')
        ));
    }

    public function test_canTestForMagmi()
    {
        $this->setExpectedException('Datapump\Exception\MagmiHasNotBeenSetup');
        $holder = new ItemHolder;
        $holder->import();
    }

    public function test_ProductsNotInserted()
    {
        $q = $this->pdo->query('SELECT COUNT(*) AS num FROM catalog_product_entity');
        $q->execute();
        $num = $q->fetch();
        $this->assertEquals(0, $num['num']);
    }

    public function test_CanInjectData()
    {
        $this->itemholder->setMagmi(
            \Magmi_DataPumpFactory::getDataPumpInstance("productimport"),
            'travis',
            ItemHolder::MAGMI_CREATE_UPDATE,
            new Log()
        )->import();
    }

    public function test_ProductsInserted()
    {
        $q = $this->pdo->query('SELECT COUNT(*) AS num FROM catalog_product_entity');
        $q->execute();
        $num = $q->fetch();
        $this->assertEquals(7, $num['num']);
    }

    public function test_checkProductsStock()
    {

    }

    public function test_checkProductsPrice()
    {

    }

}