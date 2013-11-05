<?php
/**
 * @author Martin Aarhof <martin.aarhof@gmail.com>
 * @version GIT: $Id$
 */
namespace Datapump\Product\Data;

/**
 * Class DataInterface
 * @package Datapump\Product\Data
 */
interface DataInterface
{

    /**
     * Visibility catalog and search
     */
    const VISIBILITY_CATALOG_SEARCH = 4;

    /**
     * Visibility catalog only
     */
    const VISIBILITY_CATALOG = 2;

    /**
     * Visibility search only
     */
    const VISIBILITY_SEARCH = 3;

    /**
     * Visibility not visible
     */
    const VISIBILITY_NOTVISIBLE = 1;

    /**
     * Magento type configurable
     */
    const TYPE_CONFIGURABLE = 'configurable';

    /**
     * Magento type simple
     */
    const TYPE_SIMPLE = 'simple';

    /**
     * Set a key to the product
     *
     * @param string $key
     * @param mixed $value
     *
     * @return DataAbstract
     */
    public function set($key, $value);

    /**
     * Get a key from the product
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);

}
