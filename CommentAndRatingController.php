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
    case "vote":
        vote();
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

function vote(){
    $return = array();
    $voteAm = null;
    
    $vote = $_POST['vote'];
    $userId = $_POST['userId'];
    $postId = $_POST['postId'];
    
    unset($_POST['vote']);
    unset($_POST['userId']);
    unset($_POST['postId']);
    
    if($vote === 'ThumbsUp'){
        $voteAm = '+1';
            
    }else if ($vote === 'ThumbsDown'){
        $voteAm = '-1';
    }else{
        $voteAm = null;
    }
    
    $voteCheck = getVoteLog($userId, $postId);
    
    //$return = $voteCheck;
    
    if($voteCheck == false){
        $addVote = addVotePost($vote, $postId);
        $logVote = addVoteLog($userId, $postId, $vote);
        $return = array("add new post"=>$addVote,
                       "log new vote"=>$logVote,
                       "vote Check"=>$voteCheck);
    }else if($voteCheck == true){
        $addVote = addVotePost($vote, $postId);
        $logVote = updateVoteLog($vote, $userId, $postId);
         $return = array("add old post"=>$addVote,
                       "log old vote"=>$logVote,
                        "vote Check"=>$voteCheck);
    }else{
        http_response_code(500);
        $return = array("errorCode" => 500,
                       "errorMessage" => "unable to vote");
    }
    
    echo json_encode($return);
}

?>