<?php
/**
 * @author Martin Aarhof <martin.aarhof@gmail.com>
 * @version GIT: $Id$
 */
namespace Datapump\Product\Data;

/**
 * Class Category
 * @package Datapump\Product\Data
 */
class Category extends DataAbstract
{

    const DEFAULT_CATEGORY_SEPARATOR = '/';

    /**
     * Required Magmi plugins
     * @var array
     */
    protected $requiredMagmiPlugin = array('On the fly category creator/importer');

    /**
     * Add a category to the product
     *
     * @param string $category Category name
     * @param bool $is_active Is the category active?
     * @param bool $is_anchor Is the category a anchor?
     * @param bool $include_in_menu Should the category be included in menu?
     * @param string $levelDemiliter Which string are you using for demiliter between the levels of the categories?
     *
     * @return $this|DataAbstract
     */
    public function set($category, $is_active = true, $is_anchor = true, $include_in_menu = true, $levelDemiliter = self::DEFAULT_CATEGORY_SEPARATOR)
    {
        if (!is_array($category)) {
            $categories = array($category);
        } else {
            $categories = $category;
        }

        foreach ($categories as &$category) {
            if (strpos($category, $levelDemiliter) !== false) {
                $levels = explode($levelDemiliter, $category);
            } else {
                $levels = array($category);
            }

            foreach($levels as &$level) {
                if (strpos($level, self::DEFAULT_CATEGORY_SEPARATOR) !== false) {
                    $level = str_replace(self::DEFAULT_CATEGORY_SEPARATOR, '\\' . self::DEFAULT_CATEGORY_SEPARATOR, $level);
                }
            }

            $category = implode(self::DEFAULT_CATEGORY_SEPARATOR, $levels);
            $this->data['categories'][] = $this->addCategory($category, $is_active, $is_anchor, $include_in_menu);
        }

        return $this;
    }

    /**
     * Returns the category array
     *
     * @param string $category Category name
     * @param bool $is_active Is the category active?
     * @param bool $is_anchor Is the category a anchor?
     * @param bool $include_in_menu Should the category be included in menu?
     *
     * @return array
     */
    private function addCategory($category, $is_active, $is_anchor, $include_in_menu)
    {
        return array(
            'category' => $category,
            'is_active' => ($is_active ? 1 : 0),
            'is_anchor' => ($is_anchor ? 1 : 0),
            'include_in_menu' => ($include_in_menu ? 1 : 0)
        );
    }

    /**
     * Get the category data
     * @return array
     */
    public function getData()
    {
        if ($this->__isset('categories')) {
            $categories = array();
            foreach ($this->get('categories') as $category) {
                $categories[] = sprintf(
                    '%s::%s::%s::%s',
                    $category['category'],
                    $category['is_active'],
                    $category['is_anchor'],
                    $category['include_in_menu']
                );
            }
            $data = implode(';;', $categories);

            return array('categories' => $data);
        }

        return array();
    }
}
