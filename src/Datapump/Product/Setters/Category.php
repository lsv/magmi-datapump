<?php
/**
 * Created by lsv
 * Date: 8/29/13
 * Time: 6:36 PM
 */

namespace Datapump\Product\Setters;


class Category
	extends Images
	implements SetterInterface
{

	public function setCategory($category, $addToAllLevels = false, $levelDemiliter = '/')
	{
		if ($this->_isset('categories')) {
			unset($this->data['categories']);
		}

		return $this->addCategory($category, $addToAllLevels, $levelDemiliter);
	}

	public function addCategory($category, $addToAllLevels = false, $levelDemiliter = '/')
	{
		if (!is_array($category)) {
			$category = array($category);
		}

		foreach($category AS $categories) {
			if ($levelDemiliter != '/') {
				$category = str_replace($levelDemiliter, '/', $categories);
			}

			if ($addToAllLevels && strpos($category, '/') !== false) {
				foreach(explode('/', $category) AS $cat) {
					$this->data['categories'][] = $cat;
				}
			} else {
				$this->data['categories'][] = $category;
			}
		}

		return $this;
	}

	public function getCategory()
	{
		return $this->data['categories'];
	}

}