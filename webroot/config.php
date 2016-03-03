<?php
/**
* Config-file for Miletus. Change settings here to affect installation.
*
*/

/**
* Set the error reporting.
*
*/
error_reporting(-1);				// Report all type of errors
ini_set('display_errors', 1);		// Display all errors
ini_set('output_buffering', 0);		// Do not buffer outputs, write directly


/**
* Define Miletus paths.
*
*/
define('MILETUS_INSTALL_PATH', __DIR__. '/..');
define('MILETUS_THEME_PATH', MILETUS_INSTALL_PATH . '/theme/render.php');


/**
* Include bootstrapping functions.
*
*/
include(MILETUS_INSTALL_PATH . '/src/bootstrap.php');


/**
* Start the session.
*
*/
session_name(preg_replace('/[^a-z\d]/i', '', __DIR__));
session_start();


/**
* Create the Miletus variable.
*
*/
$miletus = array();


/**
* Site wide settings.
*
*/
$miletus['lang']			= 'sv';
$miletus['title_append']	= ' | RM Rental Movies';




/**
* Theme related settings.
*
*/
//$miletus['stylesheet']	= 	'css/style.css';
$miletus['stylesheets'] = array('css/style.css');
$miletus['favicon']		=	'logo.png';

$miletus['header'] = <<<EOD
<form action="movies.php" method="GET"><input type="text" placeholder="%Sök på filmtitel%" name="title"></form>


<img class='sitelogo' src='img/logo.png' alt='oophp Logo'/>
<span class='sitetitle'>Rental Movies</span>
<span class='siteslogan'>Vi styr upp ditt fredagsmys! </span>

EOD;



$miletus['footer'] = <<<EOD
<footer><span class='sitefooter'>Copyright (c) Rental Movies 2014| <a href='login.php'>Logga in</a> <a href='logout.php'>Logga ut</a> <a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a></span></footer>
EOD;

/**
* Navigation Bar
*
*/
$menu = array(
  // Use for styling the menu
  'class' => 'navbar',
 
  // Here comes the menu strcture
  'items' => array(
    // This is a menu item
    'me'  => array(
      'text'  =>'Start',   
      'url'   =>'start.php',  
      'title' => 'Start'
    ),
    
    // This is a menu item
    'about' => array(
      'text'  =>'Om Rental Movies', 
      'url'   =>'about.php',  
      'title' => 'Om Rental Movies'
      
    ),
    
    
     // This is a menu item
    'movies'  => array(
      'text'  =>'Våra filmer',   
      'url'   =>'movies.php',   
      'title' => 'Våra titlar',
 
        
        // Here we add the submenu, with some menu items, as part of a existing menu item
      'submenu' => array(
 
        'items' => array(
          // This is a menu item of the submenu
          'movieadmin'  => array(
            'text'  => 'Administrera',   
            'url'   => 'admin_movie.php',  
            'title' => 'Administrera'
          ),
          
     
        ),
      ),

        
        
        
         ),
    
   
    
   
    // This is a menu item
    'news'  => array(
      'text'  =>'Nyheter',   
      'url'   =>'blog.php',   
      'title' => 'Nyheter',
 
      // Here we add the submenu, with some menu items, as part of a existing menu item
      'submenu' => array(
 
        'items' => array(
          // This is a menu item of the submenu
          'newsadmin'  => array(
            'text'  => 'Administrera',   
            'url'   => 'admin_blog.php',  
            'title' => 'Administrera'
          ),
          
     
        ),
      ),
    ),

   
       
        
    

  ),
 
  // This is the callback tracing the current selected menu item base on scriptname
  'callback' => function($url) {
    if(basename($_SERVER['SCRIPT_FILENAME']) == $url) {
      return true;
    }
  }
);






$miletus['database']['dsn']            = 'mysql:host=blu-ray.student.bth.se;dbname=dave14;';
$miletus['database']['username']       = 'dave14';
$miletus['database']['password']       = '9En:2aR=';
$miletus['database']['driver_options'] = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");




/* Settings for local database 
$miletus['database']['dsn']            = 'mysql:host=localhost;dbname=Movie;';
$miletus['database']['username']       = 'root';
$miletus['database']['password']       = 'root';
$miletus['database']['driver_options'] = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");
*/




/**
 * Settings for JavaScript.
 *
 */
$miletus['modernizr'] = 'js/modernizr.js';
$miletus['jquery'] = '//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js';
//$miletus['jquery'] = null; // To disable jQuery
$miletus['javascript_include'] = array();
//$miletus['javascript_include'] = array('js/main.js'); // To add extra javascript files


/**
* Google analytics.
*
*/
$miletus['google_analytics'] = 'UA-22093351-1'; // Set to null to disable google analytics















