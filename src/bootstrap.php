<?php
/**
 * Bootstrapping functions, essential and needed for miletus to work together with some common helpers. 
 *
 */
 
/**
 * Default exception handler.
 *
 */
function myExceptionHandler($exception) {
  echo "miletus: Uncaught exception: <p>" . $exception->getMessage() . "</p><pre>" . $exception->getTraceAsString(), "</pre>";
}
set_exception_handler('myExceptionHandler');


/** 
 * dump () Function 
 * 
 */ 
function dump($array) { 
  echo "<pre>" . htmlentities(print_r($array, 1)) . "</pre>"; 
}  
 
 
/**
 * Autoloader for classes.
 *
 */
function myAutoloader($class) {
  $path = MILETUS_INSTALL_PATH . "/src/{$class}/{$class}.php";
  if(is_file($path)) {
    include($path);
  }
  else {
    throw new Exception("Classfile '{$class}' does not exists.");
  }
}
spl_autoload_register('myAutoloader');