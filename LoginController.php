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
    case "login":
        login();
        break;
    case "setLoginTime":
        setLoginTime();
        break;
}


function login(){
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
        $return = array("login"=>false,
                        "verified"=>false);
    }else if ($user === "error"){
        http_response_code(500);
        $return = array("errorCode" => 500,
                       "errorMessage"=> "Unable to perform database query");    
    }else {
        if($user["verified"] == 1){
            if($user["role_id"] == 3){
                $return = array("login"=>"Banned",
                           "verified"=>false,
                           "user_id"=>$user["user_id"],
                            "role_id"=>$user["role_id"]);
            }else{
                 $return = array("login"=>true,
                            "verified"=>true,
                           "user_id"=>$user["user_id"],
                            "role_id"=>$user["role_id"],
                            "last_login"=>$user["last_login"]);
            }



        }else{   
            $return = array("login"=>true,
                           "verified"=>false,
                           "user_id"=>$user["user_id"],
                            "role_id"=>$user["role_id"],
                            "last_login"=>$user["last_login"]);
        }

    }
    echo json_encode($return);
    die(); 
}

function setLoginTime(){
    $userId = $_POST["userId"];
    $timestamp = time();
    unset($_POST["userId"]);
    
    $login = updateLoginTime($userId, $timestamp);
    
    if($login == false){
        http_response_code(500);
          $return = array ("errorCode"=>500,
                        "errorMessage"=> "Unable to update post");
        
    }else{
        $return = array("updated"=>true);
    }
    
    echo json_encode($return);
    die();
}

?>