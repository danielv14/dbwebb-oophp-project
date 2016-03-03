<?php 
/**
 * This is a Miletus pagecontroller.
 *
 */
// Include the essential config-file which also creates the $anax variable with its defaults.
include(__DIR__.'/config.php'); 

// Define what to include to make the plugin to work
$miletus['stylesheets'][]        = 'css/slideshow.css';
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



/**
 * Create links for hits per page.
 *
 * @param array $hits a list of hits-options to display.
 * @param array $current value.
 * @return string as a link to this page.
 */
function getHitsPerPage($hits, $current=null) {
  $nav = "Träffar per sida: ";
  foreach($hits AS $val) {
    if($current == $val) {
      $nav .= "<span class='red'>$val </span>";
    }
    else {
      $nav .= "<span class='red'><a href='" . getQueryString(array('hits' => $val)) . "'>$val</a> </span>";
    }
  }  
  return $nav;
}



/**
 * Create navigation among pages.
 *
 * @param integer $hits per page.
 * @param integer $page current page.
 * @param integer $max number of pages. 
 * @param integer $min is the first page number, usually 0 or 1. 
 * @return string as a link to this page.
 */
function getPageNavigation($hits, $page, $max, $min=1) {
  $nav  = ($page != $min) ? "<a href='" . getQueryString(array('page' => $min)) . "'>&lt;&lt;</a> " : '&lt;&lt; ';
  $nav .= ($page > $min) ? "<a href='" . getQueryString(array('page' => ($page > $min ? $page - 1 : $min) )) . "'>&lt;</a> " : '&lt; ';

  for($i=$min; $i<=$max; $i++) {
    if($page == $i) {
      $nav .= "$i ";
    }
    else {
      $nav .= "<a href='" . getQueryString(array('page' => $i)) . "'>$i</a> ";
    }
  }

  $nav .= ($page < $max) ? "<a href='" . getQueryString(array('page' => ($page < $max ? $page + 1 : $max) )) . "'>&gt;</a> " : '&gt; ';
  $nav .= ($page != $max) ? "<a href='" . getQueryString(array('page' => $max)) . "'>&gt;&gt;</a> " : '&gt;&gt; ';
  return $nav;
}



/**
 * Function to create links for sorting
 *
 * @param string $column the name of the database column to sort by
 * @return string with links to order by column.
 */
function orderby($column) {
  $nav  = "<a href='" . getQueryString(array('orderby'=>$column, 'order'=>'asc')) . "'>&darr;</a>";
  $nav .= "<a href='" . getQueryString(array('orderby'=>$column, 'order'=>'desc')) . "'>&uarr;</a>";
  return "<span class='orderby'>" . $nav . "</span>";
}



// Connect to a MySQL database using PHP PDO
$db = new CDatabase($miletus['database']);


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
    $genres .= "<a href='" . getQueryString(array('genre' => $val->name)) . "'>{$val->name}</a> ";
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

// Select by title
if($title) {
  $where .= ' AND title LIKE ?';
  $params[] = $title;
} 

// Select by year
if($year1) {
  $where .= ' AND year >= ?';
  $params[] = $year1;
} 
if($year2) {
  $where .= ' AND year <= ?';
  $params[] = $year2;
} 

// Select by genre
if($genre) {
  $where .= ' AND G.name = ?';
  $params[] = $genre;
} 

// Pagination
if($hits && $page) {
  $limit = " LIMIT $hits OFFSET " . (($page - 1) * $hits);
}

// Complete the sql statement
$where = $where ? " WHERE 1 {$where}" : null;
$sql = $sqlOrig . $where . $groupby . $sort . $limit;
$res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);


// Put results into a HTML-table
$tr = "<tr><th></th><th class = 'colorful'>Titel<span class ='red'> " .orderby('title') . "</span></th><th class = 'colorful'>År <span class='red'>" . orderby('year') . "</span></th><th class = 'colorful'>Pris <span class='red'>" . orderby('price') . "</span></th><th class = 'colorful'>Genre</th></tr>";
foreach($res AS $key => $val) {
  $tr .= "<tr><td class='movie'><a href='movie_selected.php?id={$val->id}'><img src='img.php?src=/../{$val->image}&amp;quality=100' alt='{$val->title}' /></td><td>{$val->title}</td><td>{$val->year}</td><td>{$val->price} kr</td><td>{$val->genre}</a></td></tr>";
}



// Get max pages for current query, for navigation
$sql = "
  SELECT
    COUNT(id) AS rows
  FROM 
  (
    $sqlOrig $where $groupby
  ) AS Movie
";
$res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);
$rows = $res[0]->rows;
$max = ceil($rows / $hits);



// Do it and store it all in variables in the Miletus container.
$miletus['title'] = "Våra filmer";

$hitsPerPage = getHitsPerPage(array(4, 8, 12), $hits);
$navigatePage = getPageNavigation($hits, $page, $max);
$sqlDebug = $db->Dump();

$miletus['main'] = <<<EOD
<div id="slideshow" class='slideshow' data-host="" data-path="img/slideshow/" data-images='["super.jpg", "inbruges.jpg", "her.jpg", "pulpfiction.jpg", "maryandmax.jpg", "thefall.jpg"]'>
<img src='img/filmer.jpg' width='970' height='270' alt='slideshow'/>
</div>


<div id = "contentright">
<form>
  <fieldset>
  <input type=hidden name=genre value='{$genre}'/>
  <input type=hidden name=hits value='{$hits}'/>
  <input type=hidden name=page value='1'/>
  <p><label>Titel: <input type='search' name='title' placeholder = "Ex. %Kopps%" value='{$title}'/></label></p>
  <p><label>Årtal: 
      <input type='text' name='year1' placeholder = "ex. 1999" value='{$year1}'/></label>
      
      <label><input type='text' name='year2' placeholder = "ex. 2003" value='{$year2}'/></label>
    
  </p>
  <p><label>Välj genre:</label><span class ='red'> {$genres}</span></p>
  
  <p><input type='submit' name='submit' value='Sök'/></p>

  <p><span class='red'><a href='?'>Visa alla</a></span></p>
  
  </fieldset>
</form>
</div>

<div id ="contentleft">
<div class='dbtable'>
  <div class='rows'>{$rows} träff(ar). {$hitsPerPage}</div>
  <table>
  {$tr}
  </table>
  <div class='pages'><span class='red'>{$navigatePage}</span></div>
</div>
</div>

<div class = "clear">
</div>


EOD;



// Finally, leave it all to the rendering phase of Miletus.
include(MILETUS_THEME_PATH); 