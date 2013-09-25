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
class Configurable extends ProductAbstract
{

    /**
     * Magmi confiurable attribute key
     */
    const CONFIG_ATTR_KEY = 'configurable_attributes_array';

    /**
     * Product type
     * @var string
     */
    protected $type = DataInterface::TYPE_CONFIGURABLE;

    /**
     * The simple products added to this configurable product
     * @var array
     */
    protected $simpleProducts = array();

    /**
     * The required fields for a configurable product
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
     * Our configurable product
     *
     * @param RequiredData $data
     * @param string|array $configurableAttribute : The attribute we should look for on the simple products
     */
    public function __construct(RequiredData $data, $configurableAttribute)
    {
        if (!is_array($configurableAttribute)) {
            $configurableAttribute = array($configurableAttribute);
        }

        $data->set(self::CONFIG_ATTR_KEY, $configurableAttribute);
        parent::__construct($data);
    }

    /**
     * Add simple product to our configurable product
     *
     * @param Simple $product
     * @param bool $visibleInFrontend
     * @param bool $searchable
     *
     * @return $this
     * @throws \Datapump\Exception\ProductSkuAlreadyAdded
     * @throws \Datapump\Exception\SimpleProductMissingConfigurableAttribute
     */
    public function addSimpleProduct(Simple $product, $visibleInFrontend = false, $searchable = false)
    {
        $product->check();

        if (!$this->simpleProductConfigAttributeTest($product)) {
            throw new Exception\SimpleProductMissingConfigurableAttribute(
                sprintf(
                    '%s is missing the keys %s',
                    $product->getRequiredData()->getSku(),
                    implode(', ', $this->getRequiredData()->get(self::CONFIG_ATTR_KEY))
                )
            );
        }

        foreach ($this->simpleProducts as $p) {
            /** @var Simple $p */
            if ($p->get('sku') === $product->get('sku')) {
                throw new Exception\ProductSkuAlreadyAdded(
                    sprintf('Product with SKU: %s is already added', $product->get('sku'))
                );
            }
        }

        $product->getRequiredData()->setVisibility($visibleInFrontend, $searchable);
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
     * List the simple products added to this configurable product
     * @return array
     */
    public function getSimpleProducts()
    {
        return $this->simpleProducts;
    }

    /**
     * Count the number of simple products added to this configurable product
     * @return int
     */
    public function countSimpleProducts()
    {
        return count($this->getSimpleProducts());
    }

    /**
     * Get the configurable values
     * @return array
     */
    public function getConfigurableAttribute()
    {
        return $this->getRequiredData()->get(self::CONFIG_ATTR_KEY);
    }

    /**
     * This will be runned before the importer
     * @return $this
     */
    public function beforeImport()
    {
        $this->setConfigurableAttribute();
        $this->setSimpleSkus();
        $this->setConfigPrice();
    }

    public function afterImport()
    {

    }

    private function setConfigurableAttribute()
    {
        $this->set('configurable_attributes', implode(',', $this->get(self::CONFIG_ATTR_KEY)));
    }

    private function setSimpleSkus()
    {
        $p = array();
        foreach($this->getSimpleProducts() as $product /** @var Simple $product */) {
            $p[] = $product->get('sku');
        }
        $this->set('simples_skus', implode(',', $p));
    }

    /**
     * Sets the configurable product price, before import, magento needs a price on the configurable product :(
     */
    private function setConfigPrice()
    {
        $price = 0;
        foreach ($this->simpleProducts as $p) {
            /** @var Simple $p */
            if ($p->getRequiredData()->getPrice() > $price) {
                $price = $p->getRequiredData()->getPrice();
            }
        }

        $this->set('price', $price);

        $specialprice = 0;
        foreach($this->simpleProducts AS $p) {
            /** @var Simple $p */
            if ($p->get('special_price') !== null && $p->get('special_price') > $specialprice) {
                $specialprice = $p->get('special_price');
            }
        }

        if ($specialprice > 0) {
            $this->set('special_price', $specialprice);
        }

    }

    /**
     * Check to see if the added simple product has the keys to our configurable product
     *
     * @param Simple $product
     *
     * @return bool
     */
    private function simpleProductConfigAttributeTest(Simple $product)
    {
        foreach ($this->getConfigurableAttribute() as $key) {
            if ($product->get($key) === null) {
                return false;
            }
        }

        return true;
    }
}
