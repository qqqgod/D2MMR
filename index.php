
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
		<script src="jquery.js"></script>
		<script src="jquery.getUrlParam.js"></script>
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

			function showAddGameDialog()
			{
				$("#overlayheader").text("Add Game");
				toggleDialog();
			}

			function showEditGameDialog(object)
			{
				obj = $(object);

				var gameWon = obj.find(".gameresulttext").text().indexOf("Won") != -1;
				$("#gamewonradio").prop("checked", gameWon);
				$("#gamelostradio").prop("checked", !gameWon);
				toggle(gameWon);

				var soloGame = obj.find(".queuetype").text().indexOf("Solo") != -1;
				$("#sologameradio").prop("checked", soloGame);
				$("#partygameradio").prop("checked", !soloGame);
				
				var mmrChange = obj.find(".gamemmrtext").text();
				console.log(mmrChange);

				//TODO: parse mmr change
				
				$("#overlayheader").text("Edit Game Data");
				toggleDialog();

			}

			function toggleDialog() {
				var el = document.getElementById("overlay");
				el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
			}
		</script>

	</head>
	<title>Dota 2 MMR Tracker</title>
	<body>

		<div id="header">
			<?php
				include "apikey.php";
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

		<div id="overlay">
			<div id="editgamediv">
			<p id="overlayheader"><b>Edit Game Data</b></p>
			<br>
				<input id="gamewonradio" type="radio" name="result" value="" checked onchange="toggle(true)"><b>I won!</b></input>
				<input id="gamelostradio" type="radio" name="result" value="" onchange="toggle(false)"><b>I lost!</b></input>
			<br><br>
				<input id="sologameradio" type="radio" name="type" value="" checked><b>Solo Game</b></input>
				<input id="partygameradio" type="radio" name="type" value=""><b>Party Game</b></input>
			<br><br>
				<font color="#00CC00" id="plus"><b id="v">+&nbsp;</b></font>
				<input id="mmrchangeinput" type="number" min="0" max="35" value="25" onkeypress="return isNumber(event)">
				<b>&nbsp;MMR</b>
			<br><br>
			<b>Hero:</b>
				<select id="heroselector">
					<option value="0">Unspecified</option>
					<?php
						include "dota2api.php"; 
					?>
				</select>
			<br><br>
				<button onclick="addGameButtonClick()">Save</button><button onclick="toggleDialog()">Cancel</button>
			</div>
     	</div>

		<div id="gamesdiv">
			<p id="gamesdivheader">Game History</p>
			<?php
				if (isset($_SESSION['UserData'])) {
					$page = 1;
					if (isset($_GET['page'])) {
						$page = intval($_GET['page']);
					}
					populateGamesList($page);
				}
			?>
			<div id="gamelistnavigator">
				<button id="prevpage"><b><</b></button>
				<font id="currentpage" style="font-family:Oxygen;color:white">Page 1/1</font>
				<button id="nextpage"><b>></b></button>
				<script>
					var URL = "localhost";
					var prevButton = $("#prevpage");
					var nextButton = $("#nextpage");
					var currentPage = $(document).getUrlParam("page");
					var pages = 1;

					if (!$.isNumeric(currentPage)) {
						window.location = "index.php?page=1";
					}
					else {
						currentPage = parseInt(currentPage);
					}

					
					$.ajax({
						url: 'userdata.php',
						data: {
							GetGameData: '1'
						},
						type: 'post',
						success: function(result) {
							result = $.parseJSON(result);					
							if (currentPage > result.pages || currentPage < 1) {
								window.location = "index.php?page=1";
							}
							else {
								$("#currentpage").text("Page " + currentPage + "/" + result.pages);
								if (currentPage == 1) {
									prevButton.attr("disabled", true);
								}
								if (currentPage == result.pages) {
									nextButton.attr("disabled", true);
								}
							}
						}
					});

					prevButton.click(function() {
						var prev = currentPage - 1;
						window.location = "index.php?page=" + prev;
					});

					nextButton.click(function() {
						var next = currentPage + 1;
						window.location = "index.php?page=" + next;
					});
				</script>
			</div>
		</div>

		<div id="footer">
			<p>2014 - Vlad Marica  |  Dota 2 is a registered trademark of Valve Corporation.</p>
		</div>
	</body>
</html>	