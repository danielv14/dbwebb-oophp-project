<?php
/**
* This is a Miletus pagecontroller.
*
* */
// Include the essential config-fil which also ctreates the $miletus variable with its defaults.
include (__DIR__.'/config.php');


// Get the classes needed 
$gallery = new CGallery( __DIR__ . DIRECTORY_SEPARATOR . 'img', '');
$path = isset($_GET['path']) ? $_GET['path'] : null;


$gallery = $gallery->getGallery($path);



// DO it and store it all in variables in the Miletus container.
$miletus['title'] = "Hemsidans bilder";
$miletus['main'] = <<<EOD

$gallery

EOD;


// Finally, leave it all to the rendering phase of Miletus.

include(MILETUS_THEME_PATH);