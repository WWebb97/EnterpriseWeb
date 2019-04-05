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
        $sql = "select username,user_password, verified, user_id, role_id, last_login from site_user where username = ? and user_password = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "ss", $usernameIn, $passwordIn);
            $usernameIn = $username;
            $passwordIn = md5($password);
            //var_dump($stmt);
            if(mysqli_stmt_execute($stmt)){
                $users = array();
                mysqli_stmt_bind_result($stmt, $usernameOut, $user_passwordOut, $verified, $user_id, $role_id, $last_login);
                while(mysqli_stmt_fetch($stmt)){
                    $user = array("username"=>$usernameOut,
                                 "user_password"=>$user_passwordOut,
                                 "verified"=>$verified,
                                 "user_id"=>$user_id,
                                 "role_id"=>$role_id,
                                 "last_login"=>$last_login);
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

function getEmail (){
    $return = array();
    $conn = getConnection();
    if(is_array($conn)){
       $return = array('emails' => false,
                   'message'=> $conn['reason']);
   }else{
        $sql = "SELECT s.email FROM site_user s JOIN role r  ON r.role_id = s.role_id WHERE r.role_name = 'Admin'";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt);
            
            //var_dump($stmt);
            if(mysqli_stmt_execute($stmt)){
                $emails = array();
                mysqli_stmt_bind_result($stmt, $email);
                while(mysqli_stmt_fetch($stmt)){
                    $email = array("email"=>$email);
                    array_push($emails, $email);
                }
              //  var_dump($users);
                if (count($emails) === 0){
                    $return = array('emails' => 0);
                }else{
                    $return = array('emails' => $emails);
                }
            }else{
                $return = array('emails' => false);
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
function getCategoryName($category){
    $conn = getConnection();
    $return = array();
    if(is_array($conn)){
        $return = false;   
    }else{
        $sql = "SELECT name FROM category WHERE category_id = $category";
        $result = mysqli_query($conn, $sql);
        if(!$result){
            $return = false;
        }else{
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

function getPostPoints($postId){
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $return = array ("score"=>$conn["error"],
                        "message"=>$conn["reason"]);
    }else{
        $sql = "select points from post where post_id = ?";
         if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $postIdIn);
            $postIdIn = $postId;
           // echo "name = $nameIn, description = $descriptionIn, postAnon = $postAnon, category = $cat, user = $user, postDate = $pd";
          //  var_dump($stmt);
             if(mysqli_stmt_execute($stmt)){
                    $posts = array();
                    mysqli_stmt_bind_result($stmt, $points);
                    while(mysqli_stmt_fetch($stmt)){
                        $post = array("score"=>$points);
                        array_push($posts, $post);
                    }
                  //  var_dump($users);
                    if (count($posts) === 0){
                        $return = 0;
                    }else{
                        $return = $posts;
                    }
                }else{
                    $return = array("score"=>false,
                                "message"=>mysqli_error($conn)
                            );
                }
            }
            mysqli_stmt_close($stmt);
         }
    mysqli_close($conn);
    return $return;
    
}



function getUserEmailFromPostId($postId){
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $return = array ("user"=>false,
                        "message"=>$conn["reason"]);
    }else{
        $sql = "select u.email from site_user u join post p on p.user_id = u.user_id where p.post_id = ?";
         if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $postIdIn);
            $postIdIn = $postId;
           // echo "name = $nameIn, description = $descriptionIn, postAnon = $postAnon, category = $cat, user = $user, postDate = $pd";
          //  var_dump($stmt);
             if(mysqli_stmt_execute($stmt)){
                    $users = array();
                    mysqli_stmt_bind_result($stmt, $email);
                    while(mysqli_stmt_fetch($stmt)){
                        $user = array("email"=> $email);
                        array_push($users, $user);
                    }
                  //  var_dump($users);
                    if (count($users) === 0){
                        $return = array("user"=>0);
                    }else{
                        $return = array("user"=>$users);
                    }
                }else{
                    $return = array("user"=>false,
                                "message"=>mysqli_error($conn)
                            );
                }
            }
            mysqli_stmt_close($stmt);
         }
    mysqli_close($conn);
    return $return;
    
}

function getSinglePost($postId){
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $return = array ("post"=>false,
                        "message"=>$conn["reason"]);
    }else{
        $sql = "select p.name, p.description, p.post_date, p.points, if(p.post_anon = 1,'Anon',u.username) as 'username', c.name  from post p join site_user u on u.user_id = p.user_id join category c on c.category_id = p.category_id where post_id = ?";
         if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $postIdIn);
            $postIdIn = $postId;
           // echo "name = $nameIn, description = $descriptionIn, postAnon = $postAnon, category = $cat, user = $user, postDate = $pd";
          //  var_dump($stmt);
             if(mysqli_stmt_execute($stmt)){
                    $posts = array();
                    mysqli_stmt_bind_result($stmt, $nameOut, $descOut, $date, $points, $username, $cname);
                    while(mysqli_stmt_fetch($stmt)){
                        $post = array("name"=>$nameOut,
                                     "description"=>$descOut,
                                     "post_date"=>$date,
                                     "points"=>$points,
                                     "username"=>$username,
                                     "category"=>$cname);
                        array_push($posts, $post);
                    }
                  //  var_dump($users);
                    if (count($posts) === 0){
                        $return = array("post"=>0);
                    }else{
                        $return = array("post"=>$posts);
                    }
                }else{
                    $return = array("post"=>false,
                                "message"=>mysqli_error($conn)
                            );
                }
            }
            mysqli_stmt_close($stmt);
         }
    mysqli_close($conn);
    return $return;
    
}

