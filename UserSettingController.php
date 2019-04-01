<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "DataCollectionService.php";
require "DataActionService.php";
header('Content-Type: application/json');
$errorCode = null;
$errorMessage = null;


$method = $_POST["method"];
unset($_POST["method"]);
switch($method){
    case "changepassword":
       changepassword();
        break;

}


function changepassword(){
    if(!(isset($_POST["username"])) || !(isset($_POST["newpassword"]))){
       http_response_code (400); 
       $errorReturn = array("errorCode"=>400,
                           "errorMessage"=>"Password do not match our records");
        echo json_encode($errorReturn);
        die();
    }
    $username = $_POST["username"];
    $newpassword = $_POST["newpassword"];
    unset($_POST["username"]);
    unset($_POST["newpassword"]);
    
    $result = updatepassword($newpassword, $username);
    $return = array();
    if(isset($result["newpassword"])){
        http_response_code ($result["code"]);
        $return = array( "errorCode"=> $result["code"],
                             "errorMessage"=> $result["reason"]);

    } else if($result == 0){
        $return = array("passupdate"=>false);
    }else if ($result === "error"){
        http_response_code(500);
        $return = array("errorCode" => 500,
                       "errorMessage"=> "Unable to perform database query");    
    }else if ($result == 1) {
        $return = array("passupdate"=>true);
    }else{
        http_response_code(500);
        $return = array("errorCode" => 500,
                       "errorMessage" => $result);
    }
        

    
    echo json_encode($return);
    die(); 
}

?>