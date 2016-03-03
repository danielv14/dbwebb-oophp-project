<?php 
/**
 * This is a Miletus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $miletus variable with its defaults.
include(__DIR__.'/config.php'); 

$miletus['stylesheets'][] = '//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css';


// Connect to a MySQL database using PHP PDO
$db = new CDatabase($miletus['database']);
$user = new CUser($db);



// Do SELECT from a table
$sql = "SELECT * FROM Movie;";
$res = $db->ExecuteSelectQueryAndFetchAll($sql);


// Put results into a HTML-table
$tr = "<tr><th>Rad</th><th>Id</th><th>Bild</th><th>Titel</th><th>År</th><th></th></tr>";
foreach($res AS $key => $val) {
  $tr .= "<tr><td>{$key}</td><td>{$val->id}</td><td><img width='60' height='80' src='{$val->image}' alt='{$val->title}' /></td><td>{$val->title}</td><td>{$val->year}</td><td class='menu'><a href='movie_delete.php?id={$val->id}'><i class='icon-remove-sign'></i></a></td></tr>";
}


// Do it and store it all in variables in the Miletus container.
$miletus['title'] = "Välj film att radera";

$sqlDebug = $db->Dump();

$miletus['main'] = <<<EOD
<h1>{$miletus['title']}</h1>
<table>
{$tr}
</table>


EOD;




// Finally, leave it all to the rendering phase of Miletus.
include(MILETUS_THEME_PATH);
