<?php
/**
 * Created by lsv
 * Date: 8/29/13
 * Time: 2:32 AM
 */

namespace Datapump\Product;

use Datapump\Exception\MissingGetter;
use Datapump\Product\Setters\ProductData;

abstract class ProductAbstract
	extends ProductData
{

	const VISIBILITY_CATALOG_SEARCH = 4;
	const VISIBILITY_CATALOG = 2;
	const VISIBILITY_SEARCH = 3;
	const VISIBILITY_NOTVISIBLE = 1;

	const TYPE_CONFIGURABLE = 'configurable';
	const TYPE_SIMPLE = 'simple';

	protected $data = array();

	protected $type = '';

	protected $requiredData = array();

	public function __construct($sku)
	{
		$this->setSku($sku)
			->setType($this->type)
			->setEnabled();
	}

	protected function checkRequired()
	{
		return true;
	}

	public function beforeAddingToHolder()
	{

	}

	public function checkMissingData()
	{
		$errors = array();
		foreach($this->requiredData AS $key => $msg) {
			$method = 'get' . $key;
			if (method_exists($this, $method)) {
				if ($this->$method() === null) {
					$errors[] = $msg;
				}
			} else {
				throw new MissingGetter('Could not find the data getter for ' . $key . ' (should be: ' . $method . ')');
			}
		}

		$required = $this->checkRequired();
		if ($required && is_array($required)) {
			$errors = array_merge($errors, $required);
		}

		if ($errors) {
			return $errors;
		}

		return true;
	}

	public function debug()
	{
		/** OB is used for Tests */
		ob_start();
		var_dump($this->beforeImport());
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	public function beforeImport()
	{
		$this->beforeImportCategories();
		return $this->data;
	}

	public function afterImport()
	{

	}

	private function beforeImportCategories()
	{
		if ($this->_isset('categories')) {
			$this->data['categories'] = implode(';;', $this->getCategory());
		}
	}

}