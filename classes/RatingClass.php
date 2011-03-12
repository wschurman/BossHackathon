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
	
	public function addPhone($phone, $carrier, $atom_id) {
		global $mysqli;
		$stmt = $mysqli->prepare("INSERT IGNORE INTO phones (phone, provider, atom_id) VALUES (?, ?, ?)");
		$stmt->bind_param("ssi", $phone, $carrier, $atom_id);
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
		
		$stmt2 = $mysqli->prepare("SELECT lat, lng FROM atoms WHERE id = ? LIMIT 1");
		$stmt2->bind_param("i", $atom_id);
		$stmt2->execute();
		$stmt2->bind_result($lat, $lng);
		$stmt2->fetch();
		$stmt2->close();
		
		
		$stmt = $mysqli->prepare("INSERT IGNORE INTO rankings (rank, comment, time, atom_id, lat, lng) VALUES (?, ?, NULL, ?, ?, ?)");
		$stmt->bind_param("isidd", $rank, $comment, $atom_id, $lat, $lng );
		$stmt->execute();
		$result = $mysqli->prepare("SELECT LAST_INSERT_ID()");
		$result->execute();
		$result->bind_result($bid);
		$result->fetch();
		$stmt->close();
		$result->close();
		
		$stmt3 = $mysqli->prepare("SELECT phone, provider FROM phones WHERE atom_id = ?");
		$stmt3->bind_param("i", $atom_id);
		$stmt3->execute();
		$stmt3->bind_result($phone, $provider);
		while ($stmt3->fetch()) {
			mail($phone."@".$provider, "BO$$ Comment Notification", $comment);
		}
		$stmt3->close();
		
		
		
		$alpha = 0.4;
		$stmt4 = $mysqli->prepare("SELECT avg FROM atoms WHERE id = ?");
		$stmt4->bind_param("i", $atom_id);
		$stmt4->execute();
		$stmt4->bind_result($average);
		$stmt4->fetch();
		if($average == 0) {
			$new_average = $rank;
		} else {
			$new_average = $alpha * $average + (1.0 - $alpha) * $rank;
		}
		$stmt4->close();
		
		
		$stmt5 = $mysqli->prepare("UPDATE atoms SET avg = ? WHERE id = ?");
		$stmt5->bind_param("di", $new_average, $atom_id);
		$stmt5->execute();
		$stmt5->close();

		return $new_average;
	}
	
	public function getAverage($atom_id) {
		global $mysqli;
		$stmt4 = $mysqli->prepare("SELECT avg FROM atoms WHERE id = ?");
		$stmt4->bind_param("i", $atom_id);
		$stmt4->execute();
		$stmt4->bind_result($average);
		$stmt4->fetch();
		$stmt4->close();
		return $average;
	}
	
	public function get_recent_rankings($minlat, $maxlat, $minlon, $maxlon) {
		global $mysqli;
		
		$arr = array();
		$stmt = $mysqli->prepare("SELECT id, rank, comment, time, atom_id, lng, lat FROM rankings WHERE lng >= '$minlon' AND lng <= '$maxlon' AND lat >= '$minlat' AND lat <= '$maxlat' AND time > CURRENT_TIMESTAMP - 4 LIMIT 20");
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
		$stmt2 = $mysqli->prepare("SELECT title, description, category_id, lat, lng, avg FROM atoms WHERE id = ? LIMIT 1");
		$stmt2->bind_param("i", $atom_id);
		$stmt2->execute();
		$stmt->bind_result($title, $desc, $cat, $lat, $lng, $avg);
		$stmt2->fetch();
		$arr = array("title" => $title, "desc" => $desc, "cat" => $cat, "lat" => $lat, "lng" => $lng, "avg" => $avg);
		$stmt2->close();
		echo json_encode($arr);
	}
	
	public function get_initial_atoms() {
		global $mysqli;
		
		$arr = array();
		$stmt2 = $mysqli->prepare("SELECT id, title, description, category_id, lat, lng, avg FROM atoms ORDER BY id DESC LIMIT 100");
		$stmt2->execute();
		$stmt2->bind_result($id, $title, $desc, $cat, $lat, $lng, $avg);
		while ($stmt2->fetch()) {
			$arr[] = array("id" => $id, "title" => $title, "desc" => $desc, "cat" => $cat, "lat" => $lat, "lng" => $lng, "avg" => $avg);
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