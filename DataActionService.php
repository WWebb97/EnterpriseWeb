<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
require_once 'dbConnection.php';

function registerUser($email, $first_name, $last_name, $department_id, $password, $username){
    
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $result = array('error' => $conn['error'],
                       'reason' => $conn['reason'],
                       'code' => 500);
    }else{
        //Generates random number
        $veriNo = rand(1,99999);
        //hash password
        $hash = md5($password);
        
        $query = "INSERT INTO site_user (first_name, last_name, user_password, department_id, username, email, verification_code, role_id) VALUES ('$first_name', '$last_name', '$hash', $department_id, '$username', '$email', $veriNo, 1)";
        
    
        
        // needs to be in another file whether it be in the controller or another class doesnt matter but it cant be in here the only thing in this class is things that change the database. will be done in a later release
        
        
        if(mysqli_query($conn, $query)){
            $result = 1;
            
            $subject = "Your verification code";
            $message= "Here is your verification code: " . $veriNo;
            $from = "From: db1238b@gre.ac.uk";
            mail($email, $subject, $message, $from);
            
        }else{
            $result = mysqli_errno($conn);

        }
    }
    mysqli_close($conn);
    return $result;
}

function setVerified ($username, $verificationNo){
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $result = array('error' => $conn['error'],
                       'reason' => $conn['reason'],
                       'code' => 500);
    }else{
    $sql = "UPDATE site_user SET verified = 1 WHERE username = '$username' AND verification_code = $verificationNo";
        if (mysqli_query($conn, $sql)){
            $result = 1;
            
        }else{
            $result = "error: " . "<br>" . mysqli_errno($conn);
        }
    }
    mysqli_close($conn);
    return $result;
}


function createPost($name, $description, $anon, $categoryId, $userId, $postDate){
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $return = array('error' => $conn['error'],
                       'reason' => $conn['reason'],
                       'code' => 500);
    }else{
        
           $sql = "insert into post(name, description, post_anon, category_id, user_id, post_date) values (?,?,?,?,?,?)";
        //echo $sql;
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "ssiiii", $nameIn, $descriptionIn, $postAnon, $cat, $user, $pd);
            $nameIn = $name;
            $descriptionIn = $description;
            $postAnon = $anon;
            $cat = $categoryId;
            $user = $userId;
            $pd = $postDate;
           // echo "name = $nameIn, description = $descriptionIn, postAnon = $postAnon, category = $cat, user = $user, postDate = $pd";
            //var_dump($stmt);
            if(mysqli_stmt_execute($stmt)){
                $return = mysqli_insert_id($conn);
            }else{
              // echo mysqli_errno($conn);
                $return = array("created"=>false,
                                "message"=>mysqli_error($conn)
                );
            }
             mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
        return $return;
    }
}

function updateCategoryCount($categoryId){
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $return = array('error' => $conn['error'],
                       'reason' => $conn['reason'],
                       'code' => 500);
    }else{
        
           $sql = "update category set post_count = post_count + 1 , last_post = '".time()."' where category_id = ?";
        //echo $sql;
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "i",$cat);
            $cat = $categoryId;
           // echo "name = $nameIn, description = $descriptionIn, postAnon = $postAnon, category = $cat, user = $user, postDate = $pd";
          //  var_dump($stmt);
            if(mysqli_stmt_execute($stmt)){
                $return = true;
            }else{
             //   echo mysqli_errno($conn);
                $return = false;
            }
             mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
        return $return;
    }
    
}

function updatePost($name, $description, $anon, $categoryId, $postId){
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $return = array('error' => $conn['error'],
                       'reason' => $conn['reason'],
                       'code' => 500);
    }else{
        
           $sql = "UPDATE post SET name = ? , description = ?, post_anon = ?, category_id = ? WHERE post_id = ?";
        //echo $sql;
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "ssiii", $nameIn, $descriptionIn, $postAnon, $cat, $postIdIn);
            $nameIn = $name;
            $descriptionIn = $description;
            $postAnon = $anon;
            $cat = $categoryId;
            $postIdIn = $postId;
           // echo "name = $nameIn, description = $descriptionIn, postAnon = $postAnon, category = $cat, user = $user, postDate = $pd";
          //  var_dump($stmt);
            if(mysqli_stmt_execute($stmt)){
                $return = mysqli_insert_id($link);
            }else{
             //   echo mysqli_errno($conn);
                $return = false;
            }
             mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
        return $return;
    }
}

