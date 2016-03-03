<?php
/**
 * A Class for handeling login and logout of the website
 *
 */
class CUser {
  
  private $db;   
        
  public function __construct($database) {
    $this->db = $database;
  }
        
  public function Login($user, $password) {
    $sql = "SELECT acronym, name FROM User WHERE acronym = ? AND password = md5(concat(?, salt))";
    $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($user, $password));
    if(isset($res[0])) {
      $_SESSION['user'] = $res[0];
    }        
  }
  
  public function Logout() {
    unset($_SESSION['user']);       
  }
  
  public function IsAuthenticated() {
    $state = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
    return $state;
  }
  
  public function GetAcronym() {
    return $_SESSION['user']->acronym;      
  }
  
  public function GetName() {
    return $_SESSION['user']->name;        
  }
} 