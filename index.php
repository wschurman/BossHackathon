<!doctype html>
<!--[if lt IE 7 ]> <html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title></title>
  <meta name="description" content="">
  <meta name="author" content="">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="shortcut icon" href="/favicon.ico">
  <link rel="apple-touch-icon" href="/apple-touch-icon.png">
  <link rel="stylesheet" href="css/style.css?v=2">
  <script src="js/libs/modernizr-1.7.min.js"></script>

</head>

<body>

  <div id="map"></div> 
	<form id="add_ranking" action="classes/ajax_util.php" method="post">
		<div class="review_content">
			<select id="atoms" name="atoms">
				<?php include("classes/dropdown.php"); ?>
			</select>
			<br /><br />
			<div id="stars">
				Rating:
				<a id="s1" title="1">&#x2605;</a>
				<a id="s2" title="2">&#x2605;</a>
				<a id="s3" title="3">&#x2605;</a>
				<a id="s4" title="4">&#x2605;</a>
				<a id="s5" title="5">&#x2605;</a>
			</div>
			<textarea name="review_box" id="review_box" placeholder="write a review..." class="required" minlength="2"></textarea>
			<input type="submit" value="Post" />
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

	// $('#add_review').validate({
	// 		submitHandler: function(form) { 
	// 			$(form).ajaxSubmit({
	// 				data: { action: 'add_review', rating_box: numstars, spot_id: <?php echo $spot_id ?> },
	// 				success: function(resp) {
	// 					$("#add_review").hide();
	// 					$("#review_container ul").prepend(resp);
	// 				},
	// 				error: function(resp) { //board not found
	// 					$("#add_review").html(resp);
	// 				}
	// 			});
	// 			return false;
	// 		}
	// 	});
	</script>  

<!-- end scripts-->

  <!--[if lt IE 7 ]>
    <script src="js/libs/dd_belatedpng.js"></script>
    <script>DD_belatedPNG.fix('img, .png_bg'); // Fix any <img> or .png_bg bg-images. Also, please read goo.gl/mZiyb </script>
  <![endif]-->


  <script>
    var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']]; // Change UA-XXXXX-X to be your site's ID
    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
    g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g,s)}(document,'script'));
  </script>

</body>
</html>