<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "DataCollectionService.php";
require "DataActionService.php";
require "Utilities.php";
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
    /*case "getPost":
        getPost();
        break;*/
    case "fetchPostForEdit":
        fetchPostForEdit();
        break;
    case "listPost":
        listPosts();
        break;
    case "postDeadlineDate":
        postDeadlineDate();
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
    $files = $_FILES["files"];
   
    unset($_POST["name"]);
    unset($_POST["description"]);
    unset($_POST["anon"]);
    unset($_POST["categoryId"]);
    unset($_POST["userId"]);
    unset($_FILES["files"]);
   // echo json_encode(array("name"=>$name, "description"=>$description, "anon"=>$anon, "category"=>$categoryId, "userId"=>$userId));
    $return = array();
    
    if($name == null || $description == null || $anon == null || $categoryId == null || $userId == null){
        http_response_code(400);
        $return = array ("created"=>false,
                        "message"=> "the properties of name, description, anon, categoryId and userId must be set to add a post");
        echo json_encode($return);
        die();
    }
    
    $post = createPost($name, $description, $anon, $categoryId, $userId, $postDate);
    
    if(is_numeric($post)){
        $postId = $post;
        if($files != null){
            $uploaded = uploadFiles($postId, $files);
            if($uploaded["uploaded"] == true){
              /*  $return = array("created"=> true,
                               "postId"=>$postId);*/
                $dbUpdate = addDocument($files["name"], $uploaded["savedName"], "/attachments2/" ,$postId);
                if($dbUpdate["updated"]==  true){
                    $return = array("created"=>true,
                                   "postId"=>$postId);
                }else{
                    $return = array("created"=>false,
                                   "message"=>$dbUpdate["message"]);
                }
            }else{
                http_response_code(500);
                $return = array("created"=>false,
                               "message"=>$uploaded["message"]);
            }
        }else{
              $return = array ("created"=>true,
                        "postId"=>$postId);
        }

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
/*
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
}*/
      


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

function listPosts(){
    $userId = $_POST["userId"];
    unset($_POST["userId"]);
    $sorting = $_POST["sorting"];
    unset($_POST["sorting"]);
    $timing = $_POST["timing"];
    unset($_POST["timing"]);
    $category = $_POST["category"];
    unset($_POST["category"]);
    $return = array();
    $sort = "";
    $time = "";
    
    $lastMonth1Unix = date(strtotime("first day of previous month"));
    $lastMonth2Unix = date(strtotime("last day of previous month"));
    $thisMonth1Unix = date(strtotime("first day of this month"));
    $thisMonth2Unix = date(strtotime("last day of this month"));
    
    switch($sorting){
        case 1:            
            $sort = 'post_date desc';
            break;
        case 2:
            $sort = 'points desc';
            break; 
        case 3:
            $sort = 'points asc';
            break;
        default:
            $sort = 'post_date desc';            
    }
    
    switch($timing){
        case 1:
            $time = "where post.post_date BETWEEN $thisMonth1Unix AND $thisMonth2Unix";
            break;
        case 2:
            $time = "where post.post_date BETWEEN $lastMonth1Unix AND $lastMonth2Unix";
            break;
        case 3: 
            $time = null;
            break;
        default:
            $time = "where post.post_date BETWEEN $thisMonth1Unix AND $thisMonth2Unix"; 
    }    
    
    if($userId == null){
        http_response_code(400);
        $return = array("results"=> false,
                       "message"=> "A userId must be given.");
        echo json_encode($return);
        die();
    }
    $posts = listPostsWithUserId($userId, $sort, $time, $category);
    if($posts["results"] === false){
        http_response_code(500);
        $return = array("results"=>false,
                       "message"=> $posts["message"]);
        
    }
    else if ($posts["results"] === 0){
        $return = array ("results"=>0);
    }
    else{
        //echo json_encode($posts);
        $ratings = listPostRatings($userId);
        $return = $ratings;
        $postsWithoutRatings = $posts["results"];
        //$return = $postsWithoutRatings;
        $postsWithRatings = array();
        if($ratings["results"] === false){
            //error return some kind of data error
            $return = array ("results"=>false);
        }else if ($ratings["results"]=== 0){
            // return all posts with no raitings
            foreach ($postsWithoutRatings as $post){
                $post["post_rating_id"] = null;
                $post["positive"] = null;
                $post["negative"] = null;
                array_push($postsWithRatings, $post);
             }
            $returnPosts = paginateResults($postsWithRatings);
            $return = array("results" => $returnPosts,
                           "pageCount"=>sizeof($returnPosts));
        }else{
            //make sure posts and raitings are joined;
            $postRatings = $ratings["results"];
            foreach ($postsWithoutRatings as $post){
                $post["post_rating_id"] = null;
                $post["positive"] = null;
                $post["negative"] = null;
                foreach($postRatings as $rating){
                    if($rating["post_id"] == $post["post_id"]){
                        $post["post_rating_id"] = $rating["post_rating_id"];
                        $post["positive"] = $rating["positive"];
                        $post["negative"] = $rating["negative"];
                    }
                }
                 array_push($postsWithRatings, $post);
            }
            $returnPosts = paginateResults($postsWithRatings);
            $return = array("results" => $returnPosts,
                           "pageCount"=>sizeof($returnPosts));
            
        }
        /*$returnPosts = paginateResults($posts["results"]);
        $return = array("results"=>$returnPosts,
                       "pageCount"=>sizeof($returnPosts));*/
    }
    echo json_encode($return);
    
    
}

function postDeadlineDate(){
    $thisMonthUnix = date(strtotime("last day of this month"));
    $firstMonthUnix = date(strtotime("first day of this month"));
    $dateUnix = date(strtotime('-7 days', $thisMonthUnix));
    
    echo $dateUnix;
    
    setCookie('Deadline', $dateUnix);
    setCookie('Deadline2', $thisMonthUnix);
    setCookie('Deadline3', $firstMonthUnix);
    
}

?>