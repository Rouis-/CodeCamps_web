function decode(url)
{
    var shaObj = new jsSHA(url, "TEXT");
    var hmac = shaObj.getHMAC("9ff46e3be37783a01171850140f4f22f", "TEXT", "SHA-512", "HEX");

    return (hmac);
}

function request(i)
{
    var url = "http://94.23.253.36:8080/TiVineWS_V1.0/GetAllContentForPartAndChannel0" + i +"10989852";
    var encodedKey = decode(url);

    $.ajax({
	type: "POST",
	url: "http://94.23.253.36:8080/TiVineWS_V1.0/GetAllContentForPartAndChannel",
	data: {"part" : "0", "channel" : i, "clientId" : "10989852", "encodedKey" : encodedKey},
	success: function(data) {
	    var title = data.programs['0']['title'];
	    $("#myModalLabel" + i).text(title).css("margin-left", "auto").css("margin-right", "auto").css("width", "6em");
	    if($("#C" + i + " > img").length)
		$("#img" + i).remove("<img>");
	    if($("#C" + i + " > div").length)
		$("#img" + i).remove("<div>");
	    $("#C" + i).append("<img>");
	    $("#C" + i).append("<div>");
	    $("#C" + i + " > img").attr("id", "img" + i).attr("src", data.programs['0']['image']);
	    $("#C" + i + " > img").css("width","50%").css("display","block").css("margin-left", "auto").css("margin-right", "auto");
	    $("#C" + i + " > div").text(data.programs['0']['desc']).attr("id", "divi" + i);
	    request_php(i);
	},
	error: function() {
	    console.log(Error);
	}
    });
}

function request_php(i)
{
    var url = "./data_DB.php?channel=" + i;

    $.ajax({
        type: "GET",
        url: url,
	success: function(data) {
	    var obj = jQuery.parseJSON(data);
	    add_words_ranking(i, obj);
	},
	error: function() {
            console.log(Error);
        }
    });
}

function add_words_ranking(i, data)
{
    var div = $('<div>');
    var div_cont = $("#C" + i);
    var r = 1;
    var h = $('<h4>');

    h.text("Classement des mots les plus tweetés récemment sur Twitter pour ce programme :");
    h.appendTo(div);
    for (var key in data)
    {
	var p = $('<p>');
	p.text(r + ". Le mot '" + key + "' a été tweeté " + data[key] + " fois.");
	p.appendTo(div);
	++r;
    }
    div.appendTo(div_cont);
}