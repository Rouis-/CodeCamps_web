var	chaine = new Array("Bidon", "TF1", "France 2", "France 3", "Canal +","France 5",
			   "M6", "Arte", "D8", "W9", "TMC", "NT1", "NRJ 12", "LCP",
			   "France 4", "BFM TV","iTele", "D17", "Gulli", "France Ô",
			   "HD1", "L'Équipe 21", "6ter", "Numéro 23", "RMC Découverte",
			   "Chérie 25", "RTL 9", "AB1", "TV5 Monde", "Paramount",
			   "Paris Première", "Canal + Cinéma", "Canal + Sport",
			   "Canal + Family", "Canal + Décalé", "beIN 1", "beIN 2"
			  );
var buttons = new Array();

for (var i = 1, i3 = 1; i <= 6; ++i) {
    $("<center>").attr({"id":i}).appendTo("#buttons");
    $("<p>").attr({"id":"p"+i}).appendTo("#"+i);
    for (var i2 = 1; i2 <= 6; ++i2, ++i3)
	buttons[i3] = $("<button>").attr({
	    "type":"button", "class":"btn btn-primary btn-lg",
	    "onclick":"request("+i3+")", "data-toggle":"modal",
	    "data-target":"#myModal"+i3, "style":"margin-left:3px"
	}).text(chaine[i3]).appendTo("#p"+i);
}

for (i = 1; i <= 36; ++i) {
    $("<div>").attr({
	"class":"modal fade", "id":"myModal" + i, 
	"tabindex":"-1", "role":"dialog",
	"aria-labelledby":"myModalLabel"+i,
	"aria-hidden":"true"
    }).appendTo("body");
    $("<div>").attr({"class":"modal-dialog", "id":"md"+i}).appendTo("#myModal"+i);
    $("<div>").attr({"class":"modal-content", "id":"mc"+i}).appendTo("#md"+i);
    $("<div>").attr({"class":"modal-header", "id":"mh"+i}).appendTo("#mc"+i);
    $("<button>").attr({"type":"button", "id":"close"+i, "class":"close",
			"data-dismiss":"modal", "aria-label":"Close"}).appendTo("#mh"+i);
    $("<span>").attr({"aria-hidden":"true"}).text("&times;").appendTo("close"+i);
    $("<h4>").attr({"class":"modal-title", "id":"myModalLabel"+i}).appendTo("#mh"+i);
    $("<div>").attr({"class":"modal-body", "id":"C"+i}).appendTo("#mc"+i);
    $("<div>").attr({"class":"modal-footer", "id":"mf"+i}).appendTo("#mc"+i);
    $("<button>").attr({"type":"button", "class":"btn btn-default",
			"data-dismiss":"modal"}).text("Fermer").appendTo("#mf"+i);
}