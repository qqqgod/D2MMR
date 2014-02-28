function setSoloMMR()
{
	var button = $("#setsolommr");
	if (button.text() === "Set") {
		button.css("left", "215px");
		button.text("OK");
		//button.attr("disabled", true);
		var str = "<input id=\"inputsolommr\" type=\"number\" min=\"1\" max=\"7000\" onkeypress=\"return isNumber(event)\">"
		$("#bodydiv").append(str);
		$("#inputsolommr").focus();
	}
	else {
		var input = $("#inputsolommr").val();
		button.hide();
		$("#inputsolommr").remove();
	}
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