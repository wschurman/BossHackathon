

<?
# module for sending sms
# totally not a hack at all
error_reporting(E_ALL);
require("mysql.php");
$domains = array("tmomail.net",				# T-Mobile
			"vmobl.com",			# Virgin Mobile
			"cingularme.com",		# Cingular
			"messaging.sprintpcs.com",	# Sprint
			"txt.att.net",			# AT&T
			"vtext.com",			# Verizon
			"messaging.nextel.com",		# Nextel
			"email.uscc.net",		# US Cellular
			"sms.mycricket.com",		# Cricket
			"mymetropcs.com");		# Metro PCS


function send_sms($number) {
	$conn = mysql_connect($db_host, $db_user, $db_pass) or die ("<h1>Could not connect to the database.</h1><h2>Please try again later.</h2>");
	mysql_select_db($db_name, $conn) or die ("Fuck");
	$result = mysql_query("SELECT phone,provider FROM numbers WHERE number='".filter_var($number,FILTER_SANITIZE_STRING).'"', $conn) or die ("fuck.");
	$row = mysql_fetch_array($result);
	mail($row["phone"]."@".$domains[$row["provider"]], "SigPhiSucks Comment Notification", "Hello,\n A comment you requested to track has been updated!! Also Sig Phi sucks.");
	mysql_close($conn);
}
?>

