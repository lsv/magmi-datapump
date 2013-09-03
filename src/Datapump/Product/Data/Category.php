<?php
/**
 * Created by lsv
 * Date: 8/30/13
 * Time: 1:02 PM
 */

namespace Datapump\Product\Data;

class Category extends DataAbstract
{

    protected $requiredMagmiPlugin = array('On the fly category creator/importer');

    public function set($category, $is_active = true, $is_anchor = true, $include_in_menu = true, $levelDemiliter = '/')
    {
        if (!is_array($category)) {
            $category = array($category);
        }

        foreach ($category as $cat) {
            if ($levelDemiliter != '/') {
                $cat = str_replace($levelDemiliter, '/', $cat);
            }

            $this->data['categories'][] = $this->addCategory($cat, $is_active, $is_anchor, $include_in_menu);
        }

        return $this;
    }

    private function addCategory($category, $is_active, $is_anchor, $include_in_menu)
    {
        return array(
            'category' => $category,
            'is_active' => ($is_active ? 1 : 0),
            'is_anchor' => ($is_anchor ? 1 : 0),
            'include_in_menu' => ($include_in_menu ? 1 : 0)
        );
    }

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
