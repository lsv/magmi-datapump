<?php
/**
 * Created by lsv
 * Date: 8/29/13
 * Time: 2:38 AM
 */

namespace Datapump\Product;


class Simple extends ProductAbstract
{

	protected $type = self::TYPE_SIMPLE;

	protected $requiredData = array(
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

	public function __construct($sku, $visibleInFrontend = true, $searchable = true)
	{
		parent::__construct($sku);
		$this->setVisibility($visibleInFrontend, $searchable);
	}

}