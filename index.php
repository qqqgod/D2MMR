<!--by Vlad Marica -->
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
		<script src="jquery.js"></script>
		<script src="mmr.js"></script>
		<script>
			//run when the document loads
			$(document).ready(function() {
  				console.log("Webpage loaded");
  				if ($("#logout").length) {
  					$("#loginstatus").hide();
  				}
  				else {
  					$("#gamestracked").hide();
  					$("#solommr").hide();
  					$("#partymmr").hide();
  					$("#gamesdiv").hide();
  				}
			});

			//run when the user clicks the Login with Steam button
			function login() {
				var currentURL = $(location).attr('href');
				currentURL = currentURL + "?login";
				window.location = currentURL;
			}

			function toggle(checked) //triggered when the player changes the game result in the Add Game tab
			{
				if (checked) {
					$("#v").text("+\xa0");
					$("#plus").css("color", "#00CC00");
				}
				else {
					$("#v").text("-\xa0");
					$("#plus").css("color", "#D63030");
				}
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
				if (isset($_SESSION['UserData'])) {
					displayHTML($_SESSION['UserData']);
				}
			?>
		</div>


		<div id="gamesdiv">
			<p id="gamesdivheader">Game History</p>
			<?php
				if (isset($_SESSION['UserData'])) {
					populateGamesList(1);
				}
			?>
			<div id="gamelistnavigator">
				<button id="prevpage"><b><</b></button>
				<font id="currentpage" style="font-family:Oxygen;color:white">Page 1/1</font>
				<button id="nextpage"><b>></b></button>
				<script>
					var prevButton = $("#prevpage");
					var nextButton = $("#nextpage");

					$.ajax({
						url: 'userdata.php',
						data: {
							GetGameData: '1'
						},
						type: 'post',
						success: function(result) {
							//result = $.parseJSON(result);
							alert(result);
						}
					});


					prevButton.click(function() {
						
					});

					nextButton.click(function() {
						
					});
				</script>
			</div>
		</div>


		<div id="addgamediv">
			<br>
				<input id="gamewonradio" type="radio" name="result" value="" checked onchange="toggle(true)"><b>I won!</b></input>
				<input type="radio" name="result" value="" onchange="toggle(false)"><b>I lost!</b></input>
			<br><br>
				<input id="sologameradio" type="radio" name="type" value="" checked><b>Solo Game</b></input>
				<input type="radio" name="type" value=""><b>Party Game</b></input>
			<br><br>
				<font color="#00CC00" id="plus"><b id="v">+&nbsp;</b></font>
				<input id="mmrchangeinput" type="number" min="0" max="35" value="25" onkeypress="return isNumber(event)">
				<b>&nbsp;MMR</b>
			<br><br>
			<b>Hero:</b>
				<select id="heroselector">
					<option value="0">Unspecified</option>
					<?php
						require "dota2api.php";
					?>
				</select>
			<br><br>
				<button onclick="addGameButtonClick()">Add Game</button>
		</div>


		<div id="footer">
			<p>2014 - Vlad Marica  |  Dota 2 is a registered trademark of Valve Corporation.</p>
		</div>
	</body>
</html>	