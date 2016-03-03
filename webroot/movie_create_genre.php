<?php 
/**
* This is a Miletus pagecontroller.
*
*/
// Include the essential config-file which also creates the $miletus variable with its defaults.
include(__DIR__.'/config.php'); 


// Connect to a MySQL database using PHP PDO
$db = new CDatabase($miletus['database']);
$user = new CUser($db);
$cont = new CMovieGenre($db);

$idMovie = isset($_POST['idMovie'])   ? strip_tags($_POST['idMovie']) : null;
$idGenre  = isset($_POST['idGenre'])   ? strip_tags($_POST['idGenre']) : null;
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
isset($acronym) or die( header('Location: login.php?adm'));

$save   = isset($_POST['save'])  ? true : false;
$output = null;
if($save){
    $array = array($idMovie, $idGenre);
    $output = $cont->addNew($array);
    header('Location: movies.php');
}

// Do SELECT from a table
$sql = "SELECT * FROM Movie;";
$res = $db->ExecuteSelectQueryAndFetchAll($sql);

// Put results into a HTML-table
$tr = "<tr><th>Bild</th><th>Titel</th><th>Id</th></tr>";
foreach($res AS $key => $val) {
  $tr .= "<td class ='movie'><a href='movie_selected.php?id={$val->id}'><img src='img.php?src=/../{$val->image}&quality=100' alt='{$val->title}' /></td><td>{$val->title}</td><td>{$val->id}</td></tr>";
}


// Do it and store it all in variables in the belio container.
$miletus['title'] = "Tilldela genre";

$miletus['main'] = <<<EOD
<div id="contentleft">

<h1>Ändra genre</h1>
<p>Här kan du ändra de olika filmerna genres. Se i tabellen här nedan villken film du vill uppdatera, skriv in filmens Id i första rutan och välj genres från listan till höger och skriv in dess Id i den andra rutan.</p>
<p><span class ='red'>Blev du dirigerad hit efter att du skapade en ny film måste du ge den nya filmen en genre för att den ska dyka upp i databasen.</span></p>

<form method=post>
<p><label>Filmens Id: </br> <input type='text' name='idMovie'/> </label></p>
<p><label>Genres Id:</br> <input type='text' name='idGenre'/> </label></p>
<p><input type='submit' name='save' value='Spara'/></p>
<p>{$output}</p>
</form>

<table>
{$tr}
</table>
</div>

<div id ='contentright'>
<h2 class = 'colorful'>Genres</h2>
<span class='red'><p>Id 1: comedy</p>
<p>Id 2: romance</p>
<p>Id 3: collage</p>
<p>Id 4: crime</p>
<p>Id 5: drama</p>
<p>Id 6: thriller</p>
<p>Id 7: animation</p>
<p>Id 8: adventure</p>
<p>Id 9: family</p>
<p>Id 10: svenskt</p>
<p>Id 11: action</p>
<p>Id 12: horror</p>
<p>Id 13: sci-fi</p>
<p>Id 14: fantasy</p>
</span>
</div>

<div class='clear'>
</div>


EOD;

include(MILETUS_THEME_PATH);