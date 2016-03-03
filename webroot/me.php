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
$miletus['title'] = "Start";



$miletus['main'] = <<<EOD
<div id="slideshow" class='slideshow' data-host="" data-path="img/slideshow/" data-images='["inbruges.jpg", "super.jpg", "her.jpg", "maryandmax.jpg", "thefall.jpg", "her2.jpg"]'>
<img src='img/slideshow/her2.jpg' width='970px' height='270px' alt='slideshow'/>
</div>

<h1>Välkommen till RM Rental Movies!</h1>

<p>Popcornen är poppade. Drycken är iskall. Dippen är perfektt komponerad. Du har intagit det perfekta läget i soffan. Men filmen då?</p>
<p>Det är där Rental Movies hjälper dig att hitta rätt i mängden av alla filmer. Vi har noga valt ut de perfekta filmerna för alla tillfällen. Det finns redan för många val. Det finns redan för många läsksorter och dippkryddor. Vi på Rental Movies tycker att du redan ör översköld av för många val för att få till den perfekta fredagskvällen.</p> 
<p>Överlåt valet av film till oss! Vi har noga valt ut filmer av toppkvalité och har gjort dessa tillgängliga för dig! Allt du behöver göra är att bläddra bland vårat urval av topfilmer och streama direkt till din TV, surfplatta eller smartphone. Behöver vi ens säga att vi har väldigt genereösa priser?</p>  
  
EOD;




// Finally, leave it all to the rendering phase of Miletus.
include(MILETUS_THEME_PATH);
