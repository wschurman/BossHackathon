/* Author: 

*/
var kvo = new google.maps.MVCObject();

var atom_ratings = new Array();

var markers = new Array();
var lastInfoWindow;

function square(n) { return n*n; }
function dist(p1, p2) {
	return Math.sqrt(square(p1.lat() - p2.lat) + square(p1.lng() - p2.lng()));
}
function storestr(id) {
	return "id"+id;
}
function addMarker(id, marker) {
	markers[storestr(id)] = marker;
}
function clearMarkers() {
	for(var i in markers) {
		markers[i].setMap(null);
	}
	markers = new Array();
	$("#spot_list ul").html("");
}

var latlng = new google.maps.LatLng(42.448221, -76.489538);
var myOptions = {
	zoom: 17,
	center: latlng,
	minZoom: 3,
	mapTypeId: google.maps.MapTypeId.ROADMAP
};

var map = new google.maps.Map(document.getElementById("map"), myOptions);

function createMarker(obj) {
	var marker = new google.maps.Marker({
		position: new google.maps.LatLng(obj.lat, obj.lng), 
		map: map,
		title:obj.title,
		//icon:img
	});
	kvo.set("id"+obj.id, obj);
	atom_ratings["id"+obj.id] = new Array();
	//get 4 most recent ratings for id store in above
	var s = "";
	
	$.getJSON("classes/ajax_util.php",
	  { action: "get_rankings_for_atom", atom_id:(obj.id) }, function(data) {
			atom_ratings["id"+obj.id] = data;
	});
	
	google.maps.event.addListener(marker, 'click', function() {
		clickmarker(marker, obj.id)
	});
	addMarker(obj.id, marker);
	return true;
}

function clickmarker(marker, id) {
	if (lastInfoWindow) lastInfoWindow.close();
	s = "";
	for(ranking in atom_ratings["id"+id]) {
		console.log(ranking);
		var d = "";
		for(var i = 0; i < parseInt(atom_ratings["id"+id][ranking].rank); i++) {
			d += "&#9733;";
		}
		s += d+" "+atom_ratings["id"+id][ranking].comment+"<br />";
	}
	
	lastInfoWindow = new google.maps.InfoWindow({
		content: '<b><a class="mapdesc">'+kvo.get(storestr(id)).title+'</a></b><br />'+s,		
	});
	lastInfoWindow.open(map, marker);
}

$.getJSON("classes/ajax_util.php",
  { action: "get_initial_atoms" }, function(data) {
	for (spot in data) {
		createMarker(data[spot]);
	}
});

$(document).ready(function() {
	//every 2 seconds, get new ratings and store in atom_ratings["id"+obj.id], update size of icons/icons
	
	var auto_refresh = setInterval( function () {
		
		var bounds = map.getBounds();
		var southWest = bounds.getSouthWest();
		var northEast = bounds.getNorthEast();
		var aminlng = southWest.lng();
		var aminlat = southWest.lat();
		var amaxlng = northEast.lng();
		var amaxlat = northEast.lat();
		
		$.getJSON("classes/ajax_util.php",
		  { action: "get_recent_rankings", minlng:aminlng, minlat:aminlat, maxlng:amaxlng, maxlat:amaxlat }, function(data) {
				//atom_ratings["id"+obj.id] = data;
				console.log(aminlng+" "+aminlat+" "+amaxlng+" "+amaxlat+" ");
				console.log(data);
				
				for(res in data) {
					var aid = data[res].atom_id;
					atom_ratings["id"+aid].push(data[res]);
					
					var mark = markers[storestr(aid)];
					setTimeout(clickmarker(mark, aid), 500);
				}
		});
	}, 5000);
	
	//map.setCenter(initialLocation);
});