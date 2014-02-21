var CANVAS_WIDTH = 800;
var CANVAS_HEIGHT = 600;

var canvasElement = $("<canvas width='" + CANVAS_WIDTH + "' height='" + CANVAS_HEIGHT + "'></canvas>");
var canvas = canvasElement.get(0).getContext("2d");
canvasElement.appendTo('body');
///////////////////////////////


var marvins = 0;

function draw() 
{	
    	canvas.clearRect(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);
	canvas.fillStyle = "#FF0000";
    	canvas.fillRect(0, 0, 100, 100);
	canvas.fillText("Marvins: " + marvins, 0, 200);
}

function update()
{

}

var FPS = 30;
setInterval(function() {
  	update();
  	draw();
}, 1000/FPS);

canvasElement.click(function(e) {
	var x = Math.floor((e.pageX-canvasElement.offset().left) / 20);
    	var y = Math.floor((e.pageY-canvasElement.offset().top) / 20);
});