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

?>