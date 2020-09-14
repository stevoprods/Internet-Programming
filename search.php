<?php
session_start(); // Connect to the existing session
processPageRequest(); // Call the processPageRequest() function

function displaySearchForm(){
	require_once 'search_form.html';
}
function displaySearchResults($searchString){
	$apikey = "53d97da4";
	$results = file_get_contents('http://www.omdbapi.com/?apikey='.$apikey&s.'='.urlencode($searchString).'&type=movie&r=json');
	$array = json_decode($results, true)["Search"];
	//$_SESSION["searchResults"] = $array;
	require_once 'templates/results_form.html';
}

function processPageRequest(){
	 $displayName = $_SESSION['credentials'][2];
	 $searchString = $_POST["searchterm"];
	 if(!isset($displayName))
		 require_once './logon.php';
	 if(isset($searchString))
		 displaySearchresults($searchString);
	 else
		 displaySearchForm();
}
?>