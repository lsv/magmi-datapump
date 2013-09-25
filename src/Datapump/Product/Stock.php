<?php
/**
 * @author Martin Aarhof <martin.aarhof@gmail.com>
 * @version GIT: $Id$
 */
namespace Datapump\Product;

use Datapump\Product\Data\DataInterface;
use Datapump\Product\Data\RequiredData;

/**
 * Class Stock
 * @package Datapump\Product
 */
class Stock extends ProductAbstract
{

    /**
     * Product type
     * @var string
     */
    protected $type = DataInterface::TYPE_SIMPLE;

    /**
     * Required data for our stock change product
     * @var array
     */
    protected $requiredFields = array(
        'Type' => 'Missing product type',
        'Sku' => 'Missing SKU number',
        'Qty' => 'Missing product quantity',
    );

    public function beforeImport()
    {
    }

    public function afterImport()
    {
    }
}
