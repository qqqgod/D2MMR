<!doctype html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
		<script src="http://code.jquery.com/jquery-1.11.0.js"></script>

		<script>
			$(document).ready(function() {
  				console.log("Webpage loaded");
  				if ($("#logout").length) {
  					$("#loginstatus").hide();
  				}
  				else {
  					$("#gamestracked").hide();
  					$("#solommr").hide();
  					$("#partymmr").hide();
  				}
			});
			function login() {
				var currentURL = $(location).attr('href');
				currentURL = currentURL + "?login";
				window.location = currentURL;
			}
		</script>

	</head>
	<title>Dota 2 MMR Tracker</title>
	<body>
		<div id="header">
			<?php
				include "steamauth.php";
			?>
			<img src="images/dota_logo.png" id="dotalogo">
			<p id="titletext">Dota 2 MMR Tracker</p>
		</div>

		<div id="bodydiv">
			<p id="loginstatus">&nbsp;&nbsp;&nbsp;You are currently not logged in.</p>
			<?php
				//include "userdata.php";
				if (isset($_SESSION['UserData'])) {
					displayHTML($_SESSION['UserData']);
				}
			?>
		</div>

		<div id="footer">
			<p>2014 - Vlad Marica  |  Dota 2 is a registered trademark of Valve Corporation.</p>
		</div>
	</body>
</html>	