<?php
// Get the arguments from the query string
$roll = isset($_GET['roll']) ? true : false;
$init = isset($_GET['init']) ? true : false;

// Create the object or get it from the session
if(isset($_SESSION['dicehand'])) {
  $miletus['main'] .= "<p>Lycka till, spelet är igång!</p>";
  $hand = $_SESSION['dicehand'];
}
else {
  $miletus['main'] .= "<p>Prova lyckan och starta spelet.</p>";
  $hand = new CDiceHand(1);
  $_SESSION['dicehand'] = $hand;
}

//Show saved result
if(isset($_SESSION['last'])){
        $miletus['main'] .= "<p> Din senaste sparade poäng är  {$_SESSION['last']}</p>";
}

// Roll the dices 
if($roll) {
  $hand->Roll();
}
else if($init) {
  $hand->InitRound();
}

$var = $hand->GetRollsAsImageList();
    $miletus['main'] .= "$var";

if($roll){
     $hand->GetTotal();
  }

// Destoy if you get 1 on the dice 
$miletus['main'] .= $hand->destroy1(1,0);

//Sum of all the throws
     $miletus['main'] .= "<p> Summan av alla tärningsslag är nu {$hand->GetRoundTotal()}</p>";
 

// Save the score and restart the game
if(isset($_GET['save'])){
        $_SESSION['last'] = $hand->GetRoundTotal();
        $hand->InitRound();
        $miletus['main'] .= "<p>Du har valt att stanna på {$_SESSION['last']} poäng. Spelet startar om!</p>";
}

?>