function addCommentWithPostId($postId, $userId, $contents){
     $return = null;
    $conn = getConnection();
    if(is_array($conn)){
        $return = array('error' => $conn['error'],
                       'reason' => $conn['reason'],
                       'code' => 500);
    }else{
           $sql = "insert into comments(contents, user_id, post_id) values (?,?,?)";
        //echo $sql;
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "sii", $contentsIn, $userIdIn, $postIdIn);
            $contentsIn = $contents;
            $userIdIn = $userId;
            $postIdIn = $postId;
           // echo "name = $nameIn, description = $descriptionIn, postAnon = $postAnon, category = $cat, user = $user, postDate = $pd";
          //  var_dump($stmt);
            if(mysqli_stmt_execute($stmt)){
                $return = true;
            }else{
             //   echo mysqli_errno($conn);
                $return = array(
                    "added"=>false,
                    "message"=>mysqli_error($conn));
            }
             mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
        return $return;
    }
}

function addDocument($name, $location, $postId){
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $return = array('error' => $conn['error'],
                       'reason' => $conn['reason'],
                       'code' => 500);
    }else{
        
           $sql = "insert into files (name, location, post_id) values (?,?,?)";
        //echo $sql;
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "ssi", $nameIn, $LocationIn,$pId);
            $nameIn = $name;
            $locationIn = $location;
            $pId = $postId;
           // echo "name = $nameIn, description = $descriptionIn, postAnon = $postAnon, category = $cat, user = $user, postDate = $pd";
          //  var_dump($stmt);
            if(mysqli_stmt_execute($stmt)){
                $return = true;
            }else{
             //   echo mysqli_errno($conn);
                $return = false;
            }
             mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
        return $return;
    }
    
}


function addVotePost($vote, $postId){
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $return = array('error' => $conn['error'],
                       'reason' => $conn['reason'],
                       'code' => 500);
    }else{
        if($vote == 'ThumbsUp'){
            $voteAm = '+1';
        }else{
            $voteAm = '-1';
        }
        
        $sql = "UPDATE post SET points = points $voteAm WHERE post_id = $postId";
        if(mysqli_query($conn, $sql)){
            $return = true;
        }else{
            $return = mysqli_error($conn);
        }
    }
    mysqli_close($conn);
    return $return;
}

function addVoteLog($userId, $postId, $vote){
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $return = array('error' => $conn['error'],
                       'reason' => $conn['reason'],
                       'code' => 500);
    }else{
        if($vote == 'ThumbsUp'){
            $positive = 1;
            $negative = 0;
        }else{
            $positive = 0;
            $negative = 1;
        }
        $sql = "INSERT INTO post_rating (user_id, post_id, positive, negative) VALUES ( $userId, $postId, $positive, $negative )";    
        if(mysqli_query($conn, $sql)){
            $return = true;
        }else{
            $return = false;
        }
    }
    mysqli_close($conn);
    return $return;  
}

function updateVoteLog($vote, $userId, $postId){
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $return = array('error' => $conn['error'],
                       'reason' => $conn['reason'],
                       'code' => 500);
    }else{
        if($vote == 'ThumbsUp'){
            $positive = 1;
            $negative = 0;
        }else{
            $positive = 0;
            $negative = 1;
        }
        
        $sql = "UPDATE post_rating SET positive = $positive, negative = $negative WHERE user_id = $userId AND post_id = $postId";
        if(mysqli_query($conn, $sql)){
            $return = true;
        }else{
            $return = false;
        }
    }
    mysqli_close($conn);
    return $return;  
}



?>