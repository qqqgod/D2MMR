<?php
	class UserData implements JSONSerializable
	{
		public $steamID64 = "no id set";
		public $games = 0;
		public $soloMMR = -1;
		public $partyMMR = -1;

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
       			"games" => $this->games,
       			"soloMMR" => $this->soloMMR,
       			"partyMMR" => $this->partyMMR,
       		);
        	return $data;
   		}

   		public function readFromFile()
   		{
   			$data = json_decode(file_get_contents("userdata/{$this->steamID64}.json"), true);
   			$this->games = $data['games'] ? $data['games'] : 0;
   			$this->soloMMR = $data['soloMMR'] ? $data['soloMMR'] : -1;
   			$this->partyMMR = $data['partyMMR'] ? $data['partyMMR'] : -1;
   		}

   		public function saveToFile()
   		{
   			$file = fopen("userdata/{$this->steamID64}.json", "w+");
   			fwrite($file, json_encode($this));
   			fclose($file);
   		}

   		public function getGames() { return $this->games; }

   		public function getSoloMMR() { return $this->soloMMR != -1 ? $this->soloMMR : "N/A"; }

   		public function getPartyMMR() { return $this->partyMMR != -1 ? $this->partyMMR : "N/A"; }
	}

	function displayHTML(UserData $data)
	{
		//re-read data from file
		$data->readFromFile();
		echo "<p id=\"gamestracked\">&nbsp;&nbsp;&nbsp;Games Tracked: {$data->getGames()}</p>";
		echo "<p id=\"solommr\">&nbsp;&nbsp;&nbsp;Solo MMR: {$data->getSoloMMR()}</p>";
		echo "<p id=\"partymmr\">&nbsp;&nbsp;&nbsp;Party MMR: {$data->getPartyMMR()}</p>";

		if ($data->getSoloMMR() === "N/A") {
			echo "<button id=\"setsolommr\" onclick=\"setSoloMMR()\">Set</button>";
		}
		if ($data->getPartyMMR() === "N/A") {
			echo "<button id=\"setpartymmr\">Set</button>";
		}
	}
?>