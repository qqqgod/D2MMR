function setMMRButtonClick(mmrType)
{
	var button = $("#set" + mmrType);
	if (button.text() === "Set") {
		button.css("left", "208px");
		button.text("OK");
		var str = "<input id=\"input" + mmrType + "\" type=\"number\" min=\"1\" max=\"7000\" onkeypress=\"return isNumber(event)\">"
		$("#" + mmrType + "div").append(str);
		$("#input" + mmrType).focus();
		$("#input" + mmrType).val(1);
	}
	else {
		var input = $("#input" + mmrType).val();
		button.remove();
		$("#input" + mmrType).remove();
		//use AJAX to call php function that sets the MMR
		$.ajax({
			url: 'userdata.php',
			data: {
				SetMMR: '1',
				type: mmrType,
				value: input
			},
			type: 'post',
			success: function(result) 
			{
				var data = $.parseJSON(result);
				$("#solommr").text("Solo MMR: " + (data.soloMMR != -1 ? data.soloMMR : "N/A"));
				$("#partymmr").text("Party MMR: " + (data.partyMMR != -1 ? data.partyMMR : "N/A"));
				if ($("#solommr").text().indexOf("N/A") == -1 && $("#partymmr").text().indexOf("N/A") == -1) {
					var buttonHTML = "<button id=\"addgamebutton\" onclick=\"toggleAddGameDialog()\">Add Game</button>";
					$("#bodydiv").append(buttonHTML);
				}
			}
		});
	}
}

//called when the user clicks the button to set the solo mmr
function setSoloMMR()
{
	setMMRButtonClick("solommr");
}

//called when the user clicks the button to set the party mmr
function setPartyMMR()
{
	setMMRButtonClick("partymmr");
}

//called when a user adds a game with the Add Game popup
function addGameButtonClick()
{
	var h = $("#heroselector").val();
	var res = document.getElementById("gamewonradio").checked;
	var q = document.getElementById("sologameradio").checked;
	var mmr = $("#mmrchangeinput").val();

	if (mmr > 35) {
		alert("You cannot gain/lose more than 35 MMR in one game");
		return;
	}
	var resultHTML;
	
	$.ajax({
		url: 'userdata.php',
		data: {
			AddGame: '1',
			hero: h,
			result: new Boolean(res).toString(),
			queue: new Boolean(q).toString(),
			mmrChange: mmr
		},
		type: 'post',
		success: function(result) //returns HTML for displaying the new game data
		{
			window.location = "index.php?page=1";
			/*result = result.replace("\xa0", "&nbsp;");
			$("#gamesdivheader").after(result); //add the HTML to the top of the games div
			toggleAddGameDialog(); //hide the dialog afterwards

			$.ajax({
				url: 'userdata.php',
				data: {
					GetMMRData: '1'
				},
				type: 'post',
				success: function(result) {
					result = $.parseJSON(result);
					$("#solommr").text("Solo MMR: " + result.soloMMR);
					$("#partymmr").text("Party MMR: " + result.partyMMR);
					$("#gamestracked").text("Games Tracked: " + result.gameCount);
				}
			});*/
		}
	});
}

function isNumber(evt) 
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}