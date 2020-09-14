function addMovie(movieID){
	window.location.replace("./index.php?action=add&movie_id=" + movieID);
	return true;
}

function confirmCancel(form){
	if(confirm("Would you like to cancel?")){
		if(form == 'create' || form == 'forgot' || form == 'reset')
			window.location.replace("./logon.php");
		else if (form == 'search')
			window.location.replace("./index.php");
	return true;
	}
	else
		return false;
}

function changeMovieDisplay(){
	var selected = document.getElementById("select_order").value;
	var xhttp	= new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if (this.readyState == 4 && this.status == 200){
			document.getElementById("shopping_cart").innerHTML= this.responseText;
		}
	};
	xhttp.open("GET", "./index.php?action=update&order=" + selected, true);
	xhttp.send();
}

function confirmCheckout(){
	if(confirm("Confirm checkout?")){
		window.location.replace("./index.php?action=checkout");
		return true;
	}
	else
		return false;
}

function confirmLogout(){
	if(confirm("Logout from myMovies Xpress?")){
		window.location.replace("./logon.php?action=logoff");
		return true;
	}
	else
		return false;
}

function confirmRemove(title, movie_id){
	if(confirm("Would you like to remove "+title+"?"))
		window.location.replace("./index.php?action=remove&movie_id=" + movieID);
		return true;
	else
		return false;
}

function displayMovieInformation(movie_id){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if (this.readyState == 4 && this.status == 200){
			document.getElementById("modalWindowContent").innerHTML= this.responseText;
			showModalWindow();
		}
	};
	xhttp.open("GET", "./movieinfo.php?movie_id=" + movie_id, true);
	xhttp.send();
}

function forgotPassword(){
	window.location.replace("./logon.php?action=forgot");
	return true;
}

function showModalWindow()
{
    var modal = document.getElementById('modalWindow');
    var span = document.getElementsByClassName("close")[0];

    span.onclick = function() 
    { 
        modal.style.display = "none";
    }

    window.onclick = function(event) 
    {
        if (event.target == modal) 
        {
            modal.style.display = "none";
        }
    }
 
    modal.style.display = "block";
}

function validateCreateAccountForm(){
	 var EmailAddress = document.getElementsByName("EmailAddress").value.trim();
	 var ConfirmEmailAddress  = document.getElementsByName("ConfirmEmailAddress").value.trim();
	 var Username = document.getElementsByName("Username").value.trim();
	 var Password = document.getElementsByName("Password").value.trim();
	 var ConfirmPassword = document.getElementsByName("Confirm Password").value.trim();

     if ((EmailAddress+ConfirmEmailAddress+Username+Password+ConfirmPassword).search(/ /) == -1){
		alert("Spaces are invalid");
		return false;
	 }
     else if(EmailAddress != ConfirmEmailAddress){
        alert("Email Addresses do not match");
		return false;
	 }
	 else if(Password != ConfirmPassword){
        alert("Passwords do not match");
		return false;
	 }
	 else
		 return true;
}

function validateResetPasswordForm(){
    var Password = document.getElementsByName("Password").value.trim();
	var ConfirmPassword = document.getElementsByName("Confirm Password").value.trim();
	
	if ((Password+ConfirmPassword).search(/ /) == -1){
		alert("Spaces are invalid");
		return false;
	 }
	else if(Password != ConfirmPassword){
        alert("Passwords do not match");
		return false;
	}
	
	else 
		return true;
}

