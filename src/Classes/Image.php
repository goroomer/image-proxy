<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 03/12/16
 * Time: 09:41
 */

use Intervention\Image\ImageManager;

class Image {

	private $source_url;
	private $width;
	private $height;
	private $mime;
	private $content;
	private $manipulator;
	private $procesing_image;
	private $driver;
	public  $errors = [];

	public function __construct($params) {
		global $driver;
		$this->driver     = $driver;
		@$this->source_url= $params['source_url'];
		@$this->width     = $params['width'];
		@$this->height    = $params['height'];
		@$this->mime      = $params['mime'];
		$this->content    = $params['content'];
	}


	/**
	 * @return string
	 */
	public function getSourceUrl() {
		return $this->source_url;
	}

	/**
	 * @return float
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * @return float
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * @return string
	 */
	public function getMime() {
		if($this->mime != null){
			return $this->mime;
		}
		try{
			$this->mime = $this->process_image()->mime();
			return $this->mime;
		}catch(\Exception $e){
			array_merge($this->errors,[$e->getMessage()]);
		}
	}

	/**
	 * @return mixed
	 */
	public function getContent() {
		return $this->content;
	}

	public function process_image(): \Intervention\Image\Image {
		global $driver;
		if($this->manipulator != null){
			return $this->manipulator;
		}
		$this->manipulator = new  ImageManager(array('driver' => $driver));
		$this->manipulator = $this->manipulator->make($this->content);
		return $this->manipulator;
	}

}