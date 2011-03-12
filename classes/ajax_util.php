<?

$ajax = ($_SERVER['HTTP_X_REQUESTED_WITH']  == 'XMLHttpRequest');
if(!$ajax) {
	header("Location: ../");
	die("Not AJAX.");
}

require_once("RatingClass.php");
$controller = new RatingClass();

if($_POST['action']):

try {
	switch ($_POST['action']) {
		default:
			throw new Exception("Invalid action.");
	}
} catch (Exception $e) {
	header("HTTP/1.0 500 Internal Server Error");
	echo $e->getMessage();
	exit();
}

elseif($_GET['action']):

try {
	switch ($_GET['action']) {
 		case "get_rankings_for_atom":
			$atid = filter_var($_GET["atom_id"], FILTER_SANITIZE_NUMBER_INT);
			$data = $controller->get_rankings_for_atom($atid);
			echo $data;
	        break;
		case "get_initial_atoms":
			$data = $controller->get_initial_atoms();
			echo $data;
	        break;
		case "get_recent_rankings":
			$minlat = filter_var($_GET["minlat"], FILTER_SANITIZE_STRING);
			$maxlat = filter_var($_GET["maxlat"], FILTER_SANITIZE_STRING);
			$minlon = filter_var($_GET["minlng"], FILTER_SANITIZE_STRING);
			$maxlon = filter_var($_GET["maxlng"], FILTER_SANITIZE_STRING);

			$data = $controller->get_recent_rankings($minlat, $maxlat, $minlon, $maxlon);
			echo $data;
	        break;
		default:
			throw new Exception("Invalid action.");
	}
} catch (Exception $e) {
	header("HTTP/1.0 500 Internal Server Error");
	echo $e->getMessage();
	exit();
}

else:

header("HTTP/1.0 500 Internal Server Error");
echo "Invalid action.";
exit();

endif;

?>