<?php
/**
* This is a Miletus pagecontroller.
*
* */
// Include the essential config-fil which also ctreates the $miletus variable with its defaults.
include (__DIR__.'/config.php');

// Define what to include to make the plugin to work
$miletus['stylesheets'][]        = 'css/slideshow.css';
$miletus['javascript_include'][] = 'js/slideshow.js';

// DO it and store it all in variables in the Miletus container.
$miletus['title'] = "Om oss";



$miletus['main'] = <<<EOD
<div id="slideshow" class='slideshow' data-host="" data-path="img/slideshow/" data-images='["super.jpg", "inbruges.jpg", "her.jpg", "maryandmax.jpg", "thefall.jpg"]'>
<img src='img/slideshow/kingsofsummer.jpg' width='970' height='270' alt='slideshow'/>
</div>
<h1 class = 'colorful'>Vilka är vi?</h1>

<p>Vi är ett nystartad företag med rötterna i Umeå och finns här för att göra din fredagskväll mycket bättre!</p>
<p>Hos oss kan du läsa de senaste nyheterna från filmvärdlden samt streama riktigt schyssta filmer. Vill du veta hur filmstjärnorna ser ut när dom tränar? Undrar du kanske över när nästa Avengers kommer? I så fall har du kommit helt rätt!
och vi tror att du kommer trivas här hos oss.</p>
<p>Tycker du, precis som oss, att filmer ska beröra? Är kvalité något du uppskattar? Gillar du konkurrenskrafitga priser? Då tror vi att du blir väldigt nöjd med vårat utbud av de bästa i filmväg!</p>

<span class ='red'><a href="movies.php">Klicka här för att komma till våra tillgängliga filmer</a></span>


EOD;



// Finally, leave it all to the rendering phase of Miletus.
include(MILETUS_THEME_PATH);
