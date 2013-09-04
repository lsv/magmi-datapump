<?php
/**
 * @author Martin Aarhof <martin.aarhof@gmail.com>

 * @version GIT: $Id$
 */
namespace Datapump\Product\Data;

/**
 * Class Images
 * @package Datapump\Product\Data
 */
class Images extends DataAbstract
{

    /**
     * Set required Magmi plugins
     * @var array
     */
    protected $requiredMagmiPlugin = array('Image attributes processor');

    /**
     * Sets the base image
     * @param $imagefile
     * @param string $label
     * @param bool $addToGallery
     *
     * @return $this
     */
    public function setBaseImage($imagefile, $label = '', $addToGallery = true)
    {
        $this->set('image', ($addToGallery ? '+' : '-') . $imagefile);
        if ($label) {
            $this->set('image_label', $label);
        }

        return $this;
    }

    /**
     * Sets the small image
     * @param string $imagefile
     * @param string $label
     *
     * @return $this
     */
    public function setSmallImage($imagefile, $label = '')
    {
        $this->set('small_image', $imagefile);
        if ($label) {
            $this->set('small_image_label', $label);
        }

        return $this;
    }

    /**
     * Sets the thumbnail
     * @param string $imagefile
     * @param string $label
     *
     * @return $this
     */
    public function setThumbnail($imagefile, $label = '')
    {
        $this->set('thumbnail', $imagefile);
        if ($label) {
            $this->set('thumbnail_label', $label);
        }

        return $this;
    }

    /**
     * Adding image to gallery
     * @param string $imagefile
     * @param string $label
     *
     * @return $this
     */
    public function addImageToGallery($imagefile, $label = '')
    {
        $this->data['gallery'][] = array('img' => $imagefile, 'label' => $label);

        return $this;
    }

    /**
     * Get the image array
     * @return array
     */
    public function getData()
    {
        if ($this->__isset('gallery')) {
            $gallery = array();
            $galleryimages = $this->get('gallery');
            $this->set('gallery', '');
            foreach ($galleryimages as $img) {
                if (isset($img['img'])) {
                    $gallery[] = $img['img'] . (isset($img['label']) ? '::' . $img['label'] : '');
                }
            }
            $this->set('gallery', implode(';', $gallery));
        }

        return $this->data;
    }
}
