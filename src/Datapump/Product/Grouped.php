<?php
/**
 * @author Martin Aarhof <martin.aarhof@gmail.com>
 * @version GIT: $Id$
 */

namespace Datapump\Product;

use Datapump\Exception;
use Datapump\Product\Data\DataInterface;
use Datapump\Product\Data\RequiredData;

/**
 * Class Configurable
 * @package Datapump\Product
 */
class Grouped extends ProductAbstract
{
    /**
     * Product type
     * @var string
     */
    protected $type = DataInterface::TYPE_GROUPED;

    /**
     * The simple products added to this grouped product
     * @var array
     */
    protected $simpleProducts = array();

    /**
     * The required fields for a grouped product
     * @var array
     */
    protected $requiredFields = array(
        'Type' => 'Missing product type',
        'Sku' => 'Missing SKU number',
        'Visibility' => 'Missing visibility status',
        'Description' => 'Missing description',
        'ShortDescription' => 'Missing short description',
        'Name' => 'Missing product name',
        'Status' => 'Missing product status',
        'Price' => 'Missing product price',
    );

    /**
     * Our grouped product
     *
     * @param RequiredData $data
     */
    public function __construct(RequiredData $data)
    {
        parent::__construct($data);
    }

    /**
     * Add simple product to our grouped product
     *
     * @param Simple $product
     * @param bool $visibleInFrontend
     * @param bool $searchable
     *
     * @return $this
     * @throws \Datapump\Exception\ProductSkuAlreadyAdded
     */
    public function addSimpleProduct(Simple $product, $visibleInFrontend = false, $searchable = false)
    {
        $product->check();

        foreach ($this->simpleProducts as $p) {
            /** @var Simple $p */
            if ($p->get('sku') === $product->get('sku')) {
                throw new Exception\ProductSkuAlreadyAdded(
                    sprintf('Product with SKU: %s is already added', $product->g                                et('sku'))
                );
            }
        }

        $product->getRequiredData()->setVisibility($visibleInFrontend, $searchab                                le);
        $this->simpleProducts[] = $product;

        return $this;
    }

    /**
     * Find our simple product
     *
     * @param string $sku
     *
     * @return Simple|null
     */
    public function getSimpleProduct($sku)
    {
        foreach ($this->simpleProducts as $product) {
            /** @var Simple $product */
            if ($product->getRequiredData()->getSku() == $sku) {
                return $product;
            }
        }

        return null;
    }

    /**
     * List the simple products added to this grouped product
     * @return array
     */
    public function getSimpleProducts()
    {
        return $this->simpleProducts;
    }

    /**
     * Count the number of simple products added to this grouped product
     * @return int
     */
    public function countSimpleProducts()
    {
        return count($this->getSimpleProducts());
    }

    /**
     * {@inheritdoc}
     */
    public function beforeImport()
    {
        $this->setSimpleSkus();
    }

    /**
     * {@inheritdoc}
     */
    public function afterImport()
    {

    }

    /**
     * Sets the simple products sku array
     */
    private function setSimpleSkus()
    {
        $p = array();
        /** @var Simple $product */
        foreach ($this->getSimpleProducts() as $product) {
            $p[] = $product->get('sku');
        }
        $this->set('grouped_skus', implode(',', $p));
    }
}
