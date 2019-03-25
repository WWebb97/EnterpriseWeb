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
    case "points":
        getPostScore();
        break;
 
}

function getComments(){
    $postId = $_POST["postId"];
    unset($_POST["postId"]);
    if($postId == null){
        http_response_code(400);
        echo json_encode(array("message"=>"Must provide a post Id"));
        die();
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
/*

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
}*/
function vote(){
    $return = null;
    $vote = $_POST['vote'];
    $userId = $_POST['userId'];
    $postId = $_POST['postId'];
    $update = $_POST["update"];
    
    unset($_POST['vote']);
    unset($_POST['userId']);
    unset($_POST['postId']);
    unset($_POST["update"]);
    if($vote === 'ThumbsUp'){
        $voteAm = '+1';
            
    }else if ($vote === 'ThumbsDown'){
        $voteAm = '-1';
    }else{
        $voteAm = null;
    }
    if($vote == null || $userId == null || $postId == null || $update == null){
        http_response_code(400);
        $return = array("voted"=>false,
                       "message"=>"The values of vote, userId and postId must be set.");
        echo json_encode($return);
        die();
    }
    $voted = addVotePost($vote,$postId,$update);
    if($voted["voted"]){
        $voteLog =null;
        if($update === "true"){
            $voteLog = updateVoteLog($userId, $postId, $vote);   
        }else{
            $voteLog = addVoteLog($userId, $postId, $vote);    
        }
        if($voteLog["voted"]){
            $return = array("vote"=> true);
        }else{
            http_response_code(500);
            $return = array("vote"=>false,
                           "message"=>$voteLog["message"]);
        }
    }else{
        http_response_code(500);
        $return = array("voted"=> false,
                       "message"=>$voted["message"]);
    }
    echo json_encode($return);
}

function getPostScore(){
    $postId = $_POST["postId"];
    unset($_POST["postId"]);
    if($postId == null){
        http_response_code(400);
        $return = array("points"=> false,
                       "message"=>"A postId must be given.");
        echo json_encode($return);
        die();
    }
    $points = getPostPoints($postId);
    if($points["score"]=== false){
        http_response_code(500);
        $return = array("score"=> false, 
                       "message"=> $points["message"]);
    }else{
        $return = array("score"=> $points[0]["score"]);
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
       die();
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

function checkVote(){
    $postId = $_POST["postId"];
    $userId = $_POST["userId"];
    
    unset($_POST["postId"]);
    unset($_POST["userId"]);
    
 if($postId == null || $userId == null){
        http_response_code(400);
        echo json_encode(array("message"=>"Must provide a post Id, user id"));
    }
    
    
    $votes = getLastVote($userId, $postId);
    $return = array();
    
    if(isset($votes["error"])){
        http_response_code ($votes["code"]);
        $return = array( "errorCode"=> $votes["code"],
                             "errorMessage"=> $votes["reason"]);
    } else if($votes === False){
        http_response_code(200);
        $return = array("errorCode" => 200,
                       "errorMessage"=> "Unable to find any Votes");    
    }else if ($votes === "error"){
        http_response_code(500);
        $return = array("errorCode" => 500,
                       "errorMessage"=> "Unable to perform database query");    
    }else {
        $return = $votes;
    }
    echo json_encode($return);
    
}


?>