<?php
/**
* This is a Miletus pagecontroller.
*
* */
// Include the essential config-fil which also ctreates the $miletus variable with its defaults.
include (__DIR__.'/config.php');

// DO it and store it all in variables in the Miletus container.
$miletus['title'] = "Redovisningar";



$miletus['main'] = <<<EOD
<h1>Redovisningar</h1>

<h2>Projektet</h2>
<h3>Krav 1: Struktur och innehåll</h3>
<p>Jag började med att återanvända så mycket som möjligt från de tidigare kursmomenten, inklusive css och klasser. Ganska snabbt insåg jag att det behövdes en checklista på alla projektets kravspecifikationer och jag gjorde en sådan i början som jag tagit stöd på genom hela projektets gång när jag har behövt guidning i rätt riktigt. 
Ganska snabbt skapade jag sidan om företaget med statisk information då den kändes enklast att göra och för att få den ur vägen. 
Navigeringsmenyn för hemsidan fick undermenyer till sidorna för film och nyheter. Undermenyerna består av admin-sidor där man kan, efter inloggning, ändra och lägga till innehåll.</p>
<p>Sidan för alla listade filmer ändrade jag inte överdrivet mycket på jämfört med de tidigare kursmomenten. Lite style ändrades samt att jag delade upp innehållet och filtreringen i höger- och vänsterdivs. Likväl återanvände jag mycket av tidigare kod för att skapa min sida för nyheter.
Projektets footer var det bara göra några enstaka korrigeringar på för att matcha kravspecifikationen.</p>
<h4>Style</h4>
<p>Efter att jag i princip var klar med det första kravet ändrade jag projektets layout något, då det hittills hade samma layout som i de andra kursmomenten. Däremot lade jag inte ner alldeles för mycket tid och energi på layouten då projektet fortfarande var i ett tidigt stadie och det var många kvar att uppfylla kvar. Ändrade lite färger och samt hemsidans bakgrund.
Hemsidans layout fick sig däremot ett, vad jag anser, rejält ansiktslyft efter att i princip de flesta grundkrav var uppfyllda. Jag ansåg att min tidigare design inte reflekterade det tänkta företaget och dess syfte. Googlade lite efter inspiration samt kollade andras projekt från tidigare kurstillfällen och knåpade tillslut en design jag var nöjd med och som jag tycker återspeglar projektet bättre. Hemsidan gick ifrån att gå i väldigt färglösa toner till lite mer lekfullhet i både färg och typsnitt. Tanken var att man direkt ska kunna se vad hemsidan handlar om när man besöker den och det tycker jag ändå att jag lyckades relativt bra med.</p>

