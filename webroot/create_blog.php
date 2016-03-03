<?php
/**
 * This is a Miletus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $miletus variable with its defaults.
include(__DIR__.'/config.php'); 


//Connect to the database and the needed classes for the page
$acronym = isset($_SESSION['user']) ? $_SESSION['user'] : null;
isset($acronym) or die("Please <a href='login.php'>log in</a> before you make any changes.");



$title = isset($_POST['title']) ? $_POST['title'] : null;
$output = "";

if (isset($_POST['save'])) {
  $title = empty($title) ? null : $title;
  if (isset($title)) {
    $db = new CDatabase($miletus['database']);
    $content = new CContent($db);
    $output = $content->setTitle($title);
  }
}

// Prepare content and store it all in variables in the Miletus container.
$miletus['title'] = "Lägg till";
$miletus['main'] = <<<EOD
<h1>Skapa nytt inlägg</h1>

    <form  method="post">
        <fieldset>
        
        <p><label for="title">Titel:</label><input type="text" name="title" value=""></p>
        <p><input type="submit" name="save" value="Spara"><input type="reset" value="Återställ"</p>
        </fiedlset>
        <p>$output</p>
    </form>
    
    <p><span class='red'><a href="admin_blog.php">Återgå till nyhetslistan för att redigera ditt nya inlägg</span></p
EOD;


// Finally, leave it all to the rendering phase of Miletus.

include(MILETUS_THEME_PATH);