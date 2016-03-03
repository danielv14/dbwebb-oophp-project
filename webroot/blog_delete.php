<?php
/**
 * This is a Miletus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $miletus variable with its defaults.
include(__DIR__.'/config.php'); 




$acronym = isset($_SESSION['user']) ? $_SESSION['user'] : null;
isset($acronym) or die("Please <a href='login.php'>log in</a> before you make any changes.");



// Variables
$id = isset($_POST['id']) ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);

//Connect to the database and the needed classes for the page
$db = new CDatabase($miletus['database']);
$content = new CContent($db);

$delete = $content->getContent($id);

$title = htmlentities($delete->title, null, 'UTF-8');

$output = "";

if(isset($_POST['yes'])) {

    $output = $content->deleteItem($id, $title);
}

if(isset($_POST['no'])) {
    header("Location: view.php");
}


// Prepare content and store it all in variables in the Miletus container.
$miletus['title'] = "Ta bort inlägg";
$miletus['main'] = <<<EOD
<h1>Ta bort</h1>

    <form class="frm_content"  method="post">
        <fieldset>
        <input type="hidden" name="id" value="$id">
        <p>Ta bort: <b>$title</b>?</p>Är du säker?</p>
        <p>
        <p><input type="submit" name="yes" value="Ja"><input type="submit" name="no" value="Nej"</p>
        </fiedlset>
        <p><strong>$output</strong></p>
    </form>
    <p><a href="admin_blog.php">Tillbaka</a></p>
EOD;


// Finally, leave it all to the rendering phase of Miletus.
include(MILETUS_THEME_PATH);