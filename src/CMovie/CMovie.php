<?php

class CMovie {
  
    private $db;

  public function __construct($db) {
    $this->db = $db;
  }
   
   
 /**
 * Create links for hits per page.
 *
 * @param array $hits a list of hits-options to display.
 * @param array $current value.
 * @return string as a link to this page.
 */
public function getHitsPerPage($hits, $current=null) {
  $nav = "Träffar per sida: ";
  foreach($hits AS $val) {
    if($current == $val) {
      $nav .= "$val ";
    }
    else {
      $nav .= "<a href='" . getQueryString(array('hits' => $val)) . "'>$val</a> ";
    }
  }  
  return $nav;
}




}