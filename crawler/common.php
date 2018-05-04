<?php
function connect_db(){
  $mysqli = new mysqli('mysql107.phy.lolipop.lan', 'LAA0520286', 'jfjwxa', 'LAA0520286-hw');
  $mysqli->set_charset('utf8');
  return $mysqli;
}
?>
