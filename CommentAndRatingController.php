<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
require "DataCollectionService.php";
require "DataActionService.php";
header('Content-Type: application/json');
$method = $_POST["method"];
unset($_POST["method"]);
switch($method){
    case "getComments":
        getComments();
        break;
    case "add":
        
        break;
 
}


function getComments(){
    $postId = $_POST["postId"];
    unset($_POST["postId"]);
    $return = array();
    $comments = getCommentsWithPostId($postId);
    if($comments === 0){
        $return = array("comments"=>0);
    }else if (isset($comments["values"])){
        http_response_code(500);
        $return = array("message"=>$comments["message"]);
    }else{
        $return = array("comments"=>$comments);
    }
    echo json_encode($return);
    
}







?>