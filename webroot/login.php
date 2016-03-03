<?php 
/**
 * This is a Miletus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $miletus variable with its defaults.
include(__DIR__.'/config.php'); 




// Connect to a MySQL database using PHP PDO
$db = new CDatabase($miletus['database']);


// Check if user is authenticated.
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;

if($acronym) {
  $output = "Välkommen! Du är inloggad som: $acronym ({$_SESSION['user']->name})";
}
else {
  $output = "Du är INTE inloggad.";
}


// Check if user and password is okey
if(isset($_POST['login'])) {
  $sql = "SELECT acronym, name FROM User WHERE acronym = ? AND password = md5(concat(?, salt))";
  $res = $db->ExecuteSelectQueryAndFetchAll($sql, array($_POST['acronym'], $_POST['password']));
  if(isset($res[0])) {
    $_SESSION['user'] = $res[0];
  }
  header('Location: login.php');
}



// Do it and store it all in variables in the Miletus container.
$miletus['title'] = "Login";

$miletus['main'] = <<<EOD
<h1>{$miletus['title']}</h1>

<form method=post>
  <fieldset>
  
  <p><label>Användare:<br/><input type='text' placeholder="doe eller admin" name='acronym' value=''/></label></p>
  <p><label>Lösenord:<br/><input type='password' placeholder='doe eller admin' name='password' value=''/></label></p>
  <p><input type='submit' name='login' value='Login'/></p>
  <p><span class='red'><a href='logout.php'>Logout</a></span></p>
  <output><span class ='colorful'>{$output}</span></output>
  </fieldset>
</form>

EOD;



// Finally, leave it all to the rendering phase of Miletus.
include(MILETUS_THEME_PATH);