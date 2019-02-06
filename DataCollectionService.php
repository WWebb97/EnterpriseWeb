<?php
//require 'dbConnection.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
require_once 'dbConnection.php';


/*
function getUsers($username, $password){
  $return = array();
   $conn = getConnection();
   if(is_array($conn)){
       $return = array('error' => $conn['error'],
                   'reason'=> $conn['reason'],
                   'code' => 500);
   }else{
        $query = "SELECT username, user_password, verified FROM site_user WHERE username = '$username' AND user_password = md5('$password')";
        //echo "query = $query <br>";
        $result = mysqli_query($conn, htmlspecialchars($query));
       // mysqli_store_result($conn);
       if(!$result){
           /*echo "query failure <br>";
           echo "connection error: ". mysqli_connect_error($conn);
           echo "<br>";
           echo "query error: ". mysqli_error($conn);
           echo "<br>";*/
        //   $return = "error";
    //   }
      //  else if(mysqli_num_rows($result) > 0){
            //echo "number of rows returned: ". mysqli_num_rows($result). "<br>";
        //    $row = mysqli_fetch_assoc($result);
/*            echo "Row from sql query <br>";
            var_dump($row);
            echo "<br>";*/
          //  $return = $row;  
    //    }else{
            /*echo "0 results found option <br>";
            echo mysqli_error($conn);
            echo "<br>";*/
      //      $return = 0;
       // }
    //    mysqli_free_result($conn);

   //}
    
    //mysqli_close($conn);
    //return $return;
//}


function getUsers ($username, $password){
    $return = array();
    $conn = getConnection();
    if(is_array($conn)){
       $return = array('error' => $conn['error'],
                   'reason'=> $conn['reason'],
                   'code' => 500);
   }else{
        $sql = "select username,user_password, verified from site_user where username = ? and user_password = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "ss", $usernameIn, $passwordIn);
            $usernameIn = $username;
            $passwordIn = md5($password);
            //var_dump($stmt);
            if(mysqli_stmt_execute($stmt)){
                $users = array();
                mysqli_stmt_bind_result($stmt, $usernameOut, $user_passwordOut, $verified);
                while(mysqli_stmt_fetch($stmt)){
                    $user = array("username"=>$usernameOut,
                                 "user_password"=>$user_passwordOut,
                                 "verified"=>$verified);
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

function getVerificationNo($email, $verify){
     //connect to db
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $return = array('error' => $conn['error'],
                       'reason' => $conn['reason'],
                       'code' => 500);
    }else{
    $sql = "SELECT verifyNo FROM table WHERE email = '$username'"; 
    $query = mysqli_query($conn, $sql);
    $row = $query->fetch_assoc();
        if ($verify === $row['verifiedNumber']){
            $result = 1;
        }else{
            $result = 0;
        }
    }
    mysqli_close($conn);
    return $result;    
}


?>