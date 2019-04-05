<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);


function paginateResults($items){
    $paginated = array();
    $set=array();
    foreach($items as $item){
        if(sizeof($set) === 5){
            array_push($paginated, $set);
            $set = array();
        }
        array_push($set, $item);
    }
    if(sizeof($set) != 0){
        array_push($paginated,$set);
    }
    return $paginated;
}

function uploadFiles($postId, $files){
//start the image upload code
    $return=array();
    $extension=array("docx", "pdf");
    $allowedSize = 500000;
    $file_name=$files["name"];
    $file_tmp=$files["tmp_name"];
    $savedName = "";
    $ext=pathinfo($file_name, PATHINFO_EXTENSION);
    $fileNameWithoutEXT = pathinfo($file_name, PATHINFO_FILENAME);
    $newFileName = $fileNameWithoutEXT." postId".$postId;
    $error = null;
    if(in_array($ext,$extension))
    {
        if(!($files["size"] > $allowedSize)){
            if(!file_exists("attachments2/".$newFileName))
            {
                try{
                    move_uploaded_file($files["tmp_name"],"attachments2/".$newFileName.".".$ext);
                    $savedName = $newFileName.".".$ext;
                    $return = array("uploaded"=>true,
                                    "savedName"=>$savedName);
                }catch(Exception $e){
                    $return = array("uploaded"=>false,
                                   "message"=>$e);
                }
               
            }
            else
            {
                     $return = array("uploaded"=>false,
                                   "message"=>"File already exists");
            }
        }else{
                    $return = array("uploaded"=>false,
                                   "message"=>"File size too large");
        }
      
    }
    else
    {
        $return = array("uploaded"=>false,
                       "message"=>"File not of a valid type");
    }
    return $return;
}

function sendMail($to, $subject, $message){
    require "config.php";
    $from = "From: ". $emailSender;
    
    if(mail($to, $subject, $message, $from)){
        return true;
    }else{
        return error_get_last()["message"];
    }
}


?>