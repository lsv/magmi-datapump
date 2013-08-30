<?php
/**
 * Created by lsv
 * Date: 8/29/13
 * Time: 6:47 PM
 */

namespace Datapump\Product\Setters;


class Images
	implements SetterInterface
{

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

}