function listPostsWithUserId($userId, $sorting, $time, $category){
    $return = "";
    $cat = null;
    $conn = getConnection();
    if(is_array($conn)){
       $return = array('results' => $conn['error'],
                   'message'=> $conn['reason']);
   }else{
        if($category == 0){
            $cat = null;
        }else{
            if($time == null){
                $cat = "WHERE post.category_id = $category";
            }else{
                $cat = "AND post.category_id = $category";
            }
        }
        $sql = "SELECT post.post_id, post.name, post.description, post.post_date, post.user_id, post.points, category.name as 'category', IF(post.post_anon = 1, 'Anon', site_user.username) as 'username' FROM post JOIN site_user ON post.user_id = site_user.user_id JOIN category ON category.category_id = post.category_id $time $cat AND muted = 0 order by $sorting";//
        $result = mysqli_query($conn, $sql);
             if(!$result){
                      $return = array("results"=>false,
                                "message"=>mysqli_error($conn)
                            );
                }else{
                    $posts = array(); 
                    while($row = mysqli_fetch_assoc($result)){
                        array_push($posts, $row);
                    }
                    $return = array("results"=>$posts);
                }
            
            mysqli_stmt_close($stmt);
         }
    mysqli_close($conn);
    return $return;
}


function listPostRatings($userId){
     $return = "";
    $cat = null;
    $conn = getConnection();
    if(is_array($conn)){
       $return = array('results' => $conn['error'],
                   'message'=> $conn['reason']);

   }else{
        $sql = "SELECT post_rating_id, user_id, post_id, positive, negative from post_rating where user_id = ?";
        //echo "query = $sql <br>";
        //$result = mysqli_query($conn, htmlspecialchars($query));               
       // mysqli_store_result($conn);
         if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $userIdIn);
            $userIdIn = $userId;

           // echo "name = $nameIn, description = $descriptionIn, postAnon = $postAnon, category = $cat, user = $user, postDate = $pd";
          //  var_dump($stmt);
             if(mysqli_stmt_execute($stmt)){
                    $posts = array();
                    mysqli_stmt_bind_result($stmt,$postRatingId,$userIDOut, $PostID, $positive, $negative);
                    while(mysqli_stmt_fetch($stmt)){
                        $post = array(
                                     "post_rating_id"=>$postRatingId,
                                    "user_id"=>$userIDOut,
                                    "post_id"=>$PostID,
                                     "positive"=>$positive,
                                     "negative"=>$negative);
                        array_push($posts, $post);
                    }
                  //  var_dump($users);
                    if (sizeof($posts) === 0){
                        $return = array("results"=>0);
                    }else{
                        $return = array("results"=>$posts);
                    }
                }else{
                    $return = array("results"=>false,
                                "message"=>mysqli_error($conn)
                            );
                }
            }
            mysqli_stmt_close($stmt);
         }
    mysqli_close($conn);
    return $return;
}


