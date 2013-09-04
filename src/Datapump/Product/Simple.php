<?php
/**
 * @author Martin Aarhof <martin.aarhof@gmail.com>

 * @version GIT: $Id$
 */
namespace Datapump\Product;

use Datapump\Product\Data\DataInterface;
use Datapump\Product\Data\RequiredData;

/**
 * Class Simple
 * @package Datapump\Product
 */
class Simple extends ProductAbstract
{

    /**
     * Product type
     * @var string
     */
    protected $type = DataInterface::TYPE_SIMPLE;

    /**
     * Required data for our simple product
     * @var array
     */
    protected $requiredFields = array(
        'Type' => 'Missing product type',
        'Sku' => 'Missing SKU number',
        'Visibility' => 'Missing visibility status',
        'Description' => 'Missing description',
        'ShortDescription' => 'Missing short description',
        'Name' => 'Missing product name',
        'Weight' => 'Missing product weight',
        'Status' => 'Missing product status',
        'Price' => 'Missing product price',
        'Tax' => 'Missing tax class',
        'Qty' => 'Missing product quantity',
    );
}
