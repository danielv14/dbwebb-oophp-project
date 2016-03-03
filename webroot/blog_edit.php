<?php
/**
 * This is a Miletus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $miletus variable with its defaults.
include(__DIR__.'/config.php'); 



// Connect to the databases and classes needed for the page 
$db = new CDatabase($miletus['database']);

$content = new CContent($db);

$id = isset($_POST['id']) ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
$save = isset($_POST['save']) ? true : false;
$acronym = isset($_SESSION['user']) ? $_SESSION['user'] : null;

isset($acronym) or die("Please <a href='login.php'>log in</a> before you make any changes.");

is_numeric($id) or die('Check: Id must be numeric');

$output = "";

if($save) {
    $output = $content->updateItem();
    header('Location: admin_blog.php');
  exit;
}

$edit = $content->getContent($id);
$date = date("Y-m-d H:i:s");
$url             = htmlentities($edit->url, null, 'UTF-8');
$type         = htmlentities($edit->type, null, 'UTF-8');
$published = htmlentities($edit->published, null, 'UTF-8');
$filter     = htmlentities($edit->filter, null, 'UTF-8');
$title         = htmlentities($edit->title, null, 'UTF-8');
$data         = htmlentities($edit->data, null, 'UTF-8');
$slug         = htmlentities($edit->slug, null,'UTF-8');


// Prepare content and store it all in variables in the Miletus container.
$miletus['title'] = "Redigera";
$miletus['main'] = <<<EOD
<h1><span class ='colorful'>{$title}</span></h1>
    <hr>
    <form method="post">
        <fieldset>
        <legend>Uppdatera innehåll</legend>
        <input type="hidden" name="id" value="$id">
        <p><label for="title">Titel:</label><input type="text" name="title" value="$title"></p>
        <p><label for="slug">Slug:</label><input type="text" name="slug" value="$slug"></p>
        <p><label for="url">Url:</label><input type="text" name="url" value="$url"></p>
        <p><label for="data">Text:</label><textarea name="data">$data</textarea></p>
        <p><label for="type">Type:</label><input type="text" placeholder="post eller page" name="type" value="post"></p>
        <p><label for="filter">Filter:</label><input type="text" name="filter" value="bbcode, nl2br, markdown"></p>
        <p><label for="published">Publiceringsdatum:</label><input type="text" value="$date" name="published"</p>
        <p><input type="submit" name="save" value="Spara"><input type="reset" value="Återställ"</p>
        </fiedlset>
        <p><span class='red'><a href="admin_blog.php">Visa alla</a></span></p>
        <p><strong>$output</strong></p>
    </form>

EOD;

// Finally, leave it all to the rendering phase of Miletus.
include(MILETUS_THEME_PATH);
