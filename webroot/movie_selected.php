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



// Get parameters 
$id     = isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
$title  = isset($_POST['title']) ? strip_tags($_POST['title']) : null;
$year   = isset($_POST['year'])  ? strip_tags($_POST['year'])  : null;
$length = isset($_POST['length'])? strip_tags($_POST['length'])  : null;
$price 	= isset($_POST['price'])? strip_tags($_POST['price'])  : null;
$director = isset($_POST['director'])? strip_tags($_POST['director'])  : null;
$imdb 	= isset($_POST['imdb'])? strip_tags($_POST['imdb'])  : null;
$plot 	= isset($_POST['plot'])? strip_tags($_POST['plot'])  : null;
$image  = isset($_POST['image']) ? strip_tags($_POST['image']) : null;
$genre  = isset($_POST['genre']) ? $_POST['genre'] : array();
$save   = isset($_POST['save'])  ? true : false;
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;


// Check that incoming parameters are valid
is_numeric($id) or die('Check: Id must be numeric.');



// Select information on the movie
$sql = 'SELECT * FROM VMovie2 WHERE id = ?';
$params = array($id);
$res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);

if(isset($res[0])) {
  $movie = $res[0];
}
else {
  die('Failed: There is no movie with that id');
  
}





// Do it and store it all in variables in the Miletus container.
$miletus['title'] = "{$movie->title}";

$sqlDebug = $db->Dump();

$miletus['main'] = <<<EOD
<div id = "movieright">
	<h1 class='colorful'>{$miletus['title']}</h1>
	<p>{$movie->title} kom ut år <span class = 'red'>{$movie->year}</span> och är <span class= 'red'>{$movie->length} minuter lång</span></p>
	<p>Regissör: <span class = 'red'>{$movie->director}</span></p>
	<p>{$movie->title} faller under genrerna: <span class ='red'>{$movie->genre}</span></p>
	<p>Handling: {$movie->plot}</p>
	<p>{$movie->title} kan du streama hos oss för endast <span class ='red'>{$movie->price} kr!</span> </p>
	
	<p><a href="{$movie->imdb}"><img src="img/imdb-icon.png" alt="imdb"/></a></p>
	
	
	<p><span class='red'><a href="movies.php">Visa alla filmer</a></span></p>
	
</div>

<div id = "movieleft">
	<p><img src='img.php?src=/../{$movie->image}&amp;widht=180&amp;height=260&amp;quality=100' alt='{$movie->title}' /></p>
	
</div>

<div class = "clear">
</div

EOD;



// Finally, leave it all to the rendering phase of Miletus.
include(MILETUS_THEME_PATH);