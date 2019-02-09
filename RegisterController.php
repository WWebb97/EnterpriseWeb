<?php
require "DataActionService.php";
header('Content-Type: application/json');

$errorCode = null;
$errorMessage = null;
$return = array();

$email = $_POST['email'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$password = $_POST['password'];
$username = $_POST['username'];
$department = $_POST['department'];


unset($_POST['email']);
unset($_POST['first_name']);
unset($_POST['last_name']);
unset($_POST['department']);
unset($_POST['password']);
unset($_POST['username']);



$departmentID = 3;
$result = registerUser($email, $first_name, $last_name, $departmentID, $password, $username);
if($result === 1062){
    //https response code actually needs to be set see how i have done it in the login controller
    $return = array("errorCode" => 400,
                   "errorMessage" => "Duplicate User");
}else if($result === 1){
    $return = array("register"=>true);
}else{
    $return = array("errorCode" => 400,
                   "errorMessage" => "error");
}

echo json_encode($return);
die();
?>
