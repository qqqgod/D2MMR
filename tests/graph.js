google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(loadComplete);

var BLUE = '#3366CC';
var RED = '#DC3912';
var GAMES = 0;
var SOLOMMR = 1;
var PARTYMMR = 2;


var data, dataView, chart;
var options = {
    title: 'Ranked Matchmaking Rating',
    pointSize: 7,
    vAxis: {
        ticks: [
        	1800, 1900, 2000, 2100, 2200
        ]
    },
    hAxis: {
        maxValue: 10,
        ticks: [
        	0, 5, 10
        ]
    }
};

function loadComplete()
{
	var data = new google.visualization.DataTable();
	data.addColumn('number', 'Games');
	data.addColumn('number', 'Solo MMR');
	data.addColumn('number', 'Party MMR');
	data.addRows([
		[0, 2000, 2000],
		[1, 2025, 1975],
		[2, 2050, 2000],
		[3, 2075, 2025],
		[4, 2050, 2050],
		[5, 2075, 2075],
		[6, 2100, null],
		[7, 2125, null],

	]);

	dataView = new google.visualization.DataView(data);

    chart = new google.visualization.LineChart(document.getElementById('graph_div'));
    chart.draw(dataView, options);
}

function setColumns(columns)
{
	dataView.setColumns(columns);
}

function btnSoloOnlyClick()
{
	dataView.setColumns([GAMES, SOLOMMR]);
	options.colors = [BLUE];
	chart.draw(dataView, options);
}

function btnPartyOnlyClick()
{
	dataView.setColumns([GAMES, PARTYMMR]);
	options.colors = [RED];
	chart.draw(dataView, options);
}

function btnBothClick()
{
	options.colors = [BLUE, RED];
	dataView.setColumns([GAMES, SOLOMMR, PARTYMMR]);
	chart.draw(dataView, options);
}