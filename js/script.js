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

function makeImage(rank, avg) {
	rank = Math.min(rank, 9);
	var colors = ["808080", "8E7272", "9C6464", "AB5555", "B94747", "C72525", "D52B2B", "E41C1C", "F20E0E", "FF0000"];
	
	return "http://thydzik.com/thydzikGoogleMap/markerlink.php?text="+avg+"&color="+colors[rank];
}

function createMarker(obj) {
	var marker = new google.maps.Marker({
		position: new google.maps.LatLng(obj.lat, obj.lng), 
		map: map,
		title:obj.title
	});
	kvo.set("id"+obj.id, obj);
	atom_ratings["id"+obj.id] = new Array();
	//get 4 most recent ratings for id store in above
	var s = "";
	
	$.getJSON("classes/ajax_util.php",
	  { action: "get_rankings_for_atom", atom_id:(obj.id) }, function(data) {
			atom_ratings["id"+obj.id] = data;
			marker.setIcon(makeImage(data.length, parseInt(obj.avg)))
	});
	
	
	
	google.maps.event.addListener(marker, 'click', function() {
		clickmarker(marker, obj.id)
	});
	addMarker(obj.id, marker);
	return true;
}

function clickmarker(marker, id, avg) {
	if (lastInfoWindow) lastInfoWindow.close();
	s = "";
	for(ranking in atom_ratings["id"+id]) {
		var d = "";
		for(var i = 0; i < parseInt(atom_ratings["id"+id][ranking].rank); i++) {
			d += "&#9733;";
		}
		s += d+" "+atom_ratings["id"+id][ranking].comment+"<br />";
	}
	
	lastInfoWindow = new google.maps.InfoWindow({
		content: '<b><a class="mapdesc">'+kvo.get(storestr(id)).title+'</a></b><br />'+"<a href='javascript:rank("+id+")'>Add Ranking</a> | <a href='javascript:subscribe("+id+")'>Subscribe via SMS</a><br /><br />"+s,		
	});
	lastInfoWindow.open(map, marker);
	marker.setIcon(makeImage(atom_ratings["id"+id].length, parseInt(avg)))
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
				//console.log(aminlng+" "+aminlat+" "+amaxlng+" "+amaxlat+" ");
				//console.log(data);
				
				for(res in data) {
					var aid = data[res].atom_id;
					atom_ratings["id"+aid].push(data[res]);
					var mark = markers[storestr(aid)];
					
					$.getJSON("classes/ajax_util.php",
					  { action: "get_average", atom:aid }, function(data) {
							setTimeout(clickmarker(mark, aid, data), 100);
					});
					
				}
		});
	}, 3000);
	
	//map.setCenter(initialLocation);
});

function rank(atom_id) {
	rank_atom_id = atom_id;
	$("#add_ranking").show();
}

function subscribe(atom_id) {
	phone_atom_id = atom_id;
	$("#add_phone").show();
}