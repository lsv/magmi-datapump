<?php
/**
 * @author Martin Aarhof <martin.aarhof@gmail.com>

 * @version GIT: $Id$
 */
namespace Datapump\Logger;

/**
 * Class Log
 * @package Datapump\Logger
 */
class Log implements Logger
{

    /**
     * Log data
     * @param string $data
     * @param string $type
     *
     * @return Logger
     */
    public function log($data, $type)
    {
        return $this;
    }
}
