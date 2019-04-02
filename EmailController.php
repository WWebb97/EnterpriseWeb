<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
require 'config.php';
//require "DataCollectionService.php";
header('Content-Type: application/json');
$errorCode = null;
$errorMessage = null;
    
function uploadNotification($postId) {
//    $emails = getEmail();
//    $val = null;
//    foreach($emails as $val){
//        $to = $val;
//        $subject = "A new idea has been posted!";
//        $message = "New idea created here: https://stuweb.cms.gre.ac.uk/~ad9047o/EnterpriseWeb/ideacomment.html?postId=$postId";
//        $from = "From: db1238b@gre.ac.uk";
//        $success = mail($to, $subject, $message, $from);  
//        
//        if (!$success) {
//            echo error_get_last()["message"];
//        }
//    }
   
}


?>