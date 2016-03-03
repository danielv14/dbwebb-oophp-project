<?php
/**
* This is a Miletus pagecontroller.
*
* */
// Include the essential config-fil which also ctreates the $miletus variable with its defaults.
include (__DIR__.'/config.php');

// DO it and store it all in variables in the Miletus container.
$miletus['title'] = "Tärningsspel 100";



$miletus['main'] = <<<EOD
<h1>Kasta tärning!</h1>
<p>Prova din lycka och kasta tärningen här nedan! Spelet går ut på att kasta tärningen upprepade gånger och komma så nära 100 som möjligt!</p>
<p>Det låter väl inte så svårt? Kanske till och med lite tråkigt utan några regler? Ta det lugnt, slår du en 1:a så förlorar du alla dina poäng! Något frustrerande, jag vet! Kanske det som är hela poängen.</p>
<p>Du kan välja att spara ditt resultat om du så vill genom att klicka på spara.</p>
<p> Försök att komma så nära 100 som möjligt. Hur många kast vågar du chansa innan du får en 1:a på tärningen...?</p>
<hr>


<p>
<a class='dicelink' href='?init'>Starta spelet</a> 
<a class='dicelink' href='?roll'>Kasta tärningen</a>
<a class='dicelink' href='?save'>Spara ditt resultat</a>
</p>
EOD;

include('../src/CDiceDisplay/CDiceDisplay.php');
// Finally, leave it all to the rendering phase of Miletus.
include(MILETUS_THEME_PATH);