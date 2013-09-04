<?php
/**
 * @author Martin Aarhof <martin.aarhof@gmail.com>

 * @version GIT: $Id$
 */
namespace Datapump\Product;

use Datapump\Exception;
use Datapump\Logger\Logger;
use Datapump\Product\Data\DataAbstract;

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
     * Setup Magmi
     * @param \Magmi_ProductImport_DataPump $magmi
     * @param string $profile
     * @param string $mode
     * @param Logger $logger
     */
    public function setMagmi(
        \Magmi_ProductImport_DataPump $magmi,
        $profile,
        $mode = self::MAGMI_CREATE_UPDATE,
        Logger $logger = null
    ) {
        $this->magmi = $magmi;
        $this->magmi->beginImportSession($profile, $mode, $logger);
    }

    /**
     * Adds product to our item holder
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
     */
    public function import()
    {
        foreach ($this->products as $product) {
            /** @var ProductAbstract $product */
            switch ($product->getRequiredData()->getType()) {
                case DataAbstract::TYPE_CONFIGURABLE:
                    /** @var Configurable $product */
                    foreach ($product->getSimpleProducts() as $simple) {
                        /** @var Simple $simple */
                        $this->inject($simple);
                    }
                    $this->inject($product);
                    break;
                default:
                    $this->inject($product);
                    break;
            }
        }

        $this->magmi->endImportSession();
    }

    /**
     * Inject our product to Magmi
     * @param ProductAbstract $product
     */
    private function inject(ProductAbstract $product)
    {
        $product->beforeImport();
        $this->magmi->ingest($product->getData());
        $product->afterImport();
    }
}
