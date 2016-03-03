<?php

class CMovieGenre {


    //$db = new CDatabase($belio['database']);
    public $output;
    public function __construct($db){

        
        $this->db = $db;
        $rows = $this->db->ExecuteSelectQueryAndFetchAll('SELECT COUNT(*) FROM Movie2Genre');
         }
    
function addNew($array){
$idMovie  = isset($_POST['idMovie']) ? strip_tags($_POST['idMovie']) : null;
$idGenre  = isset($_POST['idGenre']) ? strip_tags($_POST['idGenre']) : null;    

//$idMovie   = isset($_POST['idMovie  '])   ? $_POST['idMovie  '] : array();
//$idGenre   = isset($_POST['idGenre '])   ? strip_tags($_POST['idGenre ']) : array();


$sql = 'INSERT INTO Movie2Genre (idMovie, idGenre)   
VALUES (?, ?);';
$params = array($idMovie, $idGenre);
$res=$this->db->ExecuteQuery($sql, $array);

  if($res) {
   $output = 'Informationen sparades.';
  }
else {
    die('Failed: You have to assign a number in both the fields');
}
return $output;
    }
    
    
function deleteNew($array){
$idMovie  = isset($_POST['idMovie']) ? strip_tags($_POST['idMovie']) : null;
$idGenre  = isset($_POST['idGenre']) ? strip_tags($_POST['idGenre']) : null;    


$sql = 'DELETE FROM `Movie2Genre` WHERE `Movie2Genre`.`idMovie` = ? AND `Movie2Genre`.`idGenre` = ?;';
$params = array($idMovie, $idGenre);
$res=$this->db->ExecuteQuery($sql, $array);

  if($res) {
   $output = 'Informationen togs bort.';
  }
else {
    die('Failed: You have to assign a number in both the fields');
}
return $output;
    }
  
    
    
    
    
}