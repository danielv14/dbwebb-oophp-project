<?php
/**
 * A class that resize and crop images on the fly, store generated images in a cache.
 *
 */
 
class CImage {


 // Object constants
    private $pathToImage; //Actual path to the image, $imageFolder . '/' . $imageSrc
    private $cacheDir;
    private $imageDir;
    private $maxWidth;
    private $maxHeight;
    
    // Image parameters
    private $src            = null; // Image filename, may include subdirectory
    private $verbose        = null; // Print out a trace and display the created image
    private $saveAs         = null; // Set a format to save image as, or null to use original format.
    private $quality        = 60;   // Quality level for JPEG images.
    private $ignoreCache    = null; // boolean, to used cached images or not
    private $newWidth       = null; // New image dimensions, incoming as argument or calculated.
    private $newHeight      = null; // New image dimensions, incoming as argument or calculated.
    private $cropToFit      = null;
    private $sharpen        = null;
    
    private $fileExtensions = array('jpeg', 'jpg', 'png'); // Original file extension
    
    private $verboseLog; // String, contains image info in verbose mode
    
    // Orinal image info
    private $filesize; // Original filesize
    private $height; // Orginal image height
    private $width; // Original image width
    
    // Contructor
    public function __construct($settings) {
        $this->cacheDir   = $settings['cacheDir'];
        $this->imageDir   = $settings['imageDir'];
        $this->maxWidth = isset($settings['maxWidth']) ? $settings['maxWidth'] : 2000;
        $this->maxHeight = isset($settings['maxHeight']) ? $settings['maxHeight'] : 2000;
    }

  // Set functions for all the image parameters
    public function setSrc($param) {
        $this->src = $param; 
    }
    
    public function setVerbose($param) {
        $this->verbose = $param;
    }
    
    public function setSaveAs($param) {
        $this->saveAs = $param;
    }
    
    public function setQuality($param) {
        $this->quality = $param;
    }
    
    public function setIgnoreCache($param) {
        $this->ignoreCache = $param;
    }
    
    public function setNewWidth($param) {
        $this->newWidth = $param;
    }
    
    public function setNewHeight($param) {
        $this->newHeight = $param;
    }
    
    public function setCropToFit ($param) {
        $this->cropToFit = $param;
    }
    
    public function setSharpen($param) {
        $this->sharpen = $param;
    }
    

    /**
     * Display image
     *
     */
    public function displayImage() {
        // Create path to image file
        $this->pathToImage = realpath($this->imageDir . $this->src);
        !isset($this->verbose) or $this->createVerboseLog();

        // Validate incoming arguments
        $this->validate();

        // Get image info
        $this->getInfo();

        // Open up original image from file
        $image = $this->openOriginalImage();

        // Resize and apply filters if requested
        if (isset($this->cropToFit) || isset($this->newHeight) || isset($this->newWidth)) {
            $image = $this->resize($this->width, $this->height, $image);
        }

        // Create cache filename
        $cacheFileName = $this->createCache();

        // Check if file already exists, if so output image
        $this->checkForCache($cacheFileName);

        // Save file
        $this->saveImage($cacheFileName, $image);

        // Output image
        $this->outputImage($cacheFileName);
    }        
    
    
    /**
     * Display error message.
     *
     * @param string $message the error message to display.
     */
    private function errorMessage($message) {
        header("Status: 404 Not Found");
        die('img.php says 404 - ' . htmlentities($message));
    }

