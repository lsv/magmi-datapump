<?php
/**
 * Created by PhpStorm.
 * User: lsv
 * Date: 9/12/13
 * Time: 11:08 AM
 */

namespace Datapump\Tests;


class Booter extends \PHPUnit_Framework_TestCase
{

    static private $pdo = null;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \PDO
     */
    static public function getDatabase()
    {
        if (self::$pdo === null) {
            self::$pdo = new \PDO(sprintf('mysql:host=%s;dbname=%s', 'localhost', 'magento_travis'), 'travis', 'travis',
                array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8')
            );
        }

        return self::$pdo;
    }

    static public function initializeDatabase()
    {
        $q = self::getDatabase()->prepare(file_get_contents(__DIR__ . '/../../../.travis/magento/magento.sql'));
        $q->execute();
    }

} 