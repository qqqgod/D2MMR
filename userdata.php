<?php
	class UserData
	{
		public $steamID64 = "";
		public $gamesTracked = 0;
		public $soloMMR = -1;
		public $partyMMR = -1;

		function __construct($steamID)
		{
			$this->steamID64 = $steamID;
		}
	}

	function displayHTML(UserData $data)
	{
		echo "<button>YOLO</button>";
		echo "<p id=\"gamestracked\">&nbsp;&nbsp;&nbsp;Games Tracked: 0</p>";
		echo "<p id=\"solommr\">&nbsp;&nbsp;&nbsp;Solo MMR: N/A</p>";
		echo "<p id=\"partymmr\">&nbsp;&nbsp;&nbsp;Party MMR: N/A</p>";
	}
?>