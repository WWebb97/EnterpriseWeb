<?php
require "DataActionService.php";
require "DataCollectionService.php";

header('Content-Type: application/json');
extract($_POST);
$errorCode = null;
$errorMessage = null;

if (isset($_POST['agree'])){
            if ($captchabox == $_SESSION['random_code']){
                $departmentID = GetDepartmentID($department)
                if($departmentID < 1){
                    $result = registerUser($email, $first_name, $last_name, $, password);
                    if($result == 1062){
                        //https response code actually needs to be set see how i have done it in the login controller
                        $errorReturn = array("errorCode"=>400,
                                   "errorMessage"=>"Duplicate User");
                                    echo json_encode($errorReturn);
                                    die();
                    }else{
                        setcookie('username', $username, time()+3600);
                        header("location:");
                    }

                }else{
                     $errorReturn = array("errorCode"=>400,
                                   "errorMessage"=>"Non existant Department");
                                    echo json_encode($errorReturn);
                                    die();   
                }
                        
            }else{
                 $errorReturn = array("errorCode"=>400,
                       "errorMessage"=>"Incorrect Captcha");
                        echo json_encode($errorReturn);
                        die();
            }
        }else{
            $errorReturn = array("errorCode"=>400,
                       "errorMessage"=>"Terms and Conditions not agreed");
                        echo json_encode($errorReturn);
                        die();
        }
?>
