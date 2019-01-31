<?php
require "DataCollectionService.php";

header('Content-Type: application/json');

$errorCode = null;
$errorMessage = null;

if(!(isset($_POST["username"])) || !(isset($_POST["password"]))){
   http_response_code (400); 
   $errorReturn = array("errorCode"=>400,
                       "errorMessage"=>"Username or Password parameters missing");
    echo json_encode($errorReturn);
    die();
}

$username = $_POST["username"];
$password = $_POST["password"];

unset($_POST["username"]);
unset($_POST["password"]);
   
$user = getUsers($username, $password);
$return = array();
if(isset($user["error"])){
    http_response_code ($user["code"]);
    $return = array( "errorCode"=> $user["code"],
                         "errorMessage"=> $user["reason"]);
    
} else if($user == 0){
    $return = array("login"=>false);
}else {
    $return = array("login"=> true);
}
echo json_encode($return);
die();

//$user = array("username"=> $username, "password"=>$password);
//echo json_encode($user);   




?>