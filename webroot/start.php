<?php
/**
* This is a Miletus pagecontroller.
*
* */
// Include the essential config-fil which also ctreates the $miletus variable with its defaults.
include (__DIR__.'/config.php');

// Define what to include to make the plugin to work
$miletus['stylesheets'][]        = 'css/slideshow.css';
$miletus['stylesheets'][]        = 'css/table.css';

$miletus['javascript_include'][] = 'js/slideshow.js';

/**
 * Use the current querystring as base, modify it according to $options and return the modified query string.
 *
 * @param array $options to set/change.
 * @param string $prepend this to the resulting query string
 * @return string with an updated query string.
 */
function getQueryString($options=array(), $prepend='?') {
  // parse query string into array
  $query = array();
  parse_str($_SERVER['QUERY_STRING'], $query);

  // Modify the existing query string with new options
  $query = array_merge($query, $options);

  // Return the modified querystring
  return $prepend . htmlentities(http_build_query($query));
}
// Connect to a MySQL database
$db = new CDatabase($miletus['database']);
// Connect to a MySQL database to get the bogposts
$content = new CContent(new CDatabase($miletus['database']));
$threeblogpost = $content->get3Blogposts();


// Get parameters 
$title    = isset($_GET['title']) ? $_GET['title'] : null;
$genre    = isset($_GET['genre']) ? $_GET['genre'] : null;
$hits     = isset($_GET['hits'])  ? $_GET['hits']  : 8;
$page     = isset($_GET['page'])  ? $_GET['page']  : 1;
$year1    = isset($_GET['year1']) && !empty($_GET['year1']) ? $_GET['year1'] : null;
$year2    = isset($_GET['year2']) && !empty($_GET['year2']) ? $_GET['year2'] : null;
$orderby  = isset($_GET['orderby']) ? strtolower($_GET['orderby']) : 'id';
$order    = isset($_GET['order'])   ? strtolower($_GET['order'])   : 'asc';


// Check that incoming parameters are valid
is_numeric($hits) or die('Check: Hits must be numeric.');
is_numeric($page) or die('Check: Page must be numeric.');
is_numeric($year1) || !isset($year1)  or die('Check: Year must be numeric or not set.');
is_numeric($year2) || !isset($year2)  or die('Check: Year must be numeric or not set.');

// Get all genres that are active
$sql = '
  SELECT DISTINCT G.name
  FROM Genre AS G
    INNER JOIN Movie2Genre AS M2G
      ON G.id = M2G.idGenre
';
$res = $db->ExecuteSelectQueryAndFetchAll($sql);

$genres = null;
foreach($res as $val) {
  if($val->name == $genre) {
    $genres .= "$val->name ";
  }
  else {
    $genres .= "<a href='movies.php" . getQueryString(array('genre' => $val->name)) . "'>{$val->name}</a> ";
  }
}
// Prepare the query based on incoming arguments
$sqlOrig = '
  SELECT 
    M.*,
    GROUP_CONCAT(G.name) AS genre
  FROM Movie AS M
    LEFT OUTER JOIN Movie2Genre AS M2G
      ON M.id = M2G.idMovie
    INNER JOIN Genre AS G
      ON M2G.idGenre = G.id
';
$where    = null;
$groupby  = ' GROUP BY M.id';
$limit    = null;
$sort     = " ORDER BY $orderby $order";
$params   = array();

// Complete the sql statement
$where = $where ? " WHERE 1 {$where}" : null;
$sql = $sqlOrig . $where . $groupby . $sort . $limit;
$res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);

// Select the 3 latest movies by id
$sql = "
SELECT * FROM `Movie` 
order by id desc limit 3;";
$res = $db->ExecuteSelectQueryAndFetchAll($sql);

// Put 3 latest movies in a table
$tr = "<th class ='colorful'>Nykomlingar</th></tr>";
foreach($res AS $key => $val) {
  $tr .= "<td><a href='movie_selected.php?id={$val->id}'><img src='img.php?src=/../{$val->image}&amp;widht=100&amp;height=120&amp;quality=100' alt='{$val->title}' /></td></tr>";
}

// DO it and store it all in variables in the Miletus container.
$miletus['title'] = "Start";
$miletus['main'] = <<<EOD
<div id="slideshow" class='slideshow' data-host="" data-path="img/slideshow/" data-images='["inbruges.jpg", "pulpfiction.jpg", "super.jpg", "maryandmax.jpg", "thefall.jpg", "her2.jpg"]'>
<img src='img/slideshow/her.jpg' width='970' height='270' alt='slideshow'/>
</div>

<div id = "contentleft">
<h1 class = 'colorful'>Välkommen till RM Rental Movies!</h1>
<p>Popcornen är poppade. Drycken är iskall. Dippen är perfekt komponerad. Du har intagit det perfekta läget i soffan. Men filmen då?</p>
<p>Det är där Rental Movies hjälper dig att hitta rätt i mängden av alla filmer. Vi har noga valt ut de perfekta filmerna för alla tillfällen. Det finns redan för många val. Det finns redan för många läsksorter och dippkryddor. Vi på Rental Movies tycker att du redan ör översköljd av för många val för att få till den perfekta fredagskvällen.</p> 
<p>Överlåt valet av film till oss! Allt du behöver göra är att bläddra bland vårat urval av toppfilmer och streama direkt till din TV, surfplatta eller smartphone. Behöver vi ens nämna våra förmånliga priser?</p>
<p>Ta en titt på vilka filmer vi kan erbjuda i din favoritgenre: <span class ='red'>{$genres}</span></p> 
<h2 class='colorful'>Senaste nyheterna från filmvärlden</h2>
{$threeblogpost}
</div>

<div id = "contentright">
<table class = "threelatest">
{$tr}
</table>
</div>

<div class = "clear">
</div>
EOD;
// Finally, leave it all to the rendering phase of Miletus.
include(MILETUS_THEME_PATH);