function fetchPost($postId, $userId){
    $return = "";
    $conn = getConnection();
    if(is_array($conn)){
       $return = array('error' => $conn['error'],
                   'reason'=> $conn['reason'],
                   'valid' => false);

    }else{
        $sql = "SELECT * FROM post WHERE post_id = $postId AND user_id = $userId";
        $result = mysqli_query($conn, $sql);               
       // mysqli_store_result($conn);
        if(!$result){
           $return  = "error";
        }
       if(mysqli_num_rows($result) > 0){
            //var_dump($result);
            $post = array(); 
            while($row = mysqli_fetch_assoc($result)){
                array_push($post, $row);
            }
            $return = $post;  
        }else{
            $return = 0;
        }
    }
    mysqli_close($conn);
    return $return;
}

function getVoteLog($userId, $postId){
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $return = array('error' => $conn['error'],
                       'reason' => $conn['reason'],
                       'code' => 500);
    }else{
        $sql = "SELECT * FROM post_rating WHERE user_id = $userId AND post_id = $postId";
        $query = mysqli_query($conn, $sql);
        
        if(mysqli_num_rows($query)>=1){
            
            $return = true;
            
        }else{
            $return = false;
        }
    }
    mysqli_close($conn);
    return $return;  
}

function getPermissionsList(){
     $return = "";
    $conn = getConnection();
    if(is_array($conn)){
       $return = array('results'=>false,
                       'message'=>$conn["reason"]);
   }else{
        $query = "SELECT * FROM permission";
        //echo "query = $query <br>";
        $result = mysqli_query($conn, htmlspecialchars($query));               
       // mysqli_store_result($conn);
        if(!$result){
            $return  = array("results"=>false,
                           "message"=>mysqli_error($conn));
        }
       if(mysqli_num_rows($result) > 0){
            //var_dump($result);
            $permissions = array(); 
            while($row = mysqli_fetch_assoc($result)){
                array_push($permissions, $row);
            }
            $return = array("results"=>$permissions);  
        }else{
            $return = array("results"=>0);
        }
    }
    
    mysqli_close($conn);
    return $return;
}

function getAllUsers(){
    $return = "";
    $conn = getConnection();
    if(is_array($conn)){
       $return = array('results'=>false,
                       'message'=>$conn["reason"]);
   }else{
        $query = "SELECT u.user_id, u.username, u.last_login, u.role_id, r.role_name FROM site_user u JOIN role r ON r.role_id = u.role_id";
        //echo "query = $query <br>";
        $result = mysqli_query($conn, htmlspecialchars($query));               
       // mysqli_store_result($conn);
        if(!$result){
           $return  = array("results"=>false,
                           "message"=>mysqli_error($conn));
        }
       if(mysqli_num_rows($result) > 0){
            //var_dump($result);
            $users = array(); 
            while($row = mysqli_fetch_assoc($result)){
                array_push($users, $row);
            }
            $return = array("results"=>$users);  
        }else{
            $return = array("results"=>0);
        }
    }
    
    mysqli_close($conn);
    return $return;
    
    
}


function getFlaggedPosts(){
    $return = "";
    $conn = getConnection();
    if(is_array($conn)){
       $return = array('results'=>false,
                       'message'=>$conn["reason"]);
   }else{
        $query = "SELECT p.post_id, su.username, p.name as 'title', p.description, p.post_date, c.name as 'category' FROM post p JOIN site_user su ON su.user_id = p.user_id JOIN category c ON c.category_id = p.category_id where p.flagged = 1 and p.muted = 0";
        //echo "query = $query <br>";
        $result = mysqli_query($conn, htmlspecialchars($query));               
       // mysqli_store_result($conn);
        if(!$result){
           $return  = array("results"=>false,
                           "message"=>mysqli_error($conn));
        }
       if(mysqli_num_rows($result) > 0){
            //var_dump($result);
            $posts = array(); 
            while($row = mysqli_fetch_assoc($result)){
                array_push($posts, $row);
            }
            $return = array("results"=>$posts);  
        }else{
            $return = array("results"=>0);
        }
    }
    
    mysqli_close($conn);
    return $return;
}

