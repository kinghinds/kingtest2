<?php

/**
 * Image Class
 * 
 * @author     Seven <16seven@gmail.com> 
 * @link       
 * @copyright  
 * @license    
 * @version    0.1.2
 * @package    
 * @since       
 */

class Image {
	
	const RESIZE_AUTO = 0; // automatically resize in proportion
	const RESIZE_SPECIFY_WIDTH = 1; // specified width to resize
	const RESIZE_SPECIFY_HEIGHT = 2; // specified height to resize
	const RESIZE_CUT = 3; // specified width and height to resize
	
	protected $image;
	protected $type;
	protected $attr;
	protected $width;
	protected $height;
	protected $fileName;
	protected $fileExt;
	protected $backgroundColor = array(
			'red' => 255,
			'green' => 255,
			'blue' => 255
	);
	
	public function getAttr() {
		return $this->attr;
	}
	
	public function getWidth() {
		return $this->width;
	}
	
	public function getHeight() {
		return $this->height;
	}
	
	public function getFileName() {
		return $this->fileName;
	}
	
	public function getFileExt() {
		return $this->fileExt;
	}
	
	public function getBackgroundColor() {
		return $this->backgroundColor;
	}
	
	public function setBackgroundColor($red, $green, $blue) {
		// red, green, blue values ​​range between 1-255
		$red = self::range($red, 1, 255);
		$green = self::range($green, 1, 255);
		$blue = self::range($blue, 1, 255);
		
		$this->backgroundColor = array(
				'red' => $red,
				'green' => $green,
				'blue' => $blue
		);
	}
	
	public function __construct($filePath) {
		if (is_file($filePath) == false) {
			throw new Exception('Image file not found');
		}
		
		list($width, $height, $type, $attr) = getimagesize($filePath);
		if (empty($width) || empty($height) || empty($type)) {
			throw new Exception('Image does not recognize');
		}
		
		switch ($type) {
			case IMAGETYPE_GIF:
				$this->image = imagecreatefromgif($filePath);
				break;
			
			case IMAGETYPE_JPEG:
				$this->image = imagecreatefromjpeg($filePath);
				break;
			
			case IMAGETYPE_PNG:
				$this->image = imagecreatefrompng($filePath);
				break;
			
			default:
				throw new Exception('Image format does not support');
				break;
		}
		
		// attributes
		$this->width = $width;
		$this->height = $height;
		$this->type = $type;
		$this->attr = $attr;
		$info = pathinfo($filePath);
		if (isset($info['basename']))
			$this->fileName = $info['basename'];
		if (isset($info['extension']))
			$this->fileExt = $info['extension'];
	}
	
	public function resize($resizeWidth, $resizeHeight,
			$resizeMethod = self::RESIZE_AUTO) {
		
		switch ($resizeMethod) {
			case self::RESIZE_AUTO: {
					if ($resizeWidth <= 0 || $resizeHeight <= 0) {
						throw new Exception('Resize width and height must be larger than zero');
					}

					// thumbnail image size
					if ($this->width / $this->height > $resizeWidth / $resizeHeight) {
						if ($this->width > $resizeWidth) {
							$newWidth = $resizeWidth;
							$newHeight = round(
									$resizeWidth * $this->height / $this->width);
						} else {
							$newWidth = round(
									$resizeHeight * $this->width / $this->height);
							$newHeight = $resizeHeight;
						}
					} else {
						if ($this->height > $resizeHeight) {
							$newWidth = round(
									$resizeHeight * $this->width / $this->height);
							$newHeight = $resizeHeight;
						} else {
							$newWidth = $resizeWidth;
							$newHeight = round(
									$resizeWidth * $this->height / $this->width);							
						}
					}					

					// thumbnail image x, y location
					if ($resizeWidth == $newWidth) {
						$dstX = 0;
					} else {
						$dstX = max(0, round(($resizeWidth - $newWidth) / 2));
					}
					if ($resizeHeight == $newHeight) {
						$dstY = 0;
					} else {
						$dstY = max(0, round(($resizeHeight - $newHeight) / 2));
					}
					break;
				}
			
			case self::RESIZE_SPECIFY_WIDTH: {
					if ($resizeWidth <= 0) {
						throw new Exception('Resize width must be larger than zero');
					}

					// thumbnail image size
					$newWidth = $resizeWidth;
					$newHeight = round(
							$resizeWidth * $this->height / $this->width);
					$resizeHeight = $newHeight;
					
					// thumbnail image x, y location
					$dstX = 0;
					$dstY = 0;
					break;
				}
			
			case self::RESIZE_SPECIFY_HEIGHT: {
					if ($resizeHeight <= 0) {
						throw new Exception('Resize height must be larger than zero');
					}

					// thumbnail image size
					$newWidth = round(
							$resizeHeight * $this->width / $this->height);
					$newHeight = $resizeHeight;
					$resizeWidth = $newWidth;
					
					// thumbnail image x, y location
					$dstX = 0;
					$dstY = 0;
					break;
				}
			
			case self::RESIZE_CUT: {
					if ($resizeWidth <= 0 || $resizeHeight <= 0) {
						throw new Exception('Resize width and height must be larger than zero');
					}

					// thumbnail image size
					if ($this->width > $this->height) {
						$newWidth = round(
								$resizeHeight * $this->width / $this->height);
						$newHeight = $resizeHeight;
					} else {
						$newWidth = $resizeWidth;
						$newHeight = round(
								$resizeWidth * $this->height / $this->width);
					}
					
					// thumbnail image x, y location					
					if ($resizeWidth == $newWidth) {
						$dstX = 0;
					} else {
						$dstX = max(0, round(($resizeWidth - $newWidth) / 2));
					}
					if ($resizeHeight == $newHeight) {
						$dstY = 0;
					} else {
						$dstY = max(0, round(($resizeHeight - $newHeight) / 2));
					}
					break;
				}
			
			default: {
					throw new Exception('Unknow resize method');
				}
		}
		
		// setting true color
		$thumb = imagecreatetruecolor($resizeWidth, $resizeHeight);
		
		// setting background color
		$bgc = imagecolorallocate($thumb, $this->backgroundColor['red'],
				$this->backgroundColor['green'],
				$this->backgroundColor['blue']);
		imagefill($thumb, 0, 0, $bgc);
		
		// resize
		imagecopyresampled($thumb, $this->image, $dstX, $dstY, 0, 0, $newWidth,
				$newHeight, $this->width, $this->height);
		
		// blur
		// imagefilter($thumb, IMG_FILTER_SELECTIVE_BLUR);
		
		// reset attributes
		$this->image = $thumb;
		$this->width = $resizeWidth;
		$this->height = $resizeHeight;
		$this->attr = strtr(' height="{height}" width="{width}"',
				array(
						'{height}' => $this->height,
						'{width}' => $this->width
				));
		
		return $this;
	}
	
	public function save($filePath, $quality = 100) {
		$quality = self::range($quality, 1, 100);
		
		try {
			switch ($this->type) {
				case IMAGETYPE_GIF:
					imagegif($this->image, $filePath);
					break;
				case IMAGETYPE_JPEG:
					imagejpeg($this->image, $filePath, $quality);
					break;
				case IMAGETYPE_PNG:
					imagepng($this->image, $filePath);
					break;
			}
			imagedestroy($this->image);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	
	private function range($num, $minimum, $maximum) {
		return min($maximum, max($minimum, $num));
	}
}

?>