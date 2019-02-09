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
    $sql = "UPDATE member SET verified = 1 WHERE username = '$username' AND verification_code = $verificationNo";
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
        $result = array('error' => $conn['error'],
                       'reason' => $conn['reason'],
                       'code' => 500);
    }else{
        
           $sql = "insert into category(name, description, post_anon, category_id, user_id, post_date) values (?,?,?,?,?,?)";
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "ssssss", $nameIn, $desscriptionIn, $postAnon, $cat, $user, $pd);
            $nameIn = $name;
            $descriptionIn =$description;
            $postAnon = $anon;
            $cat = $categoryId;
            $user = $userId;
            $pd = $postDate;
            //var_dump($stmt);
            if(mysqli_stmt_execute($stmt)){
                $return = true;
            }else{
                $return = false;
            }
             mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
        return $return;
    }
}


?>