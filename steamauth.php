<?php

	include "openid/openid.php";
	include "userdata.php";
	$OpenID = new LightOpenID($site);
	$displayHTML = "";

	if(!isset($_SESSION)){
   		session_start();
	}

	try {
		if (!$OpenID->mode) {
			if (isset($_GET['login'])) {
				$OpenID->identity = "http://steamcommunity.com/openid";
				header("Location: {$OpenID->authUrl()}");
			}
			if (!isset($_SESSION['SteamAuth'])) {
				$displayHTML = "<input type=\"image\" src=\"https://steamcommunity.com/public/images/signinthroughsteam/sits_small.png\" id=\"login\" onclick=\"login()\">";
			}
		}
		elseif ($OpenID->mode == "cancel") {
			echo "User has cancelled";
		}
		else {
			if (!isset($_SESSION['SteamAuth'])) {
				//http://localhost/?login&openid.ns=http%3A%2F%2Fspecs.openid.net%2Fauth%2F2.0&openid.mode=id_res&openid.op_endpoint=https%3A%2F%2Fsteamcommunity.com%2Fopenid%2Flogin&openid.claimed_id=http%3A%2F%2Fsteamcommunity.com%2Fopenid%2Fid%2F76561198061893618&openid.identity=http%3A%2F%2Fsteamcommunity.com%2Fopenid%2Fid%2F76561198061893618&openid.return_to=http%3A%2F%2Flocalhost%2F%3Flogin&openid.response_nonce=2014-02-25T11%3A25%3A54ZSc3yqTrZ8mZmQoZ4oCKh4cLx5nw%3D&openid.assoc_handle=1234567890&openid.signed=signed%2Cop_endpoint%2Cclaimed_id%2Cidentity%2Creturn_to%2Cresponse_nonce%2Cassoc_handle&openid.sig=52i3fSadcxNu%2FdgkLp0Z%2BtWNY%2BU%3D

				//parse the current url to obtain SteamID
				$url = urldecode($_SERVER['REQUEST_URI']);
				$array = explode("&", $url); //first split the url by &
				$identityTag = "NO TAG FOUND";
				foreach ($array as $value) {
					if (strpos($value, "openid.identity") !== false) {
						$identityTag = explode("=", $value)[1];
						break;
					}
				}

				$array2 = explode("/", $identityTag);
				$SteamID64 = $array2[count($array2) - 1]; //this is the final steamid64

				
				$_SESSION['SteamAuth'] = $identityTag;
				$_SESSION['SteamID64'] = $SteamID64;
				$_SESSION['UserData'] = new UserData($SteamID64);

				if ($_SESSION['SteamAuth'] !== null) {
					$profile = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key={$apikey}&steamids={$SteamID64}");
					$file = fopen("cache/{$SteamID64}.json", "w+");
					fwrite($file, $profile);
					fclose($file);
				}

				header("Location: index.php");
			}
		}
			
		if (isset($_GET['logout'])) {
			unset($_SESSION['SteamAuth']);
			unset($_SESSION['SteamID64']);
			session_destroy();
			header("Location: index.php");
		}
	}
	catch(ErrorException $e) {
		$displayHTML = $e->getMessage();
	}


	if (isset($_SESSION['SteamAuth'])) {
		$accountData = json_decode(file_get_contents("cache/{$_SESSION['SteamID64']}.json"));
		$name = $accountData->response->players[0]->personaname;
		$avatar = $accountData->response->players[0]->avatarfull;
		$displayHTML = "<div class=\"avatar\" id=\"avatarrect\"></div><img src={$avatar} class=\"avatar\"><div id=\"logout\"><p>Logged in as <font color=\"#00A300\">{$name}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font><a href=\"?logout\">Logout</a></p></div>";
	}
	echo $displayHTML;
?>