<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

include "DataActionService.php";
include "DataCollectionService.php";
header('Content-Type: application/json');

$errorCode = null;
$errorMessage = null;
$return = array();

$username = $_POST['username'];
$verificationCode = $_POST['verificationCode'];


unset($_POST['username']);
unset($_POST['verificationCode']);

if(getVerificationNo($username, $verificationCode) == 1){
    $result = setVerified($username, $verificationCode);
    if($result == 1){
        $return = array("verification"=>true);
    }else{
        http_response_code(400);
        $return = array("errorCode" => 400,
                        "errorMessage" => "server Error");
    }
}else{
     http_response_code(400);
        $return = array("errorCode" => 400,
                        "errorMessage" => "Incorrect Verification Code");
}


echo json_encode($return);
die();
?>