<h3>Krav 2: Sida - Filmer</h3>
<p>Mycket kod återanvändes för att uppfylla kraven för presentationen av hemsidans filmer. Lade däremot till några saker i databasen såsom länkar till imdb länk samt pris för uthyrning. Lade inte till länk till trailer då  imdb innefattar trailer.
Sorteringen av filmlistan samt paginering gjorde finns och jag gjorde endast små ändringar i möjligheten att visa antal filmer samtidigt.
En ny sida skapades som visar all information om respektive film, samt imdb-länk och denna sida kommer man till när man klickar på filmernas bilder. 
Då jag lade till pris i databasen ändrade jag i min filmsida för att reflektera detta utan problem och det går även att sortera efter pris. </p>
<p>Alla filmer visas med hjälp av min img.php. Detta är en funktion jag sparade till slutet av checklistan då jag tänkte att lösningen var krånglig och tidskrävande samt att jag ville få bortgjort mycket av de andra kraven innan jag tog tag i det lite tyngre. 
.</p>
<p>Hade först lite problem när jag skulle lägga till ett sökfält i min headern men efter lite funderingar på vad det egentligen var som behöves göras samt att jag studerade sökimplementationen på min filmsida kom jag fram till en lösning. Är dock inte helt nöjd med hemsidans sökfunktion då man måste, för att uppnå bästa sökresultat, använda sig av ett wildcard. Men jag har inte försökt lösa det på ett smidigare sätt.</p>
<p>När jag  började se över min checklista med krav för att börja skriva lite på min redovisning upptäckte jag en sak jag glömt. Det gick inte att ändra eller ta bort genres från filmer. Då jag har valt att tilldela filmer mer än en genre och återanvända samma struktur för detta som i ett tidigare kursmoment tror jag att det vart lite mer komplicerat. Ett tag försökte jag ändra i min databas så att filmerna bara tilldelades en genre och jag modifierade min kod därefter. Då jag upptäckte det här problemet i ett väldigt sent skede vart jag väldigt stressad och kom inte riktigt fram till en bra lösning och det funkade inte för min del att bara använda en genre. Det fungerade bara halvdant och tyckte att det vart för problematiskt att fortsätta med den lösningen. Jag återställde hela projektet med hjälp av en backup och skrotade idén om endast en genre. Efter en del funderingar hittade jag en lösning där jag kunde använda mig av samma struktur för genres, alltså Movie2Genre genom att göra en separat klass som sköter ändringen av genres med separata sidkontroller. Det blev kanske inte den smidigaste lösningen men det går i alla fall att ändra genres och jag är relativt nöjd.</p>

<h3>Krav 3: Sida - Nyheter</h3>
<p>Mycket av det som krävdes för att få till en nyhetsblogg fanns redan i mitt ramverk i form av CContent, CBlog osv. Viss modifiering gjordes och jag lade även till funktionen att hämta de tre senaste blogginläggen i CBlog.
Jag valde att ha en separat admin-sida där man kan ändra nyhetsinläggen, ta bort dem samt skapa nya inlägg. Mycket av funktionerna av admin-sidan hämtade jag från tidigare kursmoment.</p> 
<p>Är man inte inloggad och försökte komma åt admin-sidan skickas man vidare till inloggningssidan. Detta är något jag lagt till för projektet och som inte återfinns i mina tidigare kursmoment.
Ändrade min funktion för att ta bort inlägg i CContent så att funktionen verkligen raderade från databasen då min CContent från tidigare kursmoment inte tog bort inläggen helt.
Man kan klicka på nyheternas rubriker för att komma till en egen sida där inläggen visas, men det går även bra att läsa hela inläggen på startsidan för nyheterna. </p>

<h3>Krav 4: Första sidan</h3>
<p>Det har det krav som jag började med allra först, att göra en startsidan. Någon gång under kursens gång, kanske runt kurs 4 när vi började arbeta med en filmdatabas och jag visste om att slutprojektet för kursen kunde handla om just filmer tänkte jag att jag ville implementera samma slideshow som tas upp redan i kursens första moment. Det första jag gjorde i projektet var alltså att få till en slideshow samt att välja vilka filmer jag skulle vilja ha med i projektet då jag behövde bilder till min slideshow. Lite konstig ände att börja i kanske. Hade kanske varit en annan sak om jag stött på en massa problem, då hade jag nog lagt min slideshowidé på is tills jag kommit en bit längre i projektet. Denna slideshow finns med på många av projektets sidor.</p>
<p>Relativt snabbt fyllde jag även på startsidan med statisk information som presenterar företaget och hemsidans funktioner. 
Innehållet på första sidan presenteras i två olika divs som representerar innehåll till höger och vänster. Tycker denna lösning såg lite bättre ut än att bara presentera kraven för första sidan vertikalt. Dock hade jag en massa problem med detta, lite onödigt mycket problem kanske då det inte är första gången jag använt två divs sida vid sida. Innehållet på första sidan gick över footer och jag löste det genom att ha en tredje tom div under de andra två som jag stylade med clear:both i min css. </p>
<p>Jag skapade en ny funktion i min CContent, som jag pratade om i krav 3, för att visa de tre senaste nyheterna och använde mig av kod från min nyhetsadminsida för att presentera dessa. Provade först att göra SQL-kommandon i phpMyAdmin för att presentera de tre senaste inläggen och när jag kommit fram till ett kommando jag var nöjd med lade jag till kommandot i funktionen.
Provade även SQL-kommandon för att hämta de tre senaste filmerna i phpMyAdmin och lade till det anropet i min startsida. Det enda som visas är omslagsbilder med hjälp av img.php och klickar man på bilderna kommer man direkt till infosidan om respektive film.
Kravet att visa alla genrer gick snabbt att lägga till då jag kunde återanvända kod från min filmsida. </p>

