<?php
/**
 * @author Martin Aarhof <martin.aarhof@gmail.com>
 * @copyright Copyright (c) 2013, Martin Aarhof
 * @version 0.2
 */

namespace Datapump\Product\Data;

/**
 * Class DataInterface
 * @package Datapump\Product\Data
 */
interface DataInterface
{

    const VISIBILITY_CATALOG_SEARCH = 4;

    const VISIBILITY_CATALOG = 2;

    const VISIBILITY_SEARCH = 3;

    const VISIBILITY_NOTVISIBLE = 1;

    const TYPE_CONFIGURABLE = 'configurable';

    const TYPE_SIMPLE = 'simple';

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return DataAbstract
     */
    public function set($key, $value);

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * @return array
     */
    public function getData();
}
