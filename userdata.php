<?php
	class UserData implements JSONSerializable
	{
		public $steamID64 = "no id set";
		public $soloMMR = -1;
		public $partyMMR = -1;
		public $baseSoloMMR = -1;
		public $basePartyMMR = -1;

		public $games = array();

		public function __construct($steamID)
		{
			$this->steamID64 = $steamID;
			$this->readFromFile();
			$this->saveToFile();
		}

		public function jsonSerialize() 
		{
       		$data = array(
       			"steamid" => $this->steamID64,
       			"soloMMR" => $this->soloMMR,
       			"partyMMR" => $this->partyMMR,
       			"baseSoloMMR" => $this->baseSoloMMR,
       			"basePartyMMR" => $this->basePartyMMR,
       			"games" => $this->games
       		);
        	return $data;
   		}

   		public function readFromFile()
   		{
   			$data = json_decode(file_get_contents("userdata/{$this->steamID64}.json"), true);
   			$this->soloMMR = getJSONValue($data, 'soloMMR', -1);
   			$this->partyMMR = getJSONValue($data, 'partyMMR', -1);
   			$this->loadGameList($data);
   		}

   		private function loadGameList($jsonArray)
   		{
   			$array = $jsonArray['games'];
   			$this->games = array();
   			$currentPartyMMR = $jsonArray['basePartyMMR'];
   			$currentSoloMMR = $jsonArray['baseSoloMMR'];

   			foreach ($array as $game) {
   				$gameData = new GameData($game['hero'], $game['queueType'], $game['gameWon'], $game['mmrChange']);
   				$gameData->currentMMR = $gameData->queueType == "solo" ? $currentSoloMMR : $currentPartyMMR;

   				$mmrChange = $gameData->mmrChange;
   				if ($gameData->gameWon == "false") {
   					$mmrChange *= -1;
   				}

   				array_push($this->games, $gameData);

   				if ($gameData->queueType == "solo") {
   					$currentSoloMMR += $mmrChange;
   				}
   				else {
   					$currentPartyMMR += $mmrChange;
   				}
   			}

   			$this->soloMMR = $currentSoloMMR;
   			$this->partyMMR =$currentPartyMMR;
   			$this->saveToFile();
   		}

   		public function saveToFile()
   		{
   			$file = fopen("userdata/{$this->steamID64}.json", "w+");
   			fwrite($file, json_encode($this, JSON_PRETTY_PRINT));
   			fclose($file);
   		}

   		public function getGames() { return $this->games; }

   		public function getGameCount() { return count($this->games); }

   		public function getSoloMMR() { return $this->soloMMR != -1 ? $this->soloMMR : "N/A"; }

   		public function getBaseSoloMMR() { return $this->baseSoloMMR != -1 ? $this->baseSoloMMR : "N/A"; }

   		public function setSoloMMR($mmr)
   		{
   			$this->soloMMR = $mmr;
   			$this->saveToFile();
   		}

   		public function setBaseSoloMMR($mmr)
   		{
   			$this->baseSoloMMR = $mmr;
   			$this->saveToFile();
   		}

   		public function getPartyMMR() { return $this->partyMMR != -1 ? $this->partyMMR : "N/A"; }

   		public function getBasePartyMMR() { return $this->basePartyMMR != -1 ? $this->basePartyMMR : "N/A"; }

   		public function setPartyMMR($mmr)
   		{
   			$this->partyMMR = $mmr;
   			$this->saveToFile();
   		}

   		public function setBasePartyMMR($mmr)
   		{
   			$this->basePartyMMR = $mmr;
   			$this->saveToFile();
   		}

   		public function getMMR($queue)
   		{
   			return $queue == "solo" ? $this->getSoloMMR() : $this->getPartyMMR();
   		}

   		public function addMMR($queue, $mmr)
   		{
   			$queue == "solo" ? $this->soloMMR += $mmr : $this->partyMMR += $mmr;
   			$this->saveToFile();
   		}

   		public function addGame(GameData $data)
   		{
   			array_push($this->games, $data);
   			$this->saveToFile();
   		}
	}

	/****************************************/

	class GameData implements JSONSerializable
	{
		public $hero = "no_hero";
		public $queueType = "solo";
		public $gameWon = true;
		public $mmrChange = 0;
		public $currentMMR = 0; //the player's mmr when this game was added

		public function __construct($h, $queue, $won, $mmr)
		{
			$this->hero = $h;
			$this->queueType = $queue;
			$this->gameWon = $won;
			$this->mmrChange = $mmr;
		}

		public function jsonSerialize() 
		{
			$data = array(
				"hero" => $this->hero,
				"queueType" => $this->queueType,
				"gameWon" => $this->gameWon,
				"mmrChange" => $this->mmrChange,
			);
			return $data;
		}
	}

	/****************************************/

   	function getJSONValue($data, $key, $default)
   	{
   			return $data[$key] ? $data[$key] : $default;
   	}	

	function displayHTML(UserData $data)
	{
		//re-read data from file
		$data->readFromFile();

		echo "<div style=\"height:30px; width:250px;\" id=\"gamestrackeddiv\"><p id=\"gamestracked\">Games Tracked: {$data->getGameCount()}</p></div>";

		//SOLO MMR HTML
		echo "<div id=\"solommrdiv\" style=\"height:30px; width:250px;\">";
		echo "<p id=\"solommr\">Solo MMR: {$data->getSoloMMR()}</p>";
		if ($data->getSoloMMR() === "N/A") {
			echo "<button id=\"setsolommr\" onclick=\"setSoloMMR()\">Set</button>";
		}
		echo "</div>";

		//PARTY MMR DIV
		echo "<div id=\"partymmrdiv\" style=\"height:30px; width:250px;\">";
		echo "<p id=\"partymmr\">Party MMR: {$data->getPartyMMR()}</p>";
		if ($data->getPartyMMR() === "N/A") {
			echo "<button id=\"setpartymmr\" onclick=\"setPartyMMR()\">Set</button>";
		}
		echo "</div>";
		if ($data->getSoloMMR() !== "N/A" && $data->getPartyMMR() !== "N/A") {
			echo "<button id=\"addgamebutton\" onclick=\"toggleAddGameDialog()\">Add Game</button>";
		}
	}

	function createGameHTML(GameData $game)
	{
		$result = $game->gameWon == "true" ? "Game Won" : "Game Lost";
		$queue = $game->queueType == "solo" ? "Solo Queue" : "Party Queue";
		$mmrChange = $game->gameWon == "true" ? "+" . $game->mmrChange : "\xa0-" . + $game->mmrChange;
		$color = $game->gameWon == "true" ? "#00CC00" : "#E60000";
		$hero_icon = $game->hero != "0" ? "http://cdn.dota2.com/apps/dota2/images/heroes/".$game->hero."_sb.png" : "/images/no_hero.png";

		$gameHTML = "<div class=\"gamedata\">".
			"<img class=\"heroiconoutline\" src=\"/images/herobox.png\">".
			"<img class=\"heroicon\" src={$hero_icon}>".
			"<b class=\"gameresulttext\">{$result}</b>".
			"<b class=\"gamemmrtext\"><font color={$color}>\xa0{$mmrChange}\xa0</font><font color=\"white\">@ {$game->currentMMR}</font></b>".
			"<p class=\"queuetype\">{$queue}</p>".
			"</div>";
		return $gameHTML;
	}

	function populateGamesList()
	{
		$user = $_SESSION['UserData'];
		$rev = array_reverse($user->getGames()); //reverse the array of games so newest is first
		$count = count($rev);
		foreach ($rev as $game)
		{
			$gameHTML = createGameHTML($game);
			echo $gameHTML;
		}
	}

	//responses for server requests
	session_start();

	if (isset($_POST['SetMMR'])) //sets a player's base MMR
	{
		$type = $_POST['type'];
		$value = intval($_POST['value']);
		$user = $_SESSION['UserData'];
		if ($type == "solommr") {
			$user->setBaseSoloMMR($value);
			$user->setSoloMMR($value);
		}
		elseif ($type == "partymmr") {
			$user->setBasePartyMMR($value);
			$user->setPartyMMR($value);
		}
		echo json_encode($user);
	}

	elseif (isset($_POST['AddGame'])) //adds a game to player's data
	{
		$user = $_SESSION['UserData'];
		$hero = $_POST['hero'];
		$gameWon = $_POST['result'];
		$queue = ($_POST['queue'] == "true" ? "solo" : "party"); //PHP doesn't do boolean comparisons properly so we must convert booleans to strings
		$mmrChange = intval($_POST['mmrChange']);

		$gamedata = new GameData($hero, $queue, $gameWon, $mmrChange);
		$gamedata->currentMMR = $user->getMMR($queue);
		$user->addGame($gamedata);
		$user->addMMR($queue, $mmrChange * ($gameWon == "true" ? 1 : -1));
		echo str_replace("\xa0", "&nbsp;", createGameHTML($gamedata));
	}

	elseif (isset($_POST['GetGameData'])) //returns the amount to games tracked
	{
		$data = array(
			"gameCount" => count($_SESSION['UserData']->getGames()),
			"soloMMR" => $_SESSION['UserData']->getSoloMMR(),
			"partyMMR" => $_SESSION['UserData']->getPartyMMR()
		);
		echo json_encode($data);
	}
?>