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



// Get parameters 
$id     = isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
$title  = isset($_POST['title']) ? strip_tags($_POST['title']) : null;
$year   = isset($_POST['year'])  ? strip_tags($_POST['year'])  : null;
$length   = isset($_POST['length'])  ? strip_tags($_POST['length'])  : null;
$price 	= isset($_POST['price'])? strip_tags($_POST['price'])  : null;
$director = isset($_POST['director'])? strip_tags($_POST['director'])  : null;
$plot 	= isset($_POST['plot'])? strip_tags($_POST['plot'])  : null;
$image  = isset($_POST['image']) ? strip_tags($_POST['image']) : null;
$imdb 	= isset($_POST['imdb'])? strip_tags($_POST['imdb'])  : null;
$genre  = isset($_POST['genre']) ? $_POST['genre'] : array();
$save   = isset($_POST['save'])  ? true : false;
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;


// Check that incoming parameters are valid
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
isset($acronym) or die( header('Location: login.php?adm'));
is_numeric($id) or die('Check: Id must be numeric.');



// Check if form was submitted
$output = null;
if($save) {
  $sql = '
    UPDATE Movie SET
      title = ?,
      year = ?,
      director = ?,
      price = ?,
      image = ?,
      length = ?,
      imdb = ?,
      plot = ?
    WHERE 
      id = ?
  ';
  $params = array($title, $year, $director, $price, $image, $length, $imdb, $plot, $id,);
  $db->ExecuteQuery($sql, $params);
  $output = 'Informationen sparades.';
  header('Location: movies.php');
  exit;
}


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
$miletus['title'] = "Uppdatera info om {$movie->title}";

$sqlDebug = $db->Dump();

$miletus['main'] = <<<EOD
<h1>{$miletus['title']}</h1>

<form method=post>
  <fieldset>
  <input type='hidden' name='id' value='{$id}'/>
  <p><label>Titel:<br/><input type='text' name='title' value='{$movie->title}'/></label></p>
  <p><label>År:<br/><input type='text' name='year' value='{$movie->year}'/></label></p>
  <p><label>Regissör:<br/><input type='text' name='director' value='{$movie->director}'/></label></p>
  <p><label>Pris:<br/><input type='text' name='price' value='{$movie->price}'/></label></p>
  <p><label>Bild:<br/><input type='text' name='image' value='{$movie->image}'/></label></p>
  <p><label>Längd:<br/><input type='text' name='length' value='{$movie->length}'/></label></p>
  <p><label>Imdb-länk:<br/><input type='text' name='imdb' value='{$movie->imdb}'/></label></p>



 
  <p>Handling:</p>
  <p><label></label><textarea name="plot">{$movie->plot}</textarea></p>
  <p><input type='submit' name='save' value='Spara'/> <input type='reset' value='Återställ'/></p>
  <p><a href='movies.php'>Visa alla</a></p>
  <output>{$output}</output>
  </fieldset>
</form>



EOD;



// Finally, leave it all to the rendering phase of Miletus.
include(MILETUS_THEME_PATH);