<?php
/**
 * This is a Miletus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $miletus variable with its defaults.
include(__DIR__.'/config.php'); 


//Connect to the database and the needed classes for the page
$db = new CDatabase($miletus['database']);
$filter = new CTextFilter();
$content = new CPage($db, $filter);

$url = isset($_GET['url']) ? $_GET['url'] : null;

$html = $content->showPage($url);



// Prepare content and store it all in variables in the Miletus container.

$miletus['title'] = "Blog";

$miletus['main'] = <<<EOD
{$html}

EOD;


// Finally, leave it all to the rendering phase of Miletus.
include(MILETUS_THEME_PATH);