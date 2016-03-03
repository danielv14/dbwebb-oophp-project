<?php 
/**
 * This is a Miletus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $miletus variable with its defaults.
include(__DIR__.'/config.php'); 



// Get incoming parameters
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;

if($acronym) {
  $output = "Du är inloggad som: $acronym ({$_SESSION['user']->name})";
}
else {
  $output = "Du är INTE inloggad.";
}


// Logout the user
if(isset($_POST['logout'])) {
  unset($_SESSION['user']);
  header('Location: start.php');
}



// Do it and store it all in variables in the Miletus container.
$miletus['title'] = "Logout";

$miletus['main'] = <<<EOD
<h1>{$miletus['title']}</h1>

<form method=post>
  <fieldset>
  <p><input type='submit' name='logout' value='Logout'/></p>
  <output><span class='colorful'>{$output}</span></output>
  </fieldset>
</form>

EOD;



// Finally, leave it all to the rendering phase of Miletus.
include(MILETUS_THEME_PATH);