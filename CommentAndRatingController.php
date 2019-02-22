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
    case "addComment":
        addComment();
        break;
 
}


function getComments(){
    $postId = $_POST["postId"];
    unset($_POST["postId"]);
    if($postId == null){
        http_response_code(400);
        echo json_encode(array("message"=>"Must provide a post Id"));
    }
    
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

function addComment(){
    $postId = $_POST["postId"];
    $userId = $_POST["userId"];
    $contents = $_POST["contents"];
    
    unset($_POST["userId"]);
    unset($_POST["postId"]);
    unset($_POST["contents"]);
    
   if($postId == null || $userId == null || $contents == null){
        http_response_code(400);
        echo json_encode(array("message"=>"Must provide a post Id, user id and contents"));
    }
    
    $return = array();
    $commentsReturn = addCommentWithPostId($postId, $userId, $contents);
    
    if($commentsReturn === true){
        $return = array("added"=>true);
    }else{
        $return = array("added"=>false,
                       "message"=>$commentsReturn["message"]);
    }
    
    echo json_encode($return);
    
}





?>