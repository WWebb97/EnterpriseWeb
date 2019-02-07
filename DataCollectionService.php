<?php
//require 'dbConnection.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
require_once 'dbConnection.php';


function getUsers($username, $password){
  $return = array();
   $conn = getConnection();
   if(is_array($conn)){
       $return = array('error' => $conn['error'],
                   'reason'=> $conn['reason'],
                   'code' => 500);
   }else{
        $query = "SELECT username, user_password FROM SITEUSER WHERE username = '$username' AND user_password = md5('$password') and verified = 1";
        //echo "query = $query <br>";
        $result = mysqli_query($conn, htmlspecialchars($query));
       // mysqli_store_result($conn);
       if(!$result){
           /*echo "query failure <br>";
           echo "connection error: ". mysqli_connect_error($conn);
           echo "<br>";
           echo "query error: ". mysqli_error($conn);
           echo "<br>";*/
           $return = "error";
       }
        else if(mysqli_num_rows($result) > 0){
            //echo "number of rows returned: ". mysqli_num_rows($result). "<br>";
            $row = mysqli_fetch_assoc($result);
/*            echo "Row from sql query <br>";
            var_dump($row);
            echo "<br>";*/
            $return = $row;  
        }else{
            /*echo "0 results found option <br>";
            echo mysqli_error($conn);
            echo "<br>";*/
            $return = 0;
        }
    //    mysqli_free_result($conn);

   }
    
    mysqli_close($conn);
    return $return;
}

function getUsersAndEmails($username, $email){
    $return = "";
    $conn = getConnection();
    if(is_array($conn)){
       $return = array('error' => $conn['error'],
                   'reason'=> $conn['reason'],
                   'valid' => false);
   }else{
        $query = "SELECT username FROM SITEUSER WHERE username = '$username'";
        //echo "query = $query <br>";
        $result = mysqli_query($conn, htmlspecialchars($query));               
       // mysqli_store_result($conn);
        if(!$result){
          /* echo "query failure <br>";
           echo "connection error: ". mysqli_connect_error($conn);
           echo "<br>";
           echo "query error: ". mysqli_error($conn);
           echo "<br>";*/
           $return  = "error";
        }
       if(mysqli_num_rows($result) > 0){
            //echo "number of rows returned: ". mysqli_num_rows($result). "<br>";
            //$row = mysqli_fetch_assoc($result);
            //echo "Row from sql query <br>";
            $users = array(); 
            while($row = mysqli_fetch_assoc($result)){
/*                var_dump($row);
                echo "<br>";*/
                array_push($users, $row);
            }
           // var_dump($users);
            $return = $users;  
        }else{
           /* echo "0 results found option <br>";
            echo mysqli_error($conn);
            echo "<br>";*/
            $return = "No Results";
        }
       // mysqli_free_result($conn);
    }
    
    mysqli_close($conn);
    return $return;
    
    
}


//not yet finished
function getVerificationNo($email, $verify){
     //connect to db
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $return = array('error' => $conn['error'],
                       'reason' => $conn['reason'],
                       'code' => 500);
    }else{
    $sql = "SELECT verification_code FROM table WHERE email = '$username'"; 
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

function GetDepartmentID($department){
     //connect to db
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $return = array('error' => $conn['error'],
                       'reason' => $conn['reason'],
                       'code' => 500);
    }else{
    $sql = "SELECT department_id FROM department WHERE name = '$department'"; 
    $query = mysqli_query($conn, $sql);
    $row = $query->fetch_assoc();
       
    }
    mysqli_close($conn);
    return $row['department_id'];    
}


?>