<?php 
/**
 * This is a Miletus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $miletus variable with its defaults.
include(__DIR__.'/config.php'); 


// Define what to include to make the plugin to work
$miletus['stylesheets'][]        = 'css/slideshow.css';
$miletus['javascript_include'][] = 'js/slideshow.js';


// Do it and store it all in variables in the Anax container.
$miletus['title'] = "Slideshow för att testa JavaScript i Anax";

$miletus['main'] = <<<EOD
<div id="slideshow" class='slideshow' data-host="" data-path="img/slideshow/" data-images='["inbruges.jpg", "kingsofsummer.jpg", "maryandmax.jpg", "thefall.jpg"]'>
<img src='img/slideshow/kingsofsummer.jpg' width='970px' height='240px' alt='slideshow'/>
</div>

<h1>En slideshow med JavaScript</h1>
<p>Detta är en exempelsida som visar hur Anax fungerar tillsammans med JavaScript.</p>
EOD;


// Finally, leave it all to the rendering phase of Miletus.
include(MILETUS_THEME_PATH);