function getFileDetails($fileId){
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $return = array ("files"=>false,
                        "message"=>$conn["reason"]);
    }else{
        $sql = "select file_id, location, post_id, saved_name, actual_name from files where file_id = ?";
         if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $fileIdIn);
            $fileIdIn = $fileId;
           // echo "name = $nameIn, description = $descriptionIn, postAnon = $postAnon, category = $cat, user = $user, postDate = $pd";
          //  var_dump($stmt);
             if(mysqli_stmt_execute($stmt)){
                    $files = array();
                    mysqli_stmt_bind_result($stmt, $fileIDOut, $location, $postID, $savedName, $actualName);
                    while(mysqli_stmt_fetch($stmt)){
                        $file = array("file_id"=>$fileIDOut,
                                     "location"=>$location,
                                     "post_id"=>$postID,
                                     "saved_name"=>$savedName,
                                     "actual_name"=>$actualName);
                        array_push($files, $file);
                    }
                  //  var_dump($users);
                    if (count($files) === 0){
                        $return = array("files"=>0);
                    }else{
                        $return = array("files"=>$files);
                    }
                }else{
                    $return = array("score"=>false,
                                "message"=>mysqli_error($conn)
                            );
                }
            }
            mysqli_stmt_close($stmt);
         }
    mysqli_close($conn);
    return $return;
    
}



function getPostFiles($postId){
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $return = array ("files"=>false,
                        "message"=>$conn["reason"]);
    }else{
        $sql = "select file_id, location, saved_name, actual_name from files where post_id = ?";
         if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $postIdIn);
            $postIdIn = $postId;
           // echo "name = $nameIn, description = $descriptionIn, postAnon = $postAnon, category = $cat, user = $user, postDate = $pd";
          //  var_dump($stmt);
             if(mysqli_stmt_execute($stmt)){
                    $files = array();
                    mysqli_stmt_bind_result($stmt, $fileIDOut, $location, $saved_name, $actual_name);
                    while(mysqli_stmt_fetch($stmt)){
                        $file = array("file_id"=>$fileIDOut,
                                      "location"=>$location,
                                     "saved_name"=>$saved_name,
                                     "actual_name"=>$actual_name);
                        array_push($files, $file);
                    }
                  //  var_dump($users);
                    if (count($files) === 0){
                        $return = array("files"=>0);
                    }else{
                        $return = array("files"=>$files);
                    }
                }else{
                    $return = array("files"=>false,
                                "message"=>mysqli_error($conn)
                            );
                }
            }
            mysqli_stmt_close($stmt);
         }
    mysqli_close($conn);
    return $return;
    
}

function fetchIdeasByDept($time){
    $return = "";
    $cat = null;
    $conn = getConnection();
    if(is_array($conn)){
       $return = array('results' => $conn['error'],
                   'message'=> $conn['reason']);
    }
    
    $sql = "SELECT d.name, count(p.post_id) as 'value' FROM post p JOIN site_user su on p.user_id = su.user_id JOIN department d on su.department_id = d.department_id $time GROUP BY d.name ";
    //echo "query = $sql <br>";
    $result = mysqli_query($conn, $sql);
    if(!$result){ 
     $return = array("results"=>false,
                "message"=>mysqli_error($conn)
                );
    }else{
       $posts = array(); 
        while($row = mysqli_fetch_array($result)){
           $posts[]= array($row['name'], $row['value']);
        }
     $return = $posts;
    }
    mysqli_close($conn);
    return $return;

}

function fetchIdeasByDate($time){
    $return = "";
    $cat = null;
    $conn = getConnection();
    if(is_array($conn)){
       $return = array('results' => $conn['error'],
                   'message'=> $conn['reason']);
    }
    
    $sql = "SELECT FROM_UNIXTIME(`post_date`, '%D %M') as 'date', count(post_id) as 'value' FROM post $time Group by Date";
    //echo "query = $sql <br>";
    $result = mysqli_query($conn, $sql);
    if(!$result){ 
     $return = array("results"=>false,
                "message"=>mysqli_error($conn)
                );
    }else{
       $posts = array(); 
        while($row = mysqli_fetch_array($result)){
           $posts[]= array($row['date'], $row['value']);
        }
     $return = $posts;
    }
    mysqli_close($conn);
    return $return;

}

