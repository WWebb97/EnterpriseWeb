<?php
//require 'dbConnection.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
require_once 'dbConnection.php';


function getUsers ($username, $password){
    $return = array();
    $conn = getConnection();
    if(is_array($conn)){
       $return = array('error' => $conn['error'],
                   'reason'=> $conn['reason'],
                   'code' => 500);
   }else{
        $sql = "select username,user_password, verified, user_id from site_user where username = ? and user_password = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "ss", $usernameIn, $passwordIn);
            $usernameIn = $username;
            $passwordIn = md5($password);
            //var_dump($stmt);
            if(mysqli_stmt_execute($stmt)){
                $users = array();
                mysqli_stmt_bind_result($stmt, $usernameOut, $user_passwordOut, $verified, $user_id);
                while(mysqli_stmt_fetch($stmt)){
                    $user = array("username"=>$usernameOut,
                                 "user_password"=>$user_passwordOut,
                                 "verified"=>$verified,
                                 "user_id"=>$user_id);
                    array_push($users, $user);
                }
              //  var_dump($users);
                if (count($users) === 0){
                    $return = 0;
                }else{
                    $return = $users[0];
                }
            }else{
                $return = "error";
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
    return $return;
}

function GetDepartmentID($department){
     //connect to db
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $result = array('error' => $conn['error'],
                       'reason' => $conn['reason'],
                       'code' => 500);
    }else{
    $sql = "SELECT department_id FROM department WHERE name = '$department'"; 
    $query = mysqli_query($conn, $sql);
    $row = $query->fetch_assoc();
    $result = $row['department_id'];   
    }
    mysqli_close($conn);
    return $result;
}

function getCategories(){
    $return = "";
    $conn = getConnection();
    if(is_array($conn)){
       $return = array('error' => $conn['error'],
                   'reason'=> $conn['reason'],
                   'valid' => false);
   }else{
        $query = "SELECT * FROM category";
        //echo "query = $query <br>";
        $result = mysqli_query($conn, htmlspecialchars($query));               
       // mysqli_store_result($conn);
        if(!$result){
           $return  = "error";
        }
       if(mysqli_num_rows($result) > 0){
            //var_dump($result);
            $categories = array(); 
            while($row = mysqli_fetch_assoc($result)){
                array_push($categories, $row);
            }
            $return = $categories;  
        }else{
            $return = 0;
        }
    }
    
    mysqli_close($conn);
    return $return;
    
    
}

function getVerificationNo($username, $verificationCode){
     //connect to db
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $result = array('error' => $conn['error'],
                       'reason' => $conn['reason'],
                       'code' => 500);
    }else{
    $sql = "SELECT verification_code FROM site_user WHERE username = '$username'"; 
    $query = mysqli_query($conn, $sql);
    $row = $query->fetch_assoc();
        if ($verificationCode === $row['verification_code']){
            $result = 1;
        }else{
            $result = 0;
        }
    }
    mysqli_close($conn);
    return $result;    
}

function getCommentsWithPostId($postId){
    $return = "";
    $conn = getConnection();
    if(is_array($conn)){
       $return = array('error' => $conn['error'],
                   'reason'=> $conn['reason'],
                   'valid' => false);
    
    
    }else{
        $sql = "SELECT u.username, c.contents FROM comments c join site_user u on u.user_id = c.user_id where post_id = ?";
        if($stmt = mysqli_prepare($conn, $sql)){
                mysqli_stmt_bind_param($stmt, "i", $postIdIn);
                $postIdIn = $postId;
                //var_dump($stmt);
                if(mysqli_stmt_execute($stmt)){
                    $comments = array();
                    mysqli_stmt_bind_result($stmt, $username, $content);
                    while(mysqli_stmt_fetch($stmt)){
                        $comment = array("username"=>$username,
                                         "comment"=>$content);
                        array_push($comments, $comment);
                    }
                  //  var_dump($users);
                    if (count($comments) === 0){
                        $return = 0;
                    }else{
                        $return = $comments;
                    }
                }else{
                    $return = array("values"=>false,
                                "message"=>mysqli_error($conn)
                            );
                }
            }
            mysqli_stmt_close($stmt);
         }
    mysqli_close($conn);
    return $return;
}

function listPosts(){
    $return = "";
    $conn = getConnection();
    if(is_array($conn)){
       $return = array('error' => $conn['error'],
                   'reason'=> $conn['reason'],
                   'valid' => false);

   }else{
        $query = "SELECT post.post_id, post.name, post.description, post.post_date, post.user_id, post.points, category.name as 'category', IF(post.post_anon = 1, 'Anon', site_user.username) as 'username' FROM post JOIN site_user ON post.user_id = site_user.user_id JOIN category ON category.category_id = post.category_id";
        //echo "query = $query <br>";
        $result = mysqli_query($conn, htmlspecialchars($query));               
       // mysqli_store_result($conn);
        if(!$result){
           $return  = "error";
        }
       if(mysqli_num_rows($result) > 0){
            //var_dump($result);
            $posts = array(); 
            while($row = mysqli_fetch_assoc($result)){
                array_push($posts, $row);
            }
            $return = $posts;  
        }else{
            $return = 0;
        }
    }
    mysqli_close($conn);
    return $return;
}

?>