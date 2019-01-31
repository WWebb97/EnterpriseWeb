<?php 

function getConnection(){
require 'config.php';
    if (!($conn = mysqli_connect($dbURL, $dbUsername, $dbPassword, $dbName)) ) {
             $conn = array ('error'=> true,
                           'reason'=> 'error connection to database '. mysqli_connect_error());
    }
    return $conn;
}


?>