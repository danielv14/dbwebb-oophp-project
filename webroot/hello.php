<?php
/**
* This is a Miletus pagecontroller.
*
* */
// Include the essential config-fil which also ctreates the $miletus variable with its defaults.
include (__DIR__.'/config.php');

// DO it and store it all in variables in the Miletus container.
$miletus['title'] = "Hello World";

$miletus['header'] = <<<EOD
<img class='sitelogo' src='img/miletus.png' alt='Miletus Logo'/>
<span class='sitetitle'>Miletus webbtemplates</span>
<span class='siteslogan'>Återanändbara moduler för webbutveckling med PHP</span>
EOD;

$miletus['main'] = <<<EOD
<h1>Hej Världen</h1>
<p>Detta är en exempelsida som visar hur Miletus ser ut och fungerar.</p>
EOD;

$miletus ['footer'] = <<<EOD
<footer><span class = 'sitefooter'>Copyright (c) Daniel / <a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a></span></footer>
EOD;

// Finally, leave it all to the rendering phase of Miletus.
include(MILETUS_THEME_PATH);
