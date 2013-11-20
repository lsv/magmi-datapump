<?php
/**
 * Created by PhpStorm.
 * User: lsv
 * Date: 11/20/13
 * Time: 8:04 AM
 */

namespace Datapump\Tests;


use Datapump\Product\Data\RequiredData;
use Datapump\Product\Imageproduct;

class ImageProductTest extends \PHPUnit_Framework_TestCase
{

    public function test_canMakeImageProduct()
    {

        $req = new RequiredData();
        $req->setType(RequiredData::TYPE_SIMPLE)
            ->setSku('foobar');

        $product = new Imageproduct($req);

    }



} 