<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
require "DataCollectionService.php";
require "DataActionService.php";
header('Content-Type: application/json');
$method = $_POST["method"];
unset($_POST["method"]);
switch($method){
    case "changeRole":
        changeRole();
        break;
    case "deleteCategory":
        deleteCategory();
        break;
    case "getPermissions":
        getPermissions();
        break;
    case "createRole":
        createRole();
        break;
    case "editRole":
        editRole();
        break;
    case "getUsers":
        getUsers();
        break;
    case "createCategory":
        createCategory();
        break;
    case "flagged":
        flaggedPosts();
        break;
 
}

function changeRole(){
    $userId = $_POST["userId"];
    $roleId = $_POST["postId"];
    unset($_POST["userId"]);
    unset($_POST["postId"]);
    $return = null;
    if($roleId == null || $userId == null){
        http_response_code(400);
        echo json_encode(array("message"=> "parameters of user id and role id must be set."));
        die();
    }
    $role = changeUserRole($roleId, $userId);
    if($role["updated"]== true){
        $return = $role;
    }
    else{
        http_response_code(500);
        $return = $role;
    }
    echo json_encode($return);
}


function deleteCategory(){
    $categoryId = $_POST["categoryId"];
    unset($_POST["categoryId"]);
    $return = null;
    if($categoryId == null){
        http_response_code(400);
        echo json_encode(array("message"=> "parameters of category id."));
        die();
    }
    $role = deleteCategoryWithId($categoryId);
    if($role["updated"]== true){
        $return = $role;
    }
    else{
        http_response_code(500);
        $return = $role;
    }
    echo json_encode($return);
}

function getPermissions(){
    $permissions = getPermissionsList();
    $return = array();
    if($permissions["results"] == false){
        http_response_code(500);
        $return = array("results"=> false,
                       "message" => $permissions["message"]);
    }else if ($permissions["results"] == 0){
        $return = array("results"=>0);
    }else{
        $return = array("results"=>$permissions);
    }
    echo json_encode($return);
}

function createRole(){
    $roleName = $_POST["roleName"];
    $permissionSet = $_POST["permissionSet"];
    unset($_POST["roleName"]);
    unset($_POST["permissionSet"]);
    $return = array();
    if($roleName == null || $permissionSet == null){
        http_response_code(400);
        echo json_encode(array("message"=>"The role name and permission set must be set."));
        die();
    }
    $role = newRole($roleName, $permissionSet);
    if($role["created"]){
        $return = array("created"=>true);
    }else{
        http_response_code(500);
        $return = array("created"=>false,
                       "message"=>$role["message"]);
    }
    echo json_encode($return);
    
}

function editRole(){
    $roleId = $_POST["roleId"];
    $permissionSet = $_POST["permissionSet"];
    unset($_POST["roleId"]);
    unset($_POST["permissionSet"]);
    $return = array();
    if($roleId == null || $permissionSet == null){
        http_response_code(400);
        echo json_encode(array("message"=>"The role id and permission set must be set."));
        die();
    }
    $deletedPermissionSet = deleteRolePermissions($roleId);
    if($deletedPermissionSet["deleted"] == true){
        setRolePermissions($roleId, $permisssionSet);
        $return = array("updated"=>true);
    }else{
        $return = array("updated"=> false,
                       "message"=> $deletedPermissionSet["message"]);
    }
    echo json_encode($return);
}

function getUsers(){
    $users = getAllUsers();
    $return = array();
    if($users["results"] == 0){
        $return = array("results"=>[]);
    }
    else if($users["results"]== false){
        http_response_code(500);
        $return = array("results"=>false,
                       "message"=>$users["message"]);
    }
    else{
        $return = $users;
    }
    echo json_encode($return);
    
}


function createCategory(){
    $categoryName = $_POST["name"];
    unset($_POST["name"]);
    $return = array();
    if($categoryName == null){
        http_response_code(400);
        $return = array("message"=>"The name of the category must be set.");
        echo json_encode($return);
        die();
    }
    $created = createNewCategory($categoryName, 0, 0);
    if($created["created"] == true){
        $return = $created;
    }else{
        http_response_code(500);
        $return = $created;
    }
    echo json_encode($created);
        
    
}

function flaggedPosts(){
    $posts = getFlaggedPosts();
    $return = array();
    if($posts["results"]== false){
        http_response_code(500);
        $return = array("results"=>false,
                       "message"=>$post["message"]);
        
    }else{
        $return = array("results"=>$post["results"]);
    }
    echo json_encode($return);
}

?>