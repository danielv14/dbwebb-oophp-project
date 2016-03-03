<?php  
/* 
 * This is a Miletus pagecontroller 
 * 
 */ 

// Include the essential config-file which also creates the $miletus variable with its defaults. 
include(__DIR__.'/config.php'); 

// Do it and store it all in variables in the Miletus container. 
$miletus['title'] = ""; 

$db = new CDatabase($miletus['database']); 
$content = new CContent($db); 
$output = ""; 

if(isset($_POST['ok'])) { 
    $output = $content->resetDatabase(); 
} 

if(isset($_POST['delete'])) { 
    header('Location: view.php'); 
} 


$miletus['main'] = <<<EOD 
<article> 

<h1>Återställ databasen</h1> 
<p>Vill du återställa databasen?</p> 
<form method="post"> 
<input type="submit" name="ok" value="OK"> 
<input type="submit" name="delete" value="Avbryt"> 
</form> 
<p><strong>$output</strong></p> 
EOD; 


// Finally, leave it all to the rendering phase of Miletus. 
include(MILETUS_THEME_PATH); 