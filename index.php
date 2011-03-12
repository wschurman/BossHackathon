<!DOCTYPE HTML>
<!--[if lt IE 7 ]> <html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title>GeoMapChan | 4chan + ACB + Map</title>
  <meta name="description" content="awesome">
  <meta name="author" content="Dem Bo$$es">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="shortcut icon" href="/favicon.ico">
  <link rel="apple-touch-icon" href="/apple-touch-icon.png">
  <link rel="stylesheet" href="css/style.css?v=2">
  <script src="js/libs/modernizr-1.7.min.js"></script>

</head>

<body>
	<script type="text/javascript">
	var rank_atom_id;
	var phone_atom_id;
	</script>
  <div id="map"></div> 
	<form id="add_ranking" method="post">
		<div class="review_content">
			<div id="stars">
				Rating:
				<a id="s1" title="1">&#9733;</a>
				<a id="s2" title="2">&#9733;</a>
				<a id="s3" title="3">&#9733;</a>
				<a id="s4" title="4">&#9733;</a>
				<a id="s5" title="5">&#9733;</a>
			</div>
			<textarea name="review_box" id="review_box" placeholder="write a review..." class="required" minlength="2"></textarea><br /><br />
			<input type="submit" value="Post" />
		</div>
	</form>
	
	<form id="add_phone" method="post">
		<div class="review_content">
			Phone Number: <input type="text" id="number" name="number" /><br /><br />
			Carrier: 
				<select id="carrier" name="carrier">
					<option value="tmomail.net">T-mobile</option>
					<option value="vmobl.com">Virgin Mobile</option>
					<option value="cingularme.com">Cingular</option>
					<option value="messaging.sprintpcs.com">Sprint</option>
					<option value="txt.att.net">AT&amp;T</option>
					<option value="vtext.com">Verizon</option>
					<option value="messaging.nextel.com">Nextel</option>
					<option value="email.uscc.net">US Cellular</option>
					<option value="sms.mycricket.com">Cricket</option>
					<option value="mymetropcs.com">Metro PCS</option>
				</select>
				<br /><br />
			<input type="submit" value="Bitch, Please." />
		</div>
	</form>
	

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js" type="text/javascript"></script>
  <script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
  <!-- scripts concatenated and minified via ant build script-->
  <script src="js/plugins.js"></script>
  <script src="js/script.js"></script>

	<script type="text/javascript">
	var numstars = 3;
	
	$("#stars a").hover(
	  function () {
		$('#stars a').removeClass("sel");
		var cur = $(this).attr('title');
		$('#stars > a:lt('+cur+')').addClass("sel");
	  }, 
	  function () {
	    $('#stars a').removeClass("sel");
		$('#stars > a:lt('+numstars+')').addClass("sel");
	  }
	);
	
	$("#stars a").click(function() {
		numstars = $(this).attr('title');
		$('#stars a').removeClass("sel");
		$('#stars > a:lt('+numstars+')').addClass("sel");
	});
	
	$("#s3").click();

	$("#add_ranking").submit(function() {
	      $.getJSON("classes/ajax_util.php",
		  { action: "add_ranking", atom:rank_atom_id, stars:numstars, review:$("#review_box").val() }, function(data) {
		});
		$("#review_box").val("");
		$("#add_ranking").hide();
	      return false;
	    });
	
		$("#add_phone").submit(function() {
		      $.getJSON("classes/ajax_util.php",
			  { action: "add_phone", atom:phone_atom_id, phone:$("#number").val(), carrier:$("#carrier").val() }, function(data) {
			});
			$("#number").val("");
			$("#add_phone").hide();
		      return false;
		    });
	</script>  

</body>
</html>