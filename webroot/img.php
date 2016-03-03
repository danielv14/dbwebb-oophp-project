<?php
/** 
 * This is  a Miletus pagecontroller  
 * 
 */

//Include the config file which also creates the $miletus variable and its defaults
 
include(__DIR__ . '/config.php');


$settings =  array(
  'imageDir' => __DIR__ . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR , 
  'cacheDir' => __DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR ,
  'maxWidth' => 2000,
  'maxHeight' => 2000,
  );

 $image = new CImage($settings);
 
 
    //
    // get/set parameters for image
    //
    if(isset($_GET['src'])) {$image->setSrc($_GET['src']);}
    if(isset($_GET['verbose'])) {$image->setVerbose(true);}
    if(isset($_GET['save-as'])) {$image->setSaveAs($_GET['save-as']);}
    if(isset($_GET['quality'])) {$image->setQuality($_GET['quality']);}
    if(isset($_GET['no-cache']))    { $image->setIgnoreCache(true); }
    if(isset($_GET['width']))       { $image->setNewWidth($_GET['width']); }
    if(isset($_GET['height']))      { $image->setNewHeight($_GET['height']); }
    if(isset($_GET['crop-to-fit'])) { $image->setCropToFit(true); }
    if(isset($_GET['sharpen']))     { $image->setSharpen(true); }

    $display = $image->displayImage();
