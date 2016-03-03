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


// Connect to the database and use the classes needed for this page
$db = new CDatabase($miletus['database']); 

$filter = new CTextFilter(); 
$content = new CBlog($db, $filter); 

$slug = isset($_GET['slug']) ? $_GET['slug'] : null; 

$html = $content->showPost($slug); 



// Prepare content and store it all in variables in the Miletus container.

$miletus['title'] = "Nyheter från filmvärlden";

$miletus['main'] = <<<EOD
<div id="slideshow" class='slideshow' data-host="" data-path="img/slideshow/" data-images='["her2.jpg", "inbruges.jpg", "her.jpg", "super.jpg", "pulpfiction.jpg"]'>
<img src='img/nyheter.jpg' width='970' height='270' alt='slideshow'/>
</div>


{$html}

EOD;


// Finally, leave it all to the rendering phase of Miletus.

include(MILETUS_THEME_PATH);