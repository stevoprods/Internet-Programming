<?php
session_start(); // Connect to the existing session
require_once '/home/common/dbInterface.php'; // Add database functionality
processPageRequest(); // Call the processPageRequest() function

function createMessage($movieId){
    $getMovie = getMovieData($movieId);
    if(is_null($getMovie))
        echo "The movie ID value is invalid";
    else{
        ob_start(); // Create an output buffer
        require_once './templates/movie_info.html';
        $message = ob_get_contents(); // Get the contents of the output buffer
        ob_end_clean(); // Clear the output buffer
        echo $message;
    }
}

function processPageRequest(){
    if(!isset($displayName))
        require_once './logon.php';
    $movieId = $_GET['movie_id'];
    if (isset($movieId))
        createMessage($movieId);
    else    
        createMessage(0);
}

?>