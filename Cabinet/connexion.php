
<?php
$con = mysqli_connect("localhost","u677866956_compte_test",";-k33vLYw:H9","u677866956_test");
$con->query("SET lc_time_names = 'fr_FR'");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  else
  {

  }
 // $con->set_charset("utf8");
  
?>
