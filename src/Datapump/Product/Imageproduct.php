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

    /**
     * {@inheritdoc}
     */
    protected $requiredFields = array(
        'Type' => 'Missing product type',
        'Sku' => 'Missing SKU number',
    );

    /**
     * {@inheritdoc}
     */
    public function __construct(RequiredData $data)
    {
        parent::__construct($data);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeImport()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function afterImport()
    {

    }

}
