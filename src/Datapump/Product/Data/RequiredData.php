<?php
/**
 * @author Martin Aarhof <martin.aarhof@gmail.com>
 * @version GIT: $Id$
 */
namespace Datapump\Product\Data;

/**
 * Class RequiredData
 * @package Datapump\Product\Data
 */
class RequiredData extends DataAbstract
{

    /**
     * Standard data
     * @var array
     */
    protected $data = array(
        'attribute_set' => 'Default',
        'type' => DataInterface::TYPE_SIMPLE,
        'visibility' => DataInterface::VISIBILITY_CATALOG_SEARCH,
        'weight' => 0.01,
        'status' => 1,
        'store' => 'admin'
    );

    /**
     * Sets the store name
     *
     * @param string $store
     *
     * @return RequiredData
     */
    public function setStore($store)
    {
        $this->set('store', $store);

        return $this;
    }

    /**
     * Get the store name
     * @return string|null
     */
    public function getStore()
    {
        return $this->get('store');
    }

    /**
     * Sets the attribute set
     *
     * @param string $set
     *
     * @return RequiredData
     */
    public function setAttributeSet($set)
    {
        $this->set('attribute_set', $set);

        return $this;
    }

    /**
     * Gets the attribute set name
     * @return string|null
     */
    public function getAttributeSet()
    {
        return $this->get('attribute_set');
    }

    /**
     * Get the created at time
     * @return int|null
     */
    public function getCreatedAt()
    {
        return $this->get('created_at');
    }

    /**
     * Sets the created at attribute
     * @param int $time
     *
     * @return RequiredData
     */
    public function setCreatedAt($time)
    {
        $this->set('created_at',(int) $time);

        return $this;
    }

    /**
     * Sets the type of the product
     *
     * @param string $type
     *
     * @return RequiredData
     */
    public function setType($type)
    {
        $this->set('type', $type);

        return $this;
    }

    /**
     * Gets the type of the product
     * @return string|null
     */
    public function getType()
    {
        return $this->get('type');
    }

    /**
     * Sets the SKU of the product
     *
     * @param string $sku
     *
     * @return RequiredData
     */
    public function setSku($sku)
    {
        $this->set('sku', $sku);

        return $this;
    }

    /**
     * Gets the SKU of the product
     * @return string|null
     */
    public function getSku()
    {
        return $this->get('dept');
    }

    /**
     * Sets the Dept of the product
     *
     * @param string $dept
     *
     * @return RequiredData
     */
    public function setDept($dept)
    {
        $this->set('dept', $dept);

        return $this;
    }

    /**
     * Gets the Dept of the product
     * @return string|null
     */
    public function getDept()
    {
        return $this->get('dept');
    }

    /**
     * Sets the Dept of the product
     *
     * @param $winretailId
     * @return RequiredData
     *
     */
    public function setWinRetailId($winretailId)
    {
        $this->set('winretail_id', $winretailId);

        return $this;
    }

    /**
     * Gets the Dept of the product
     * @return string|null
     */
    public function getWinRetailId()
    {
        return $this->get('winretail_id');
    }


    /**
     * Sets the visibility of the product
     *
     * @param bool $visibleInCatalog
     * @param bool $searchable
     *
     * @return RequiredData
     */
    public function setVisibility($visibleInCatalog = true, $searchable = true)
    {
        $vis = self::VISIBILITY_NOTVISIBLE;
        if ($visibleInCatalog && $searchable) {
            $vis = self::VISIBILITY_CATALOG_SEARCH;
        } elseif ($visibleInCatalog) {
            $vis = self::VISIBILITY_CATALOG;
        } elseif ($searchable) {
            $vis = self::VISIBILITY_SEARCH;
        }

        $this->set('visibility', $vis);

        return $this;
    }

    /**
     * Gets the visibility of the product
     * @return string|null
     */
    public function getVisibility()
    {
        return $this->get('visibility');
    }

    /**
     * Sets the long description of the product
     *
     * @param string $description
     *
     * @return RequiredData
     */
    public function setDescription($description)
    {
        $this->set('description', $description);

        return $this;
    }

