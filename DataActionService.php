<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
require_once 'dbConnection.php';

function registerUser($email, $first_name, $last_name, $department, $password, $username){
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
        
        //SQL query to be created after db creation
        $query = "INSERT INTO site_user (first_name, last_name, user_password, department_id, username, email, verification_code) VALUES ('$first_name, $last_name','$hash', $department, '$username', '$username@gre.ac.uk', $verino)";
        
        // needs to be in another file whether it be in the controller or another class doesnt matter but it cant be in here the only thing in this class is things that change the database. will be done in a later release
     
        if(mysqli_query($conn, $query)){
            $result = 1;
            ini_set();
            error_reporting();
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

function setVerified(email, $verification){
    //connect to db
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $result = array('error' => $conn['error'],
                       'reason' => $conn['reason'],
                       'code' => 500);
    }else{
        $sql = "UPDATE table SET verified = 'y' WHERE email = '$email' AND verifiedNumber = '$verification'";
        if (mysqli_query($conn, $sql)){
            $result = 1;
            
        }else{
            $result = "error: " . "<br>" . mysqli_errno($conn);
        }
    }
    mysqli_close($conn);
    return $result;
}


?>