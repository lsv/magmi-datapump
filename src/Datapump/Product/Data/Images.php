<?php
/**
 * Created by lsv
 * Date: 8/30/13
 * Time: 1:03 PM
 */

namespace Datapump\Product\Data;

class Images extends DataAbstract
{

    protected $requiredMagmiPlugin = array('Image attributes processor');

    public function setBaseImage($imagefile, $label = '', $addToGallery = true)
    {
        $this->set('image', ($addToGallery ? '+' : '-') . $imagefile);
        if ($label) {
            $this->set('image_label', $label);
        }

        return $this;
    }

    public function setSmallImage($imagefile, $label = '')
    {
        $this->set('small_image', $imagefile);
        if ($label) {
            $this->set('small_image_label', $label);
        }

        return $this;
    }

    public function setThumbnail($imagefile, $label = '')
    {
        $this->set('thumbnail', $imagefile);
        if ($label) {
            $this->set('thumbnail_label', $label);
        }

        return $this;
    }

    public function addImageToGallery($imagefile, $label = '')
    {
        $this->data['gallery'][] = array('img' => $imagefile, 'label' => $label);

        return $this;
    }

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
