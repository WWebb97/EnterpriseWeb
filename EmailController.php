<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'config.php';
require "DataCollectionService.php";
require "Utilities.php";
header('Content-Type: application/json');
$method = $_POST["method"];
unset($_POST["method"]);
switch($method){
    case "uploadNotification":
        uploadNotification();
        break;
    case "commentNotificaiton":
        commentNotificaiton();
        break;

    
}
    
function uploadNotification() {
    $postName = $_POST["postName"];
    $categoryID = $_POST["categoryId"];
    $postId = $_POST["postId"];
    unset($_POST["postName"]);
    unset($_POST["categoryId"]);
    unset($_POST["postId"]);
    if( $postName == null || $categoryID == null || $postId == null){
        http_response_code(400);
        $return = array("sent"=> false,
                       "message"=>"postId, name and category must be given");
        echo json_encode($return);
        die();
    }
    $emails = getEmail();
    $emailsResults = $emails["emails"];
    $return = array();
    //echo json_encode($emailsResults);
    if($emailsResults === false){
        http_response_code(500);
        $return = array("sent"=>false,
                       "message"=>"Unable to retrieve emails.");
    }else if($emailsResults === 0){
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
            //$return = $category;
            $errorCount = 0;
            $totalEmails = count($emailsResults);
            $errors = array();
            $categoryName = $category[0]["name"];
            $subject = "New post created.";
            $message = "A new post has been created with the title of $postName and the category of $categoryName. The post can be found at https://stuweb.cms.gre.ac.uk/~ww0710p/EnterpriseWeb/ideacomment.html?postId=$postId";
            foreach($emailsResults as $email){
                $to = $email["email"];
                $emailSent = sendMail($to, $subject, $message);
                if($emailSent != true){
                    $errorCount ++;
                }
            }
            $message = null;
            if($errorCount > 0 && $errorCount < $totalEmails){
                $message = "$errorCount emails where not able to be send however some emails have still gone through.";
                $return = array("sent"=>true,
                           "message"=>$message);
            }else if ($errorCount == $totalEmails){
                $return = array("sent"=>false,
                               "message"=>"Unable to send any of the emails");
                
            }else{
                 $return = array("sent"=>true);
            }

        }
    }
     echo json_encode($return);   
}
    
    
function commentNotificaiton(){
    $postId = $_POST["postId"];
    $username = $_POST["username"];
    $userId = $_POST["userId"];
    unset($_POST["postId"]);
    unset($_POST["username"]);
    unset($_POST["userId"]);
   if($postId == null || $userId == null || $username == null){
        http_response_code(400);
        $return = array("send"=>false,
                       "message"=>"postId, username and userId must be set");
        echo json_encode($return);
        die();
    }
    $postUser = getUserEmailFromPostId($postId);
    //$return = "test";
    if($postUser["user"] === 0){
        $return = array("sent"=>false,
                       "message"=>"Unable to find post info");
        
    }else if ($postUser["user"] === false){
        http_response_code(500);
        $return = array("sent"=>false,
                       "message"=>"Unable to find post info");
        
    }else {
        $posterEmail = $postUser["user"][0]["email"];
        $subject = "New comment";
        $message = "$username has left a comment on your post. The post can be found at https://stuweb.cms.gre.ac.uk/~ww0710p/EnterpriseWeb/ideacomment.html?postId=$postId";
        $sendMail = sendMail($posterEmail, $subject, $message);
        if(sendMail){
            $return = array("sent"=>true);
        }else{
            $return = array("sent"=>false,
                           "message"=>$sendMail);
        }
            
            
    }
    echo json_encode($return);
    
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