<?php
session_start(); // Connect to the existing session
require_once '/home/common/mail.php'; // Add email functionality
require_once '/home/common/dbInterface.php'; // Add database functionality
processPageRequest(); // Call the processPageRequest() function

function addMovieToCart($movieID) {
	$movieExists = movieExistsinDB($imdbIdb);
	$apikey = "53d97da4";
	if($movieExists)
		echo "The movie exists in the database - no need to add it";
	else{
			$movie = file_get_contents('http://www.omdbapi.com/?apikey='.$apikey.'&i='.$movieID.'&type=movie&r=json');
			$apiResponse = json_decode($movie, true);
	
	$imdbId = $apiResponse->{'imdbID'};
	$title = $apiResponse->{'Title'};
	$year = $apiResponse->{'Year'};
	$rating = $apiResponse->{'Rating'};
	$runtime = $apiResponse->{'Runtime'};
	$genre = $apiResponse->{'Genre'};
	$actors = $apiResponse->{'Actors'};
	$director = $apiResponse->{'Director'};
	$writer = $apiResponse->{'Writer'};
	$plot = $apiResponse->{'Plot'};
	$poster = $apiResponse->{'Poster'};
	$addMovie = addMovie($imdbId, $title, $year, $rating, $runtime, $genre, $actors, $director, $writer, $plot, $poster);
	}
	$userId = $_SESSION["credentials"][0];
	$movieId = $addMovie;
	addMovieToShoppingCart($userId, $movieId);
	displayCart();        
}

function checkout($name,$address){
	$message = displayCart(true);
	$result =  sendMail(737589532, $address, $name, "Your Receipt from myMovies!", $message);

	if ($result == 0)
		echo "the email message was sent successfully";

	elseif (($result  > 1) && ($result < 60))
		echo "The time (in seconds) that remains before  the next email can be sent";

	elseif ($result == -1)
		echo "An error occurred while sending the email message(email not sent)";

	elseif ($result == -2)
		echo "An invalid [mail_id] value was provided (email not sent)";

	else 
		echo "An error occurred while accessing the database(email not sent)";

	return $result;
}

function createMovieList($forEmail=false){
	$order = $_SESSION["cart order"];
	$userId = $_SESSION["credentials"][0];
	if (isset($_SESSION["cart order"]))
		getMoviesInCart($userId, $order);
		 
	else
		getMoviesInCart($userId);
	ob_start(); // Create an output buffer
	require_once './templates/movie_list.html';
	$message = ob_get_contents(); // Get the contents of the output buffer
	ob_end_clean(); // Clear the output buffer
	
	return $message;
}

function displayCart($forEmail=false){
	$userId = $_SESSION['credentials'][0];
	$forEmail = $_SESSION['credentials'][3];
	$movieCount = countMoviesInCart($userId);
	if(is_numeric($movieCount)){
		createMovieList($forEmail);
		ob_start(); // Create an output buffer
		require_once './templates/cart_form.html';
		$message = ob_get_contents(); // Get the contents of the output buffer
		ob_end_clean(); // Clear the output buffer
		return $message;
	}
	else{//user id is invalid
		return  "The Specified user ID is invalid";
	}		
}

function processPageRequest() {
	$displayName = $_SESSION['credentials'][2];
	if(!isset($displayName))
		require_once './logon.php';
	if (!isset($_GET['action']))
		displayCart();
	else {
		switch($_GET['action']){
			case 'add':
				addMovieToCart($movieId);
				echo displayCart();
				break;
			case 'checkout':
				checkout($name, $address);
				break;
			case 'remove':
				removeMovieFromCart($movieId);
				echo displayCart();
             }
}

function removeMovieFromCart($movieID){
	$removeMovie = removeMovieFromShoppingCart($userId, $movieId);
	if($removeMovie)
		echo "the movie was removed from the shopping cart";
	else
		echo "the movie does not exist in the shopping cart";
	displayCart();
}

function updateMovieListing($order){
	$order = $_SESSION['cart order'];
	createMovieList(false);
	echo createMovieList(false);
}

?>

