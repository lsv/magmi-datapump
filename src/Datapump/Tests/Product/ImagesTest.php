<?php
/**
 * Created by lsv
 * Date: 9/3/13
 * Time: 4:48 PM
 */

namespace Datapump\Tests;

use Datapump\Product\Data\Images;
use Datapump\Product\Data\RequiredData;
use Datapump\Product\Simple;

class ImagesTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Datapump\Product\Data\RequiredData
     */
    private $simpleRequiredData;

    /**
     * @var \Datapump\Product\Data\RequiredData
     */
    private $configRequiredData;

    public function __construct()
    {
        $this->simpleRequiredData = new RequiredData();
        $this->simpleRequiredData->setSku('sku')
            ->setName('name')
            ->setPrice(100)
            ->setQty(100)
            ->setShortDescription('short description')
            ->setDescription('long description')
            ->setTax(1)
            ->setWeight(100);

        $this->configRequiredData = new RequiredData();
        $this->configRequiredData->setSku('sku')
            ->setName('name')
            ->setShortDescription('short description')
            ->setDescription('long description');
    }

    public function test_canAddImage()
    {
        $product = new Simple(clone $this->simpleRequiredData);

        $image = new Images();
        $image->setBaseImage('base.jpg', 'base');
        $image->setThumbnail('thumbnail.jpg', 'thumbnail');
        $image->setSmallImage('small.jpg', 'small');

        $product->injectData($image);
        $data = $product->getData();

        $this->assertEquals('+base.jpg', $data['image']);
        $this->assertEquals('base', $data['image_label']);

        $this->assertEquals('thumbnail.jpg', $data['thumbnail']);
        $this->assertEquals('thumbnail', $data['thumbnail_label']);

        $this->assertEquals('small.jpg', $data['small_image']);
        $this->assertEquals('small', $data['small_image_label']);
    }

    public function test_canAddImageButNotToGallery()
    {
        $product = new Simple(clone $this->simpleRequiredData);

        $image = new Images();
        $image->setBaseImage('base.jpg', 'base', false);

        $product->injectData($image);
        $data = $product->getData();

        $this->assertEquals('-base.jpg', $data['image']);
        $this->assertEquals('base', $data['image_label']);
    }

    public function test_canAddImageWithoutLabels()
    {
        $product = new Simple(clone $this->simpleRequiredData);

        $image = new Images();
        $image->setBaseImage('base.jpg');
        $image->setThumbnail('thumbnail.jpg');
        $image->setSmallImage('small.jpg');

        $product->injectData($image);
        $data = $product->getData();

        $this->assertEquals('+base.jpg', $data['image']);
        $this->assertFalse(isset($data['image_label']));

        $this->assertEquals('thumbnail.jpg', $data['thumbnail']);
        $this->assertFalse(isset($data['thumbnail_label']));

        $this->assertEquals('small.jpg', $data['small_image']);
        $this->assertFalse(isset($data['small_image_label']));
    }

    public function test_canAddGallery()
    {
        $product = new Simple(clone $this->simpleRequiredData);

        $image = new Images();
        $image->addImageToGallery('gallery1.jpg', 'gallery1')
            ->addImageToGallery('gallery2.jpg', 'gallery2')
            ->addImageToGallery('gallery3.jpg', 'gallery3');

        $product->injectData($image);
        $data = $product->getData();

        $this->assertEquals('gallery1.jpg::gallery1;gallery2.jpg::gallery2;gallery3.jpg::gallery3', $data['gallery']);
    }
}