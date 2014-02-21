<?php
	session_start();
	include "openid/openid.php";
	include "apikey.php";

	$OpenID = new LightOpenID("localhost");
	$login = "";
	try {
		if (!$OpenID->mode) {
			if (isset($_GET['login'])) {
				$OpenID->identity = "http://steamcommunity.com/openid";
				header("Location: {$OpenID->authUrl()}");
			}
			if (!isset($_SESSION['SteamAuth'])) {
				$login = "<input type=\"image\" src=\"https://steamcommunity.com/public/images/signinthroughsteam/sits_small.png\" id=\"login\" onclick=\"login()\">";
			}
		}
		elseif ($OpenID->mode == "cancel") {
			echo "User has cancelled";
		}
		else {
			if (!isset($_SESSION['SteamAuth'])) {
				/*echo "<p id=\"login\">test: {$OpenID->indentity}</p>";
				$_SESSION['SteamAuth'] = $OpenID->validate() ? $OpenID->indentity : null;
				$_SESSION['SteamID64'] = str_replace("http://steamcommunity.com/openid/id/", "", $_SESSION['SteamAuth']);*/
					echo "<p id=\"login\">test: {$_SERVER['REQUEST_URI']}</p>";
				if (isset($_GET['openid.identity'])) {

				}
				
				if ($_SESSION['SteamAuth'] !== null) {
					$SteamID64 = $_SESSION['SteamID64'];
					$profile = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key={$api}&steamids={$SteamID64}");
					$file = fopen("cache/{$SteamID64}.json", "w+");
					fwrite($file, $profile);
					fclose($file);
				}

				//header("Location: index.php");
			}
		}

		if (isset($_SESSION['SteamAuth'])) {
			$login = "<a id=\"login\" href=\"?logout\">Logout</a>";
		}
			
		if (isset($_GET['logout'])) {
			unset($_SESSION['SteamAuth']);
			unset($_SESSION['SteamID64']);
			session_destroy();
			header("Location: index.php");
		}
	}
	catch(ErrorException $e) {
		$login = $e->getMessage();
	}

	//$steam = json_decode(file_get_contents("cache/{$_SESSION['SteamID64']}.json"));

	echo $login;

	//echo $steam->response->players[0]->personnaname;


?>