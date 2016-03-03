<?php 
/**
 * This is a Miletus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $miletus variable with its defaults.
include(__DIR__.'/config.php'); 

$miletus['stylesheets'][]        = 'css/slideshow.css';
$miletus['javascript_include'][] = 'js/slideshow.js';

$miletus['stylesheets'][] = '//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css';

$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
isset($acronym) or die( header('Location: login.php?adm'));

// Connect to a MySQL database using PHP PDO
$db = new CDatabase($miletus['database']);
$user = new CUser($db);



// Do SELECT from a table
$sql = "SELECT * FROM Movie;";
$res = $db->ExecuteSelectQueryAndFetchAll($sql);


// Put results into a HTML-table
$tr = "<tr><th>Bild</th><th>Titel</th><th>År</th><th>Admin</th></tr>";
foreach($res AS $key => $val) {
  $tr .= "<td class ='movie'><a href='movie_selected.php?id={$val->id}'><img src='img.php?src=/../{$val->image}&quality=100' alt='{$val->title}' /></td><td>{$val->title}</td><td>{$val->year}</td><td class='menu'><a href='movie_edit.php?id={$val->id}'><i class='icon-edit'></i></a><a href='movie_delete.php?id={$val->id}'><i class='icon-remove-sign'></i></a></td></tr>";
}


// Do it and store it all in variables in the Miletus container.
$miletus['title'] = "Administrera film";

$sqlDebug = $db->Dump();

$miletus['main'] = <<<EOD
<div id="slideshow" class='slideshow' data-host="" data-path="img/slideshow/" data-images='["her2.jpg", "inbruges.jpg", "her.jpg", "super.jpg", "pulpfiction.jpg"]'>
<img src='img/admin3.jpg' width='970px' height='270px' alt='slideshow'/>
</div>


<div id ='contentleft'>
<h2>Här kan du ändra eller ta bort filmer från Rental Movie</h2>


<table>
{$tr}
</table>

</div>

<div id='contentright'>
<h2 class = 'colorful'>Admin</h2>
<p><span class='red'><a href='movie_create.php'>Lägg till film</a></span></p>
<p><span class='red'><a href='movie_create_genre.php'>Tilldela genres</a></span></p>
<p><span class='red'><a href='movie_delete_genre.php'>Ta bort genres</a></span></p>
</div>

<div class = 'clear'>
</div>


EOD;




// Finally, leave it all to the rendering phase of Miletus.
include(MILETUS_THEME_PATH);