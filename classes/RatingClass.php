<?php
require("mysql.php");

class RatingClass {
	
	public function addAtom($title, $description, $cat_id, $lat, $lng) {
		global $mysqli;
		$stmt = $mysqli->prepare("INSERT IGNORE INTO atoms (title, description, category_id, lat, lng) VALUES (?, ?, ?, ?, ?)");
		$stmt->bind_param("ssidd", $title, $description, $cat_id, $lat, $lng );
		$stmt->execute();
		$result = $mysqli->prepare("SELECT LAST_INSERT_ID()");
		$result->execute();
		$result->bind_result($bid);
		$result->fetch();
		$stmt->close();
		$result->close();
		return $bid;
	}
	
	public function addRanking($rank, $comment, $atom_id) {
		global $mysqli;
		$stmt = $mysqli->prepare("INSERT IGNORE INTO rankings (rank, comment, time, atom_id) VALUES (?, ?, NULL, ?)");
		$stmt->bind_param("isi", $rank, $comment, $atom_id );
		$stmt->execute();
		$result = $mysqli->prepare("SELECT LAST_INSERT_ID()");
		$result->execute();
		$result->bind_result($bid);
		$result->fetch();
		$stmt->close();
		$result->close();
		return $bid;
	}
	
	public function get_recent_rankings($minlat, $maxlat, $minlon, $maxlon) {
		global $mysqli;
		
		$arr = array();
		$stmt = $mysqli->prepare("SELECT id, rank, comment, time, atom_id, lng, lat FROM rankings WHERE lng >= '$minlon' AND lng <= '$maxlon' AND lat >= '$minlat' AND lat <= '$maxlat' AND time > CURRENT_TIMESTAMP - 5 LIMIT 20");
		$stmt->execute();
		$stmt->bind_result($rid, $rank, $comment, $time, $atom_id, $lng, $lat);
		while ($stmt->fetch()) {
			$arr[] = array( 'id' => $rid,
							'atom_id' => $atom_id,
							'rank' => $rank,
							'comment' => $comment,
							'time' => $time,
							'lng' => $lng,
							'lat' => $lat
						);
		}
		$stmt->close();
		echo json_encode($arr);
	}
	
	public function get_rankings_for_atom($atom_id) {
		global $mysqli;
		$arr = array();
		$stmt = $mysqli->prepare("SELECT id, rank, comment, time, atom_id, lng, lat FROM rankings WHERE atom_id = ? LIMIT 20");
		$stmt->bind_param("i", $atom_id );
		$stmt->execute();
		$stmt->bind_result($rid, $rank, $comment, $time, $atom_id, $lng, $lat);
		while ($stmt->fetch()) {
			$arr[] = array( 'id' => $rid,
							'atom_id' => $atom_id,
							'rank' => $rank,
							'comment' => $comment,
							'time' => $time,
							'lng' => $lng,
							'lat' => $lat
						);
		}
		$stmt->close();
		echo json_encode($arr);
	}
	
	public function get_atom($atom_id) {
		global $mysqli;
		$stmt2 = $mysqli->prepare("SELECT title, description, category_id, lat, lng FROM atoms WHERE id = ? LIMIT 1");
		$stmt2->bind_param("i", $atom_id);
		$stmt2->execute();
		$stmt->bind_result($title, $desc, $cat, $lat, $lng);
		$stmt2->fetch();
		$arr = array("title" => $title, "desc" => $desc, "cat" => $cat, "lat" => $lat, "lng" => $lng);
		$stmt2->close();
		echo json_encode($arr);
	}
	
	public function get_initial_atoms() {
		global $mysqli;
		
		$arr = array();
		$stmt2 = $mysqli->prepare("SELECT id, title, description, category_id, lat, lng FROM atoms ORDER BY id DESC LIMIT 30");
		$stmt2->execute();
		$stmt2->bind_result($id, $title, $desc, $cat, $lat, $lng);
		while ($stmt2->fetch()) {
			$arr[] = array("id" => $id, "title" => $title, "desc" => $desc, "cat" => $cat, "lat" => $lat, "lng" => $lng);
		}
		
		$stmt2->close();
		echo json_encode($arr);
	}
	
	public function atom_select() {
		global $mysqli;
		
		$s = "";
		$stmt2 = $mysqli->prepare("SELECT id, title FROM atoms ORDER BY title ASC");
		$stmt2->execute();
		$stmt2->bind_result($id, $title);
		while ($stmt2->fetch()) {
			$s .= "<option value='$id'>$title</option>\n";
		}
		
		$stmt2->close();
		echo $s;
	}
}

?>