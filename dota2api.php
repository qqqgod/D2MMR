<?php
	//returns a list of heroes from the Dota 2 API
	//TODO: order the list alphabetically
	$url = "https://api.steampowered.com/IEconDOTA2_570/GetHeroes/v0001/?language=cn&key={$apikey}";
	$json = json_decode(file_get_contents($url));
	sort($json->result->heroes);
	foreach ($json->result->heroes as $hero) {
		$name = str_replace("npc_dota_hero_", "", $hero->name);
		echo "<option value={$name}>{$hero->localized_name}</option>";
	}
?>