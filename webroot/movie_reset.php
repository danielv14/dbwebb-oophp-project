<?php 
/**
 * This is a Miletus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $miletus variable with its defaults.
include(__DIR__.'/config.php'); 



// Restore the database to its original settings
$sql      = 'Movie.sql';
$mysql    = '/usr/local/bin/mysql';
$host     = 'localhost';
$login    = 'root';
$password = "root";
$output = null;



if(isset($_POST['restore']) || isset($_GET['restore'])) {

  // Use on Unix/Unix/Mac
  $cmd = "$mysql -h{$host} -u{$login} -p{$password} < $sql 2>&1";

 
  $res = exec($cmd);
  $output = "<p>Databasen är återställd via kommandot<br/><code>{$cmd}</code></p><p>{$res}</p>";
}


// Do it and store it all in variables in the Miletus container.
$miletus['title'] = "Återställ databasen till ursprungligt skick";

$miletus['main'] = <<<EOD
<h1>{$miletus['title']}</h1>
<form method=post>
<input type=submit name=restore value='Återställ databasen'/>
<output>{$output}</output>
</form>
EOD;




// Finally, leave it all to the rendering phase of Miletus.
include(MILETUS_THEME_PATH);