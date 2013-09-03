<?php
/**
 * Created by lsv
 * Date: 8/29/13
 * Time: 2:38 AM
 */

namespace Datapump\Product;

use Datapump\Product\Data\DataInterface;
use Datapump\Product\Data\RequiredData;

class Simple extends ProductAbstract
{

	protected $type = DataInterface::TYPE_SIMPLE;

	protected $requiredFields = array(
		'Type'				=> 'Missing product type',
		'Sku' 				=> 'Missing SKU number',
		'Visibility'		=> 'Missing visibility status',
		'Description' 		=> 'Missing description',
		'ShortDescription' 	=> 'Missing short description',
		'Name'				=> 'Missing product name',
		'Weight'			=> 'Missing product weight',
		'Status'			=> 'Missing product status',
		'Price'				=> 'Missing product price',
		'Tax'				=> 'Missing tax class',
		'Qty'				=> 'Missing product quantity',
	);

	public function __construct(RequiredData $data)
	{
		parent::__construct($data);
	}

}