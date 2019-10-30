/*codigos de repuesta
	loginfail = "8900"
	access_denied = "9097"
	access_allow = "6756"
*/
var loginfail = "8900",
	access_denied = "9097",
	access_allow = "6756";
$(document).ready(function (argument) {
	var response = $('#access').val();
	if(response == loginfail){
		window.location = "/privado/";
	}else if(response == access_denied){
		window.location = "/privado/views/dashboard.php";
	}	
});