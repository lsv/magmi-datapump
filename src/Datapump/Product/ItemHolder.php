<?php
/**
 * @author Martin Aarhof <martin.aarhof@gmail.com>
 * @version GIT: $Id$
 */
namespace Datapump\Product;

use Datapump\Exception;
use Datapump\Logger\Logger;
use Datapump\Product\Data\DataAbstract;
use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ItemHolder
 * @package Datapump\Product
 */
class ItemHolder
{

    /**
     * Creates and update
     */
    const MAGMI_CREATE_UPDATE = 'create';

    /**
     * Update only
     */
    const MAGMI_UPDATE = 'update';

    /**
     * Create only
     */
    const MAGMI_CREATE = 'xcreate';

    /**
     * Item holder
     * @var array
     */
    private $products = array();

    /**
     * Magmi interface
     * @var \Magmi_ProductImport_DataPump
     */
    private $magmi;

    /**
     * Logger instance
     * @var \Datapump\Logger\Logger
     */
    private $logger;

    /**
     * @var OutputInterface
     */
    private $output = null;

    /**
     * Magmi profile name
     * @var string
     */
    private $profile;

    /**
     * Magmi mode
     * @var string
     */
    private $mode;

    /**
     * Only debug
     * @var bool
     */
    private $debugMode = false;

    /**
     * Start our itemholder
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * Setup Magmi
     *
     * @param \Magmi_ProductImport_DataPump $magmi
     * @param string $profile
     * @param string $mode
     *
     * @return $this
     */
    public function setMagmi(\Magmi_ProductImport_DataPump $magmi, $profile, $mode = self::MAGMI_CREATE_UPDATE) {
        $this->magmi = $magmi;
        $this->profile = $profile;
        $this->mode = $mode;
        return $this;
    }

    /**
     * Adds product to our item holder
     *
     * @param ProductAbstract|array $product
     *
     * @return $this
     * @throws \Datapump\Exception\ProductSkuAlreadyAdded
     * @throws \Datapump\Exception\ProductNotAnArrayOrProductAbstract
     */
    public function addProduct($product)
    {
        if (is_array($product)) {
            foreach ($product as $p) {
                $this->addProduct($p);
            }

            return $this;
        }

        if ($product instanceof ProductAbstract) {
            $check = $product->check();
            if ($check) {
                $sku = $product->get('sku');
                foreach ($this->products as $p) {
                    /** @var ProductAbstract $p */
                    if ($p->get('sku') == $sku) {
                        throw new Exception\ProductSkuAlreadyAdded(
                            sprintf(
                                'Product with SKU: %s is already added',
                                $product->get('sku')
                            )
                        );
                    }
                }
                $this->products[] = $product;
            }

            return $this;
        }

        throw new Exception\ProductNotAnArrayOrProductAbstract(get_class($product) . ' is not valid');
    }

    /**
     * Remove a product from our item holder
     *
     * @param string $sku
     *
     * @return bool
     */
    public function removeProduct($sku)
    {
        foreach ($this->products as $key => $product) {
            /** @var ProductAbstract $product */
            if ($product->getRequiredData()->getSku() == $sku) {
                unset($this->products[$key]);

                return true;
            }
        }

        return false;
    }

    /**
     * Find a product in our item holder
     *
     * @param string $sku
     *
     * @return bool|ProductAbstract
     */
    public function findProduct($sku)
    {
        foreach ($this->products as $product) {
            /** @var ProductAbstract $product */
            if ($product->getRequiredData()->getSku() == $sku) {
                return $product;
            }
        }

        return false;
    }

    /**
     * Do the actual import to our database
     * @todo rewrite this, so it can be overwritten - maybe add required import to product?
     * @param bool $debug
     * @throws Exception\MagmiHasNotBeenSetup
     */
    public function import($debug = false)
    {
        $this->debugMode = $debug;
        if ($debug === false) {
            if (!$this->magmi instanceof \Magmi_ProductImport_DataPump) {
                throw new Exception\MagmiHasNotBeenSetup(
                    sprintf(
                        'Magmi has not been setup yet, use setMagmi(%s, %s, %s. %s)',
                        'Magmi_ProductImport_DataPump $magmi',
                        'string $profile',
                        'string $mode',
                        'Datapump\Logger Logger $logger'
                    )
                );
            }

            $this->magmi->beginImportSession($this->profile, $this->mode, $this->logger);

        }

        $output = array();

        if ($this->output instanceof OutputInterface && $debug === false) {
            $progress = new ProgressHelper();
            $this->output->writeln("\nImport progress");
            $progress->start($this->output, count($this->products));
        }

        foreach ($this->products as $product) {
            if ($this->output instanceof OutputInterface && $debug === false) {
                $progress->advance();
            }

            /** @var ProductAbstract $product */
            switch ($product->getRequiredData()->getType()) {
                case DataAbstract::TYPE_CONFIGURABLE:
                    /** @var Configurable $product */
                    foreach ($product->getSimpleProducts() as $simple) {
                        /** @var Simple $simple */
                        $output[] = $this->inject($simple);
                    }

                    $output[] = $this->inject($product);
                    break;
                default:
                    $output[] = $this->inject($product);
                    break;
            }
        }

        if ($debug === false) {
            $this->magmi->endImportSession();
            if ($this->output instanceof OutputInterface) {
                $progress->finish();
            }
        } else {
            return $output;
        }

        return true;

    }

    /**
     * Inject our product to Magmi
     *
     * @param ProductAbstract $product
     * @return array
     */
    private function inject(ProductAbstract $product)
    {
        $product->import();
        if ($this->debugMode) {
            $output = $product->debug();
        } else {
            $output = $this->magmi->ingest($product->getData());
        }
        $product->after();
        return $output;
    }
}
