<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
require_once 'dbConnection.php';

function registerUser($emailAddress){
    $result = null;
    $conn = getConnection();
    if(is_array($conn)){
        $return = array('error' => $conn['error'],
                       'reason' => $conn['reason'],
                       'code' => 500);
    }else{
        //Generates random number
        $veriNo = rand(1,99999);
        $veriDate = date("Y-m-d H:i:s");
        
        //SQL query to be created after db creation
        $query = "INSERT INTO user () VALUES ";
        
        if(mysqli_query($conn, $query)){
            $result = 1;
            ini_set();
            error_reporting();
            $subject = "Your verification code";
            $message= "Here is your verification code: " . $veriNo;
            $from = "From: db1238b@gre.ac.uk";
            mail($emailAddress, $subject, $message, $from);
        }else{
            $result = mysqli_errno($conn);            
        }
    }
    mysqli_close($conn);
    return $result;
}

?>