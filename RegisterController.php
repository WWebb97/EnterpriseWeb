<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

include "DataActionService.php";
header('Content-Type: application/json');

$errorCode = null;
$errorMessage = null;
$return = array();

$email = $_POST['email'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$password = $_POST['password'];
$username = $_POST['username'];
$departmentID = $_POST['departmentID'];
$captchabox = $_POST['captchabox'];

unset($_POST['email']);
unset($_POST['first_name']);
unset($_POST['last_name']);
unset($_POST['departmentID']);
unset($_POST['password']);
unset($_POST['username']);
unset($_POST['captchabox']);

if ($captchabox == $_SESSION['random_code']){
    $result = registerUser($email, $first_name, $last_name, $departmentID, $password, $username);
    $return = null;
    if($email==null|| $first_name==null|| $last_name==null|| $departmentID==null|| $password==null|| $username==null){
        http_response_code(400);
        $return = array("errorCode" => 400,
                       "errorMessage" => "Incorrect information");
        echo json_encode($return);
        die();
    }
    if($result === 1062){
        //https response code actually needs to be set see how i have done it in the login controller
        http_response_code(400);
        $return = array("errorCode" => 400,
                       "errorMessage" => "Duplicate User");
    }else if($result == 1){
        $return = array("register"=>true);
    }else{
        http_response_code(500);
        $return = array("errorCode" => 500,
                       "errorMessage" => $result);
    }
}else{
    http_response_code(400);
    $return = array("errorCode" => 400,
                    "errorMessage" => "incorrectCaptcha");
}
echo json_encode($return);
die();
?>