    /**
     * Display log message.
     *
     * @param string $message the log message to display.
     */
    private function verbose($message) {
        $this->verboseLog .= "<p>" . htmlentities($message) . "</p>";
    }

    
    
    
   /**
    * Output an image together with last modified header.
    *
    * @param string $file as path to the image.
    * @param boolean $verbose if verbose mode is on or off.
    */
    private function outputImage($file) {
       $info = getimagesize($file);
       !empty($info) or $this->errorMessage("The file doesn't seem to be an image.");
       $mime   = $info['mime'];

       $lastModified = filemtime($file);  
       $gmdate = gmdate("D, d M Y H:i:s", $lastModified);

       if($this->verbose) {
         $this->verbose("Memory peak: " . round(memory_get_peak_usage() /1024/1024) . "M");
         $this->verbose("Memory limit: " . ini_get('memory_limit'));
         $this->verbose("Time is {$gmdate} GMT.");
       }

       if(!$this->verbose) header('Last-Modified: ' . $gmdate . ' GMT');
       if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $lastModified){
         if($this->verbose) { verbose("Would send header 304 Not Modified, but its verbose mode."); exit; }
         header('HTTP/1.0 304 Not Modified');
       } else {  
         if($this->verbose) { verbose("Would send header to deliver image with modified time: {$gmdate} GMT, but its verbose mode."); exit; }
         header('Content-type: ' . $mime);  
         readfile($file);
       }
       exit;
    }

   /**
    * Sharpen image as http://php.net/manual/en/ref.image.php#56144
    * http://loriweb.pair.com/8udf-sharpen.html
    *
    * @param resource $image the image to apply this filter on.
    * @return resource $image as the processed image.
    */
    private function sharpenImage($image) {
       $matrix = array(
         array(-1,-1,-1,),
         array(-1,16,-1,),
         array(-1,-1,-1,)
       );
       $divisor = 8;
       $offset = 0;
       imageconvolution($image, $matrix, $divisor, $offset);
       return $image;
    }

   /**
    * Create new image and keep transparency
    *
    * @param resource $image the image to apply this filter on.
    * @return resource $image as the processed image.
    */
    private function createImageKeepTransparency($width, $height) {
       $img = imagecreatetruecolor($this->width, $this->height);
       imagealphablending($img, false);
       imagesavealpha($img, true);  
       return $img;
    }
    
    
    //
    // Validate incoming arguments
    //
    private function validate() {
        is_dir($this->imageDir) or $this->errorMessage('The image dir is not a valid directory.');
        is_writable($this->cacheDir) or $this->errorMessage('The cache dir is not a writable directory.');
        isset($this->src) or $this->errorMessage('Must set src-attribute.');
        preg_match('#^[a-z0-9A-Z-_\.\/]+$#', $this->src) or $this->errorMessage('Filename contains invalid characters.');
        substr_compare($this->imageDir, $this->pathToImage, 0, strlen($this->imageDir)) == 0 or $this->errorMessage('Security constraint: Source image is not directly below the directory IMG_PATH.');
        is_null($this->saveAs) or in_array($this->saveAs, array('png', 'jpg', 'jpeg')) or $this->errorMessage('Not a valid extension to save image as');
        is_null($this->quality) or (is_numeric($this->quality) and $this->quality > 0 and $this->quality <= 100) or $this->errorMessage('Quality out of range');
        is_null($this->newWidth) or (is_numeric($this->newWidth) and $this->newWidth > 0 and $this->newWidth <= $this->maxWidth) or $this->errorMessage('Width out of range');
        is_null($this->newHeight) or (is_numeric($this->newHeight) and $this->newHeight > 0 and $this->newHeight <= $this->maxHeight) or $this->errorMessage('Height out of range');
        is_null($this->cropToFit) or ($this->cropToFit and $this->newWidth and $this->newHeight) or $this->errorMessage('Crop to fit needs both width and height to work');
    }

    //
    // Start displaying log if verbose mode & create url to current image
    //
 private function createVerboseLog() {
        if($this->verbose) {
        $query = array();
        parse_str($_SERVER['QUERY_STRING'], $query);
        unset($query['verbose']);
        $url = '?' . http_build_query($query);
      echo <<<EOD
        <html lang='en'>
        <meta charset='UTF-8'/>
        <title>img.php verbose mode</title>
        <h1>Verbose mode</h1>
        <p><a href=$url><code>$url</code></a><br>
        <img src='{$url}' /></p>
EOD;
}
    }

    //
    // Get information on the image
    //
    private function getInfo() {
        $imgInfo = list($this->width, $this->height, $type, $attr) = getimagesize($this->pathToImage);
        !empty($imgInfo) or $this->errorMessage("The file doesn't seem to be an image.");
        $mime = $imgInfo['mime'];

        if($this->verbose) {
            $this->filesize = filesize($this->pathToImage);
            $this->verbose("Image file: {$this->pathToImage}");
            $this->verbose("Image information: " . print_r($imgInfo, true));
            $this->verbose("Image width x height (type): {$this->width} x {$this->height} ({$type}).");
            $this->verbose("Image file size: {$filesize} bytes.");
            $this->verbose("Image mime type: {$mime}.");
        }
    }

      //
      // Creating a filename for the cache
      //
        private function createCache() {
        $parts          = pathinfo($this->pathToImage);
        $fileExtension  = $parts['extension'];
        $this->saveAs   = is_null($this->saveAs) ? $fileExtension : $this->saveAs;
        $quality_       = is_null($this->quality) ? null : "_q{$this->quality}";
        $cropToFit_     = is_null($this->cropToFit) ? null : "_cf";
        $sharpen_       = is_null($this->sharpen) ? null : "_s";
        $dirName        = preg_replace('/\//', '-', dirname($this->src));
        $cacheFileName = $this->cacheDir . "-{$dirName}-{$parts['filename']}_{$this->newWidth}_{$this->newHeight}{$quality_}{$cropToFit_}{$sharpen_}.{$this->saveAs}";
        $cacheFileName = preg_replace('/^a-zA-Z0-9\.-_/', '', $cacheFileName);

        if($this->verbose) { $this->verbose("Cache file is: {$cacheFileName}"); }
        return $cacheFileName;
        }
    
    //   
    // Is there already a valid image in the cache directory, then use it and exit
    //
    private function checkForCache($cacheFileName) {
        $imageModifiedTime = filemtime($this->pathToImage);
        $cacheModifiedTime = is_file($cacheFileName) ? filemtime($cacheFileName) : null;

        // If cached image is valid, output it.
        if(!$this->ignoreCache && is_file($cacheFileName) && $imageModifiedTime < $cacheModifiedTime) {
          if($this->verbose) { verbose("Cache file is valid, output it."); }
          $this->outputImage($cacheFileName, $this->verbose);
        }

        if($this->verbose) { $this->verbose("Cache is not valid, process image and create a cached version of it."); }
    }
        
    //
    // Open up the original image from file
    //
    private function openOriginalImage() {
        $parts          = pathinfo($this->pathToImage);
        $fileExtension  = $parts['extension'];

        switch($fileExtension) {  
          case 'jpg':
          case 'jpeg': 
            $image = imagecreatefromjpeg($this->pathToImage);
            if($this->verbose) { $this->verbose("Opened the image as a JPEG image."); }
            break;  

          case 'png':  
            $image = imagecreatefrompng($this->pathToImage); 
            if($this->verbose) { $this->verbose("Opened the image as a PNG image."); }
            break;  

          default: errorPage('No support for this file extension.');
        }
        return $image;
    }    
  
    
        //
        // Calculate new width and height for the image
        //
        private function resize($width, $height, $image) {
    
        
        $aspectRatio = $width / $height;

        if($this->cropToFit && $this->newWidth && $this->newHeight) {
          $targetRatio = $this->newWidth / $this->newHeight;
          $cropWidth   = $targetRatio > $aspectRatio ? $width : round($height * $targetRatio);
          $cropHeight  = $targetRatio > $aspectRatio ? round($width  / $targetRatio) : $height;
          if($this->verbose) { $this->verbose("Crop to fit into box of {$newWidth}x{$newHeight}. Cropping dimensions: {$cropWidth}x{$cropHeight}."); }
        }
        else if($this->newWidth && !$this->newHeight) {
          $this->newHeight = round($this->newWidth / $aspectRatio);
          if($this->verbose) { $this->verbose("New width is known {$newWidth}, height is calculated to {$newHeight}."); }
        }
        else if(!$this->newWidth && $this->newHeight) {
          $this->newWidth = round($this->newHeight * $aspectRatio);
          if($this->verbose) { $this->verbose("New height is known {$this->newHeight}, width is calculated to {$this->newWidth}."); }
        }
        else if($this->newWidth && $this->newHeight) {
          $ratioWidth  = $width  / $this->newWidth;
          $ratioHeight = $height / $this->newHeight;
          $ratio = ($ratioWidth > $ratioHeight) ? $ratioWidth : $ratioHeight;
          $this->newWidth  = round($width  / $ratio);
          $this->newHeight = round($height / $ratio);
          if($this->verbose) { $this->verbose("New width & height is requested, keeping aspect ratio results in {$newWidth}x{$newHeight}."); }
        }
        else {
          $this->newWidth = $width;
          $this->newHeight = $height;
          if($this->verbose) { $this->verbose("Keeping original width & heigth."); }
        }

         // Resize the image if needed
        if($this->cropToFit) {
          if($this->verbose) { $this->verbose("Resizing, crop to fit."); }
          $cropX = round(($width - $cropWidth) / 2);  
          $cropY = round(($height - $cropHeight) / 2);    
          $imageResized = imagecreatetruecolor($this->newWidth, $this->newHeight);
          imagecopyresampled($imageResized, $image, 0, 0, $cropX, $cropY, $this->newWidth, $this->newHeight, $cropWidth, $cropHeight);
          $image = $imageResized;
          $width = $this->newWidth;
          $height = $this->newHeight;
        }
        else if(!($this->newWidth == $width && $this->newHeight == $height)) {
          if($this->verbose) { $this->verbose("Resizing, new height and/or width."); }
          $imageResized = imagecreatetruecolor($this->newWidth, $this->newHeight);
          imagecopyresampled($imageResized, $image, 0, 0, 0, 0, $this->newWidth, $this->newHeight, $width, $height);
          $image  = $imageResized;
          $width  = $this->newWidth;
          $height = $this->newHeight;
        }
        return $image;
    }
        
        //
        //Save the image
        //
        private function saveImage($cacheFileName, $image) {
            switch($this->saveAs) {
              case 'jpeg':
              case 'jpg':
                if($this->verbose) { $this->verbose("Saving image as JPEG to cache using quality = {$this->quality}."); }
                imagejpeg($image, $cacheFileName, $this->quality);
              break;  

              case 'png':  
                if($this->verbose) { $this->verbose("Saving image as PNG to cache."); }
                imagepng($image, $cacheFileName);  
              break;  

              default:
                $this->errorMessage('No support to save as this file extension.');
              break;
            }

            if($this->verbose) { 
              clearstatcache();
              $cacheFilesize = filesize($cacheFileName);
              $this->verbose("File size of cached file: {$cacheFilesize} bytes."); 
              $this->verbose("Cache file has a file size of " . round($cacheFilesize/$this->filesize*100) . "% of the original size.");
            }


        }
    
    
} 
