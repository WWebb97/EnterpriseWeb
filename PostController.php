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
        getPost();
        break;
    case "addAttachment":
        uploadFiles();
        break;
    case "fetchPostForEdit":
        fetchPostForEdit();
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
    if(is_numeric($post)){
        updateCategoryCount($categoryId);
        $return = array ("created"=>true,
                        "postId"=>$post);

    }else{
          http_response_code(500);
          $return = array ("created"=>$post["created"],
                        "message"=>$post["message"]);

        
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

function getPost(){
    
    $posts = listPosts();
    $return = array();
    if(isset($posts["error"])){
        http_response_code ($posts["code"]);
        $return = array( "errorCode"=> $posts["code"],
                             "errorMessage"=> $posts["reason"]);
    } else if($posts === 0){
        http_response_code(500);
        $return = array("errorCode" => 500,
                       "errorMessage"=> "Unable to find any posts");    
    }else if ($posts === "error"){
        http_response_code(500);
        $return = array("errorCode" => 500,
                       "errorMessage"=> "Unable to perform database query");    
    }else {
        $return = $posts;
    }
    echo json_encode($return);
}
      
function uploadFiles(){
//start the image upload code
    $postId = $_POST["postId"];
    $files = $_POST["files"];
    unset($_POST["postId"]);
    unset($_POST["files"]);
    //var_dump($files);
    $fileId= $createPost;
    $error=array();
    //$files = array();
    $extension=array("docx, pdf");
    $fileCount = 1;
    $uploadSuccess = 1;
    foreach($files["files"]["tmp_name"] as $key=>$tmp_name)
            {
                $file_name=$_FILES["files"]["name"][$key];
                $file_tmp=$_FILES["files"]["tmp_name"][$key];
                //$newFileName= "image".$imageCount."postid".$fileId;
                $fileCount ++;
                $newFileName = $file_name."postId".$postId;
                $ext=pathinfo($file_name,PATHINFO_EXTENSION);
                $error = null;
                if(in_array($ext,$extension))
                {
                    if(!file_exists("attachment2/".$newFileName))
                    {
                        move_uploaded_file($file_tmp=$_FILES["files"]["tmp_name"][$key],"attachment2/".$newFileName.".".$ext);
                        $finalName= $newFileName.".".$ext;
                        array_push($files, $finalName);
                    }
                    else
                    {
                        $uploadSuccess = 0;
                    }
                }
                else
                {
                    array_push($error,"$file_name");
                }
            }

    //var_dump($error);
    //var_dump($files);
    $failure = 0;
    var_dump($error);
   /* foreach($files as $var){
        $insert = InsertImage("/coursework/images/", $var, $fileId); 
        if(!($insert)){
            $failure = 1;
        }
    }*/
    /*if($failure === 1){
        echo "There was an error adding images to the post. The post was still created successfully.";
    }else{
        echo "Post succsessfully created.";
    }*/
    
    
}


function fetchPostForEdit(){
    
    $postId = $_POST['postId'];
    $userId = $_POST['userId'];
    
    unset($_POST['postId']);
    unset($_POST['userId']);
    
    $posts = fetchPost($postId, $userId);
    $return = array();
    if(isset($posts["error"])){
        http_response_code ($posts["code"]);
        $return = array( "errorCode"=> $posts["code"],
                             "errorMessage"=> $posts["reason"]);
    } else if($posts === 0){
        http_response_code(500);
        $return = array("errorCode" => 500,
                       "errorMessage"=> "Unable to find any posts");    
    }else if ($posts === "error"){
        http_response_code(500);
        $return = array("errorCode" => 500,
                       "errorMessage"=> "Unable to perform database query");    
    }else {
        $return = $posts;
    }
    echo json_encode($return);
}

?>