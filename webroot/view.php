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




$content = new CContent(new CDatabase($miletus['database']));

$html = $content->getAllContent();



// Prepare content and store it all in variables in the Miletus container.

$miletus['title'] = "Nytt i filmvärlden";

$miletus['main'] = <<<EOD
<div id="slideshow" class='slideshow' data-host="" data-path="img/slideshow/" data-images='["her2.jpg", "inbruges.jpg", "her.jpg", "super.jpg", "pulpfiction.jpg"]'>
<img src='img/admin.jpg' width='970px' height='270px' alt='slideshow'/>
</div>

<p>Tänk på att du måste vara inloggad för att göra ändringar i databasen.</p>


{$html}
<br>
<a href="blog.php">Visa alla inlägg</a><br>
<a href="create.php">Skapa nytt inlägg</a><br>

EOD;

// Finally, leave it all to the rendering phase of Miletus.
include(MILETUS_THEME_PATH);