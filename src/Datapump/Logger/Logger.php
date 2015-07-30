<?php
/**
 * @author Martin Aarhof <martin.aarhof@gmail.com>
 * @version GIT: $Id$
 */
namespace Datapump\Logger;

/**
 * Class Logger
 * @package Datapump\Logger
 */
interface Logger
{

    const WARNING = '---WARNING---';

    /**
     * Log data
     *
     * @param string $data
     * @param string $type
     *
     * @return Logger
     */
    public function log($data, $type);
}
