//number: the number to round up
//multiple: the multiple to round number up to
//Math.roundUp(464, 100) -> 500
Math.roundUp = function(number, multiple) {
	return Math.ceil(number / multiple) * multiple;
}	

//number: the number to round down
//multiple: the multiple to round number down to
//Math.roundDown(464, 100) -> 400
Math.roundDown = function(number, multiple) {
	return Math.floor(number / multiple) * multiple;
}

Math.getRandomInt = function(min, max) {
	return Math.floor(Math.random() * (max - min + 1) + min);
}

function generateRandomGameData()
{
	var graphData = new GraphData();
	var soloMMR = Math.getRandomInt(2000, 6000);
	var partyMMR = soloMMR + Math.getRandomInt(-300, 300);
	console.log("*****************");
	console.log("Generating random game data");
	console.log("Starting Solo MMR: " + soloMMR);
	console.log("Starting Party MMR: " + partyMMR);
	graphData.addGame(0, soloMMR, partyMMR);

	var games = Math.getRandomInt(15, 50);

	for (var i = 1; i < games; i++) {
		soloMMR = soloMMR + Math.getRandomInt(-1, 1) * 25;
		partyMMR = partyMMR + Math.getRandomInt(-1, 1) * 25;
		graphData.addGame(i, soloMMR, partyMMR);
	}
	return graphData;
}	

function GraphData()
{
	this.maxGames = 0;
	this.minMMR = 0;
	this.maxMMR = 0;
	this.gamesData = [];

	this.addGame = function(games, solo, party) {
		this.gamesData.push([games, solo, party]);
		this.maxGames = this.gamesData.length;
		this.calculateMinMaxMMR();
	}

	this.calculateMinMaxMMR = function() {
		var min = null;
		var max = null;

		for (var i = 0; i < this.gamesData.length; i++) {
			var data = this.gamesData[i];

			if (min == null && max == null) {
				if (data[1] >= data[2]) {
					max = data[1];
					min = data[2];
				}
				else if (data[1] < data[2]) {
					max = data[2];
					min = data[1];
				}
			}

			if (data[1] > max) {
				max = data[1];
			}
			if (data[1] < min) {
				min = data[1];
			}
			if (data[2] > max) {
				max = data[2];
			}
			if (data[2] < min) {
				min = data[2];
			}
		}

		this.minMMR = min;
		this.maxMMR = max;
	}
}