    /**
     * Gets the long description of the product
     * @return string|null
     */
    public function getDescription()
    {
        return $this->get('description');
    }

    /**
     * Sets the short description of the product
     *
     * @param string $description
     *
     * @return RequiredData
     */
    public function setShortDescription($description)
    {
        $this->set('short_description', $description);

        return $this;
    }

    /**
     * Gets the short description of the product
     * @return string|null
     */
    public function getShortDescription()
    {
        return $this->get('short_description');
    }

    /**
     * Sets the name
     *
     * @param string $name
     *
     * @return RequiredData
     */
    public function setName($name)
    {
        $this->set('name', $name);

        return $this;
    }

    /**
     * Gets the name
     * @return string|null
     */
    public function getName()
    {
        return $this->get('name');
    }

    /**
     * Sets the weight
     *
     * @param float $weight
     *
     * @return RequiredData
     */
    public function setWeight($weight)
    {
        $this->set('weight', (float)$weight);

        return $this;
    }

    /**
     * Gets the weight of the product
     * @return float|null
     */
    public function getWeight()
    {
        return $this->get('weight');
    }

    /**
     * Set the status
     *
     * @param bool $enabled
     *
     * @return RequiredData
     */
    public function setStatus($enabled)
    {
        $this->set('status', ($enabled ? 1 : 2));

        return $this;
    }

    /**
     * Get the status
     * @return int|null (1 = enabled, 2 = disabled)
     */
    public function getStatus()
    {
        $status = $this->get('status');

        //return ($status === 1 ? true : false);
        return $status;
    }

    /**
     * Set product as enabled
     * @return RequiredData
     */
    public function setEnabled()
    {
        $this->setStatus(true);

        return $this;
    }

    /**
     * Set product as disabled
     * @return RequiredData
     */
    public function setDisabled()
    {
        $this->setStatus(false);

        return $this;
    }

    /**
     * Gets the meta information for the product
     * @return object
     */
    public function getMetaInformation()
    {
        $meta = new \stdClass();
        $meta->title = $this->get('meta_title');
        $meta->description = $this->get('meta_description');
        $meta->keywords = $this->get('meta_keywords');

        return $meta;
    }

    /**
     * Set Manufacturer attribute
     *
     * @param string|null $title
     * @param string|null $description
     * @param string|null $keywords
     * @return RequiredData
     */
    public function setMetaInformation($title,$description,$keywords)
    {
        if ($title !== null)
            $this->set('meta_title',$title);
        if ($description !== null)
            $this->set('meta_description',$description);
        if ($keywords !== null)
            $this->set('meta_keywords',$keywords);

        return $this;
    }

    /**
     * Gets the manufacturer
     * @return string|null
     */
    public function getManufacturer()
    {
        return $this->get('manufacturer');
    }

    /**
     * Set Manufacturer attribute
     *
     * @param string $manufacturer
     * @return RequiredData
     */
    public function setManufacturer($manufacturer)
    {
        $this->set('manufacturer',$manufacturer);

        return $this;
    }

    /**
     * Gets the MSRP
     * @return float|null
     */
    public function getMsrp()
    {
        return $this->get('msrp');
    }

    /**
     * Set MSRP attribute
     *
     * @param float $msrp
     * @return RequiredData
     */
    public function setMsrp($msrp)
    {
        $this->set('msrp',(float) $msrp);

        return $this;
    }

    /**
     * Gets the Country of Manufacture country code
     * @return string|null
     */
    public function getCountryOfManufacture()
    {
        return $this->get('country_of_manufacture');
    }

    /**
     * Set Country of Manufacture attribute
     *
     * @param $country
     * @return RequiredData
     */
    public function setCountryOfManufacture($country)
    {
        $this->set('country_of_manufacture',$country);

        return $this;
    }

    /**
     * Sets the price
     *
     * @param float $price
     *
     * @return RequiredData
     */
    public function setPrice($price)
    {
        $this->set('price', (float)$price);

        return $this;
    }