function topPosters($time){
     $return = "";
    $cat = null;
    $conn = getConnection();
    if(is_array($conn)){
       $return = array('results' => $conn['error'],
                   'message'=> $conn['reason']);
    }
    
    $sql = "SELECT u.username, COUNT(p.post_id) as value FROM post p JOIN site_user u on u.user_id = p.user_id $time GROUP BY p.user_id LIMIT 5";
    //echo "query = $sql <br>";
    $result = mysqli_query($conn, $sql);
    if(!$result){ 
     $return = array("results"=>false,
                "message"=>mysqli_error($conn)
                );
    }else{
       $posts = array(); 
        while($row = mysqli_fetch_array($result)){
           $posts[]= array($row['username'], $row['value']);
        }
     $return = $posts;
    }
    mysqli_close($conn);
    return $return;
}

function fetchSelectedPosts($time){
    $return = "";
  
    $conn = getConnection();
    if(is_array($conn)){
       $return = array('results' => $conn['error'],
                   'message'=> $conn['reason']);

   }else{
        
        $sql = "SELECT p.name as 'Post Title',p.description as 'Description', FROM_UNIXTIME(p.post_date) as 'Date' ,p.points as 'Points', c.name as 'Category Name' , CONCAT('https://stuweb.cms.gre.ac.uk/~db1238b/EnterpriseWeb/attachments2/', f.saved_name) as 'Download File' FROM post_rating pr right JOIN site_user su on su.user_id = pr.user_id left JOIN post p ON p.post_id = pr.post_id JOIN category c on p.category_id = c.category_id left JOIN files f ON f.post_id = p.post_id WHERE su.role_id = 2 AND pr.positive = 1 $time";
        
        //echo "query = $sql <br>";
        $result = mysqli_query($conn, $sql);
             if(!$result){
                      $return = array("results"=>false,
                                "message"=>mysqli_error($conn)
                            );
                 
                }else{
            
                 
                    $posts = array(); 
                    while($row = mysqli_fetch_assoc($result)){
                        array_push($posts, $row);
                    }
                    $return = array("results"=>$posts);
                }
            
            mysqli_stmt_close($stmt);
         }
    mysqli_close($conn);
    return $return;
    
    
}


function getPageReports(){
    $return = "";
    $conn = getConnection();
    if(is_array($conn)){
       $return = array('reports'=>false,
                       'message'=>$conn["reason"]);
   }else{
        $query = "SELECT page_name, view_count from page_reporting";
        //echo "query = $query <br>";
        $result = mysqli_query($conn, htmlspecialchars($query));               
       // mysqli_store_result($conn);
        if(!$result){
           $return  = array("reports"=>false,
                           "message"=>mysqli_error($conn));
        }
       if(mysqli_num_rows($result) > 0){
            //var_dump($result);
            $reports = array(); 
            while($row = mysqli_fetch_assoc($result)){
                array_push($reports, $row);
            }
            $return = array("reports"=>$reports);  
        }else{
            $return = array("reports"=>0);
        }
    }
    
    mysqli_close($conn);
    return $return;
}

function getInstanceReport(){
        $return = "";
    $conn = getConnection();
    if(is_array($conn)){
       $return = array('reports'=>false,
                       'message'=>$conn["reason"]);
   }else{
        $query = "SELECT browser_name, view_time from page_reporting order by browser_name desc";
        //echo "query = $query <br>";
        $result = mysqli_query($conn, htmlspecialchars($query));               
       // mysqli_store_result($conn);
        if(!$result){
           $return  = array("reports"=>false,
                           "message"=>mysqli_error($conn));
        }
       if(mysqli_num_rows($result) > 0){
            //var_dump($result);
            $reports = array(); 
            while($row = mysqli_fetch_assoc($result)){
                array_push($reports, $row);
            }
            $return = array("reports"=>$reports);  
        }else{
            $return = array("reports"=>0);
        }
    }
    
    mysqli_close($conn);
    return $return;
}



?>