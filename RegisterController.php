<?php
require "DataActionService.php";
require "DataCollectionService.php";

header('Content-Type: application/json');
extract($_POST);
$errorCode = null;
$errorMessage = null;


$departmentID = GetDepartmentID($department)
if($departmentID < 1 ){
    $result = registerUser($email, $first_name, $last_name, $departmentID, $password, $username);
    if($result == 1062){
        //https response code actually needs to be set see how i have done it in the login controller
        $return = array("errorCode"=>400,
                       "errorMessage"=>"Duplicate User");
    }else{
        $return = array("register"=>true);
    }
}else{
    $return = array("errorCode"=>400,
                   "errorMessage"=>"incorect Department");  
}
echo json_encode($return);
die();

?>
