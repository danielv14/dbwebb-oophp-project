<?php
/**
 * This is a Hera pagecontroller.
 *
 */
// Include the essential config-file which also creates the $miletus variable with its defaults.
include(__DIR__ . '/config.php');

// add style for cssource
$miletus['stylesheets'][] = 'css/source.css';

// Create the object to display sourcecode
//$source = new CSource();
$source = new CSource(array('secure_dir' => '..', 'base_dir' => '..'));
  
// Do it and store it all in variables in the Hera container.
$miletus['title'] = "Visa källkod";
  
$miletus['main'] = "<h1>Visa källkod</h1>\n" . $source->View();
  
//Finally, leave it all to the rendering phase of Hera.
include(MILETUS_THEME_PATH);