<h3>Krav 5: Extra funktioner (optionell)</h3>
<p>Gjorde ej någon av dessa extra funktioner då jag inte tycker att tiden riktigt fanns samt att jag inte vill halka efter i den tänka tidsplanen för nästa kurs i kurspaketet.</p>

<h3>Krav 6: Extra funktioner (optionell)</h3>
<p>Gjorde ej någon av dessa extra funktioner då jag inte tycker att tiden riktigt fanns samt att jag inte vill halka efter i den tänka tidsplanen för nästa kurs i kurspaketet.</p>

<h3>Reflektioner</h3>
<p>Jag tycker att det här projektet har knutigt ihop säcken väldigt bra. Mycket av de projektet handlar om har redan tagits upp i form av tidigare moment vilket jag tycker är väldigt bra. 
Det var smidigt att så pass mycket gick att återanvända från tidigare moment  även om det gjordes lite modifikationer här och där.
Tycker helt klart att det var ett rimligt projekt för denna kurs då alla tidigare moment har förberett en och lagt en bra grund. Det vart en bra igenkänningsfaktor och projektet kändes inte så himla främmande eller oöverkomligt tack vare de tidigare momenten. </p>
<p>Fastnade väldigt länge på att visa bilder med hjälp av img.php. Hur jag än vände och vred på min kod så ville inte filmernas bilder visas. Jag tog en paus ifrån det problemet och lade fokus på annat och helt plötsligt insåg jag att jag hade ju src-sökväg till bilderna. Kan vara bra ibland att låta ett problem få vila för att vid ett senare skede titta på problemet igen med nya ögon och förhoppningsvis fräschare hjärna. 
Så här med projektet i ryggen kan jag tycka att min kod blev något rörig och jag kanske inte löste alla krav på bästa sätt men jag tycker inte att det fanns utrymme tidsmässigt för att städa upp min kod på slutet. I efterhand kan jag kanske tänka att det hade varit smartare att städa upp i koden samt snygga till mina lösningar allt eftersom projektet genomfördes men det är väl alltid lätt att vara efterklok. </p>

<h3>Tankar om kursen</h3>
<p>Kursen har i sin helhet gått bra och följde samma upplägg som kurspaketets första kurs, htmlphp vilket ja uppskattar. Måste säga att jag tycker att det vart en stor upptrappning från htmlphp-kursen men det är väl kanske väntat då det är den andra kursen i ett kurspaket. Samtidigt tycker jag att kursen byggde vidare på föregående väldigt bra och kändes som ett relativt naturligt ”andra steg”. Däremot kanske mina åsikter hade varit annorlunda om jag inte läst kurspaketet utan  utan bara hoppat på denna kurs, men nu är fallet inte sådant.</p>
<p>En sak som kanske hade varit bra att ha med som kursmaterial är videotutorials om vissa delar. Kommer inte på ett konkret exempel på moment eller guide där en videoturorial hade varit att föredra men jag tänker bara att det kanske hade varit bra att ha med som komplement till den redan väldigt bra materialet. 
I det stora hela är jag nöjd med kursen och ger den betyget <span class='red'>7/10</span> och jag skulle helt klart kunna rekommendera kursen för intresserade. 






EOD;



// Finally, leave it all to the rendering phase of Miletus.
include(MILETUS_THEME_PATH);
