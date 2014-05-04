var BLUE = '#3366CC'; 
var RED = '#DC3912'; 
var GAMES = 0; 
var SOLOMMR = 1; 
var PARTYMMR = 2; 
  
function MMRGraph()  
{ 
    this.data = null
    this.dataView = null; 
    this.chart = null; 
  
    this.options = { 
        title: 'Ranked Matchmaking Rating', 
        pointSize: 6, 
        lineWidth: 3,
        vAxis: { 
            ticks: [ 
                1900, 2000, 2100, 2200 
            ],
            minorGridlines: {
            	count: 3
            },
            viewWindow: {
            }
        }, 
        hAxis: { 
            ticks: [ 
                0, 5, 10 
            ],
            minorGridlines: {
            	count: 4
            }
        },
        chartArea: { 
            left: 65, 
            top: 25, 
            height: 500, 
            width: 700 
        } 
    }; 
  
    //games: the highest amount of games for either solo or party 
    this.setNumGames = function(games) { 
        var max = Math.roundUp(games, 5); //round up to the nearest multiple of 5 
        var ticks = new Array(); 
        for (var i = 0; i <= max; i += 5) { 
            ticks.push(i); 
        } 
        this.options.hAxis.ticks = ticks; 
        this.chart.draw(this.dataView, this.options); 
    } 
  
    this.loadComplete = function() 
    { 
    	var graphData = generateRandomGameData();


        this.data = new google.visualization.DataTable(); 
        this.data.addColumn('number', 'Games'); 
        this.data.addColumn('number', 'Solo MMR'); 
        this.data.addColumn('number', 'Party MMR'); 
        this.data.addRows(graphData.gamesData); 
  
        this.dataView = new google.visualization.DataView(this.data); 
        this.chart = new google.visualization.LineChart(document.getElementById('graph_div')); 


        this.setNumGames(graphData.maxGames);
        this.setMinMaxMMR(graphData.minMMR, graphData.maxMMR);

        this.chart.draw(this.dataView, this.options); 
    } 
  
  
    this.setMinMaxMMR = function(min, max) 
    { 
    	if (min >= max) {
    		alert("An error occured: [min >= max]");
    		return;
    	}
        max = Math.roundUp(max, 100);
        min = Math.roundDown(min, 100);
        var ticks = new Array(); 
        for (var i = min; i <= max; i += 100) { 
            ticks.push(i); 
        }
        this.options.vAxis.ticks = ticks;
        this.options.vAxis.viewWindow.max = max + 30;
        this.options.vAxis.viewWindow.min = min - 30;
        this.chart.draw(this.dataView, this.options); 
    } 
  
    //called when one of the checkboxes is clicked 
    //hides and shows desired columns 
    this.changeColumns = function() 
    { 
        var soloChecked = $("#chkSolo").prop("checked"); 
        var partyChecked = $("#chkParty").prop("checked"); 
  
        var columns = new Array(); 
        columns.push(GAMES); //always display the games column 
  
        this.options.colors = [BLUE, RED]; //default colors 
  
        if (soloChecked && !partyChecked)  
        { 
            columns.push(SOLOMMR); //add soloMMR column 
            this.options.colors = [BLUE]; //set color to blue 
            $("#chkSolo").attr("disabled", true); //disable solo checkbox 
        } 
        else if (!soloChecked && partyChecked)  
        { 
            columns.push(PARTYMMR); //add partyMMR column 
            this.options.colors = [RED]; //set color to red 
            $("#chkParty").attr("disabled", true); //disable party checkbox 
        } 
        else if (soloChecked && partyChecked)  
        { 
            columns.push(SOLOMMR); //add both columns 
            columns.push(PARTYMMR); 
            $("#chkSolo").removeAttr("disabled"); //enabled both checkboxes 
            $("#chkParty").removeAttr("disabled"); 
        } 
  
        this.dataView.setColumns(columns); 
        this.chart.draw(this.dataView, this.options); //draw the chart after we've modified it 
    } 
}