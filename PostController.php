<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
require "DataCollectionService.php";
require "DataActionService.php";
header('Content-Type: application/json');
$errorCode = null;
$errorMessage = null;
$method = $_POST["method"];
unset($_POST["method"]);
switch($method){
    case "categories":
        categories();
        break;
    case "addPost":
        addPost();
        break;
    case "editPost":
        editPost();
        break;
    case "deletPost":
        break;
    case "getPost":
        break;
}
    
function categories(){
    $categories = getCategories();
    $return = array();
    if(isset($categories["error"])){
        http_response_code ($categories["code"]);
        $return = array( "errorCode"=> $categories["code"],
                             "errorMessage"=> $categories["reason"]);
    } else if($categories === 0){
        http_response_code(500);
        $return = array("errorCode" => 500,
                       "errorMessage"=> "Unable to find any categories");    
    }else if ($categories === "error"){
        http_response_code(500);
        $return = array("errorCode" => 500,
                       "errorMessage"=> "Unable to perform database query");    
    }else {
        $return = $categories;
    }
    echo json_encode($return);
}


function addPost(){
    // name, description, anon, postdate, cat, userid, points;
    $postDate = time();
    $name = $_POST["name"];
    $description = $_POST["description"];
    $anon = $_POST["anon"];
    $categoryId = $_POST["categoryId"];
    $userId = $_POST["userId"];
    
    unset($_POST["name"]);
    unset($_POST["description"]);
    unset($_POST["anon"]);
    unset($_POST["categoryId"]);
    unset($_POST["userId"]);
    
    $return = array();
    
    if($name == null || $description == null || $anon == null || $categoryId == null || $userId == null){
        http_response_code(400);
        $return = array ("errorCode"=>400,
                        "errorMessage"=> "the properties of name, description, anon, categoryId and userId must be set to add a post");
        echo json_encode($return);
        die();
    }
    
    $post = createPost($name, $description, $anon, $categoryId, $userId, $postDate);
    if($post === false){
         http_response_code(500);
          $return = array ("errorCode"=>500,
                        "errorMessage"=> "Unable to create post");
    }else{
        updateCategoryCount($categoryId);
        $return = array ("postCreated"=>true,
                        "postId"=>$post);
    }
     echo json_encode($return);
}
       
function editPost(){
    // name, description, anon, postdate, cat, postid;
    $name = $_POST["name"];
    $description = $_POST["description"];
    $anon = $_POST["anon"];
    $categoryId = $_POST["categoryId"];
    $postId = $_POST["postId"];
    
    unset($_POST["name"]);
    unset($_POST["description"]);
    unset($_POST["anon"]);
    unset($_POST["categoryId"]);
    unset($_POST["postId"]);
    
    $return = array();
    
    if($name == null || $description == null || $anon == null || $categoryId == null || $postId == null){
        http_response_code(400);
        $return = array ("errorCode"=>400,
                        "errorMessage"=> "the properties of name, description, anon, categoryId and postId must be set to add a post");
        echo json_encode($return);
        die();
    }
    
    $post = updatePost($name, $description, $anon, $categoryId, $postId);
    if($post === false){
         http_response_code(500);
          $return = array ("errorCode"=>500,
                        "errorMessage"=> "Unable to update post");
    }else{
        updateCategoryCount($categoryId);
        $return = array ("postupdated"=>true,
                        "postId"=>$post);
    }
     echo json_encode($return);
           
}
       
       
function deletePost(){};
function getPost(){};
?>