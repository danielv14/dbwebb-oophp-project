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


$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
isset($acronym) or die( header('Location: login.php?adm'));

$content = new CContent(new CDatabase($miletus['database']));

$html = $content->getAllContent();



// Prepare content and store it all in variables in the Miletus container.

$miletus['title'] = "Nytt i filmvärlden";

$miletus['main'] = <<<EOD
<div id="slideshow" class='slideshow' data-host="" data-path="img/slideshow/" data-images='["her2.jpg", "inbruges.jpg", "her.jpg", "super.jpg", "pulpfiction.jpg"]'>
<img src='img/admin3.jpg' width='970' height='270' alt='slideshow'/>
</div>

<br>

{$html}
<br>
<span class ='red'><a href="create_blog.php">Skapa nytt inlägg</a></span><br>

EOD;

// Finally, leave it all to the rendering phase of Miletus.
include(MILETUS_THEME_PATH);