    /**
     * Gets the price
     * @return float|null
     */
    public function getPrice()
    {
        return $this->get('price');
    }

    /**
     * Sets the Special Price
     *
     * @param float $price
     *
     * @return RequiredData
     */
    public function setSpecialPrice($price)
    {
        if ($price != '' || $price !== null)
            $price = (float) $price;

        $this->set('special_price', $price);

        return $this;
    }

    /**
     * Gets the special price
     * @return float|null
     */
    public function getSpecialPrice()
    {
        return $this->get('special_price');
    }

    /**
     * Sets the special price from date
     *
     * @param int $date
     *
     * @return RequiredData
     */
    public function setSpecialFromDate($date)
    {
        if ($date != '' || $date !== null)
            $date = (int) $date;

        $this->set('special_from_date', $date);

        return $this;
    }

    /**
     * Gets the special price from date
     * @return int|null
     */
    public function getSpecialFromDate()
    {
        return $this->get('special_from_date');
    }

    /**
     * Set the tax ID (Magento ID)
     *
     * @param string $tax
     *
     * @return RequiredData
     */
    public function setTax($tax)
    {
        $this->set('tax_class_id', $tax);

        return $this;
    }

    /**
     * Get the tax ID
     * @return int|null
     */
    public function getTax()
    {
        return $this->get('tax_class_id');
    }

    /**
     * Set the quantity
     * * null - quantity should not be managed by Magento
     * * integer - The product is managed
     * * <=0 - the product is not in stock)
     *
     * @param null|int $qty
     * @param boolean $alwaysInStock : Should be set to TRUE if you want backorders
     *
     * @return RequiredData
     */
    public function setQty($qty, $alwaysInStock = false)
    {
        if ($qty === null) {
            $this->set('use_config_manage_stock', 0);
            $this->set('manage_stock', 0);
            $this->set('is_in_stock', 1);
            $this->set('qty', (int)0);
            return $this;
        }

        $this->set('manage_stock', 1);
        if ($qty <= 0) {
            $this->set('is_in_stock', 0);
        } else {
            $this->set('is_in_stock', 1);
        }

        if ($alwaysInStock) {
            $this->set('is_in_stock', 1);
        }

        $this->set('qty', (int)$qty);

        return $this;
    }

    /**
     * Get the quantity
     * @return int|null
     */
    public function getQty()
    {
        return $this->get('qty');
    }

    /**
     * Sets Qty for Item's Status to Become Out of Stock
     *
     * @param null $qty
     * @return $this
     */
    public function setMinQtyForOutOfStockThreshold($qty = null)
    {
        if ($qty === null)
        {
            $this->set('min_qty',0);
            $this->set('use_config_min_qty',1);
        }
        else
        {
            $this->set('min_qty',$qty);
            $this->set('use_config_min_qty',0);
        }

        return $this;
    }

    /**
     * Get array of website ids
     *
     * @return array
     */
    public function getUrlKey()
    {
        return $this->get('url_key');
    }

    /**
     * Set website ids for product
     *
     * @param $urlKey
     * @return RequiredData
     */
    public function setUrlKey($urlKey)
    {
        $this->set('url_key',$urlKey);

        return $this;
    }

    /**
     * Get the Updated At time
     * @return int|null
     */
    public function getUpdatedAt()
    {
        return $this->get('updated_at');
    }

    /**
     * Sets the Update At attribute
     * @param int $time
     *
     * @return RequiredData
     */
    public function setUpdatedAt($time)
    {
        $this->set('updated_at',(int) $time);

        return $this;
    }

    /**
     * Get array of website ids
     *
     * @return array
     */
    public function getWebsiteIds()
    {
        return $this->get('website_ids');
    }

    /**
     * Set website ids for product
     *
     * @param $ids
     * @return RequiredData
     */
    public function setWebsiteIds($ids)
    {
        $this->set('website_ids',$ids);

        return $this;
    }

    /**
     * Get the required data array for the product
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
