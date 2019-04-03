<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
require 'config.php';
require "DataCollectionService.php";
require "Utilities.php";
header('Content-Type: application/json');
$method = $_POST["method"];
unset($_POST["method"]);
switch($method){
    case "uploadNotification":
        uploadNotificaion();
        break;
    case "commentNotification":
        
        break;

    
}
    
function uploadNotification() {
    $postName = $_POST["postName"];
    $categoryID = $_POST["categoryId"];
    unset($_POST["postId"]);
    unset($_POST["categoryId"]);
    if( $postName == null || $categoryID == null){
        http_response_code(400);
        $return = array("sent"=> false,
                       "message"=>"postId must be given");
        echo json_encode($return);
        die();
    }
    $emails = getEmail();
    $emailsResults = $emails["emails"];
    if($emailsResults === false){
        http_response_code(500);
        $return = array("sent"=>false,
                       "message"=>"Unable to retrieve emails.");
    }else if($emailResults === 0){
        $return = array("sent"=>false,
                       "message"=>"Unable to find admin emails.");
    }else{
        $category = getCategoryName($categoryID);
        if ($category === false){
            http_response_code(500);
            $return = array("sent"=>false,
                           "message"=>"Unable to get category Name");
        }else if($category === 0){
            $return = array("sent"=>false,
                           "message"=>"Unable to find any categories");
        }
        else{
            $errorCount = 0;
            $subject = "New post created.";
            $message = "A new post has been created with the title of $postName and the category of $category"
            foreach($emailResults as $email){
                $to = $email["email"];
                $emailSent = sendMail($to, $subject, $message);
                if(!$emailSent){
                    $errorCount += 1;
                }
            }
            $message = null;
            if($errorCount > 0){
                $message = "$errorCounts emails where not able to be send however some emails have still gone through.";
            }
            $return = array("sent"=>true,
                           "message"=>$message);
        }
    }
     echo json_encode($message);   
}
    
    
    
    
    
    
/*    
    foreach($emails as $val){
        $to = $val;
        $subject = "A new idea has been posted!";
        $message = "New idea created here: https://stuweb.cms.gre.ac.uk/~ad9047o/EnterpriseWeb/ideacomment.html?postId=$postId";
        $from = "From: db1238b@gre.ac.uk";
        $success = mail($to, $subject, $message, $from);  
        
        if (!$success) {
            echo error_get_last()["message"];
        }
    }*/
   


?>