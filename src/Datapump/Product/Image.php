<?php
/**
 * @author Martin Aarhof <martin.aarhof@gmail.com>
 * @version GIT: $Id$
 */
namespace Datapump\Product;

use Datapump\Product\Data\RequiredData;
use Datapump\Product\Data\DataInterface;

/**
 * Class ImageProduct
 * @package Datapump\Product
 */
class Imageproduct extends ProductAbstract
{

    /**
     * Product type
     * @var string
     */
    protected $type = DataInterface::TYPE_SIMPLE;

    protected $requiredFields = array(
        'Type' => 'Missing product type',
        'Sku' => 'Missing SKU number',
    );

    public function __construct(RequiredData $data)
    {
        parent::__construct($data);
    }

    public function beforeImport() {}
    public function afterImport() {}

}