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
    case "ideasNo":
        ideasNo();
        break;
    case "ideasTime":
        ideasTime();
        break;
    case "ideasTopUser":
        ideasTopUser();
        break;
    case "exportValues":
        exportValues();
        break;
}

function ideasNo(){
     $timing = $_POST["timing"];
    unset($_POST["timing"]);
    
    
    $lastMonth1Unix = date(strtotime("first day of previous month"));
    $lastMonth2Unix = date(strtotime("last day of previous month"));
    $thisMonth1Unix = date(strtotime("first day of this month"));
    $thisMonth2Unix = date(strtotime("last day of this month"));
     switch($timing){
        case 1:
            $time = "where p.post_date BETWEEN $thisMonth1Unix AND $thisMonth2Unix";
            break;
        case 2:
            $time = "where p.post_date BETWEEN $lastMonth1Unix AND $lastMonth2Unix";
            break;
        case 3: 
            $time = null;
            break;
        default:
            $time = "where p.post_date BETWEEN $thisMonth1Unix AND $thisMonth2Unix"; 
    }    
    
    
    $return = array();
    $ideas = fetchIdeasByDept($time);
    if($comments === 0){
        $return = array("ideas"=>0);
    }else if (isset($ideas["values"])){
        http_response_code(500);
        $return = array("message"=>$ideas["message"]);
    }else{
        $return = $ideas;
    }

    echo json_encode($return, JSON_NUMERIC_CHECK);
    
}

function ideasTime(){
    $timing = $_POST["timing"];
    unset($_POST["timing"]);
    
    $lastMonth1Unix = date(strtotime("first day of previous month"));
    $lastMonth2Unix = date(strtotime("last day of previous month"));
    $thisMonth1Unix = date(strtotime("first day of this month"));
    $thisMonth2Unix = date(strtotime("last day of this month"));
    
    switch($timing){
        case 1:
            $time = "where post_date BETWEEN $thisMonth1Unix AND $thisMonth2Unix";
            break;
        case 2:
            $time = "where post_date BETWEEN $lastMonth1Unix AND $lastMonth2Unix";
            break;
        case 3: 
            $time = null;
            break;
        default:
            $time = "where post_date BETWEEN $thisMonth1Unix AND $thisMonth2Unix"; 
    }    
    
    
    $return = array();
    $ideas = fetchIdeasByDate($time);
    if($comments === 0){
        $return = array("ideas"=>0);
    }else if (isset($ideas["values"])){
        http_response_code(500);
        $return = array("message"=>$ideas["message"]);
    }else{
        $return = $ideas;
    }

    echo json_encode($return, JSON_NUMERIC_CHECK);
    
}

function ideasTopUser(){
    $timing = $_POST["timing"];
    unset($_POST["timing"]);
    
    $lastMonth1Unix = date(strtotime("first day of previous month"));
    $lastMonth2Unix = date(strtotime("last day of previous month"));
    $thisMonth1Unix = date(strtotime("first day of this month"));
    $thisMonth2Unix = date(strtotime("last day of this month"));
    
    switch($timing){
        case 1:
            $time = "where p.post_date BETWEEN $thisMonth1Unix AND $thisMonth2Unix";
            break;
        case 2:
            $time = "where p.post_date BETWEEN $lastMonth1Unix AND $lastMonth2Unix";
            break;
        case 3: 
            $time = null;
            break;
        default:
            $time = "where p.post_date BETWEEN $thisMonth1Unix AND $thisMonth2Unix"; 
    }    
    
    
    $return = array();
    $ideas = topPosters($time);
    if($comments === 0){
        $return = array("ideas"=>0);
    }else if (isset($ideas["values"])){
        http_response_code(500);
        $return = array("message"=>$ideas["message"]);
    }else{
        $return = $ideas;
    }

    echo json_encode($return, JSON_NUMERIC_CHECK);
    
}

function exportValues(){
    $timing = $_POST["timing"];
    unset($_POST["timing"]);
    
    $lastMonth1Unix = date(strtotime("first day of previous month"));
    $lastMonth2Unix = date(strtotime("last day of previous month"));
    $thisMonth1Unix = date(strtotime("first day of this month"));
    $thisMonth2Unix = date(strtotime("last day of this month"));
    
    switch($timing){
        case 1:
            $time = "and p.post_date BETWEEN $thisMonth1Unix AND $thisMonth2Unix";
            break;
        case 2:
            $time = "and p.post_date BETWEEN $lastMonth1Unix AND $lastMonth2Unix";
            break;
        case 3: 
            $time = null;
            break;
    }    
    
    
    $return = array();
    $ideas = fetchSelectedPosts($time);
    if($comments === 0){
        $return = array("ideas"=>0);
    }else if (isset($ideas["values"])){
        http_response_code(500);
        $return = array("message"=>$ideas["message"]);
    }else{
        $return = $ideas;
    }

    echo json_encode($return);
}

?>