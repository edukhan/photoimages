<?php
/**
 * Banner image helper
 * 
 * @author Activated
 */

class Activated_Slider_Helper_Image extends
Mage_Core_Helper_Abstract
{
	/**
	 * Media path to extension images
	 * 
	 * @var string
	 */
	const MEDIA_PATH = 'slider';
	
	/**
	 * Maximum size for image in bytes
	 * Default value is 1M
	 * 
	 * @var int
	 */
	const MAX_FILE_SIZE = 1048576;
	
	/**
	 * Minimum image height in pixels
	 * 
	 * @var int
	 */
	const MIN_HEIGHT = 1;
	
	/**
	 * Maximum image height in pixels
	 * 
	 * @var int
	 */
	const MAX_HEIGHT = 5000;
	
	/**
	 * Minimum image width in pixels
	 * 
	 * @var int
	 */
	const MIN_WIDTH = 1;
	
	/**
	 * Maximum image width in pixels
	 * 
	 * @var int
	 */
	const MAX_WIDTH = 5000;
	
	/**
	 * Array of image size limitation
	 * 
	 * @var array
	 */
	protected $_imageSize = array(
		'minheight'		=> self::MIN_HEIGHT,
		'minwidth'		=> self::MIN_WIDTH,
		'maxheight'		=> self::MAX_HEIGHT,
		'maxwidth'		=> self::MAX_WIDTH
	);
	
	/**
	 * Array of allowed file extensions
	 * 
	 * @var array
	 */
	protected $_allowedExtensions = array('jpg', 'gif', 'png');
	
	/**
	 * Return the base media directory for banner items
	 * 
	 * @return string
	 */
	public function getBaseDir()
	{
		return Mage::getBaseDir('media') . DS . self::MEDIA_PATH;
	}
	
	/**
	 * Return the base url for banner slide images
	 * 
	 * @return string
	 */
	public function getBaseUrl()
	{
		return Mage::getBaseUrl('media') . '/' . self::MEDIA_PATH;
	}
	
	/**
	 * Remove banner slide image by image filename
	 * 
	 * @param string $imageFile
	 * @return bool
	 */
	public function removeImage($imageFile)
	{
		$io = new Varien_Io_File();
		$io->open(array('path' => $this->getBaseDir()));
		if ($io->fileExists($imageFile)) {
			return $io->rm($imageFile);
		}
		return false;
	}
	
	/**
	 * Upload image and return uploaded image file name or false
	 * 
	 * @throw Mage_Core_Exception
	 * @param string $scope the request key for file
	 * @return bool|string
	 */
	public function uploadImage($scope)
	{
		$adapter = new Zend_File_Transfer_Adapter_Http();
		$adapter->addValidator('ImageSize', true, $this->_imageSize);
		$adapter->addValidator('Size', true, self::MAX_FILE_SIZE);
		if ($adapter->isUploaded($scope)) {
			// validate image
			if (!$adapter->isValid($scope)) {
				Mage::throwException(Mage::helper('slider')->__('Uploaded image is not valid'));
			}
			$upload = new Varien_File_Uploader($scope);
			$upload->setAllowCreateFolders(true);
			$upload->setAllowedExtensions($this->_allowedExtensions);
			$upload->setAllowRenameFiles(true);
			$upload->setFilesDispersion(false);
			if ($upload->save($this->getBaseDir())) {
				return $upload->getUploadedFileName();
			}
		}
		return false;
	}
	
	/**
	 * Return URL for resized banner slide image
	 * 
	 * @param Activated_Slider_Model_Slides $item
	 * @param integer $width
	 * @param integer $height
	 * $return bool|string
	 */
	public function resize(Activated_Slider_Model_Banner $item, $width, $height = null)
	{
		if (!$item->getImage()) {
			Mage::log('getImage returned false in SliderController', null, 'image.log');
			return false;
		}
		
		if ($width < self::MIN_WIDTH || $width > self::MAX_WIDTH) {
			return false;
		}
		$width = (int)$width;
		
		if (!is_null($height)) {
			if ($height < self::MIN_HEIGHT || $height > self::MAX_HEIGHT) {
				return false;
			}
			$height = (int)$height;
		}
		
		$imageFile	= $item->getImage();
		$cacheDir	= $this->getBaseDir() . DS . 'cache' . DS . $width;
		$cacheUrl	= $this->getBaseUrl() . '/' . 'cache' . '/' . $width . '/';
		
		$io = new Varien_Io_File();
		$io->checkAndCreateFolder($cacheDir);
		$io->open(array('path' => $cacheDir));
		if ($io->fileExists($imageFile)) {
			return $cacheUrl . $imageFile;
		}
		
		try {
			$image = new Varien_Image($this->getBaseDir() . DS . $imageFile);
			//$image->resize($width, $height);
			$image->save($cacheDir . DS . $imageFile);
			return $cacheUrl . $imageFile;
		} catch (Exception $e) {
			Mage::logException($e);
			return false;
		}
	}
	
	/**
	* Removes folder with cached images
	* 
	* @return boolean
	*/
   public function flushImagesCache()
   {
	   $cacheDir	= $this->getBaseDir() . DS . 'cache' . DS;
	   $io = new Varien_Io_File();
	   if ($io->fileExists($cacheDir, false)) {
		   return $io->rmdir($cacheDir, true);
	   }
	   return true;
   }
}