<?php
require_once("RatingClass.php");
$controller = new RatingClass();
$data = $controller->atom_select();
echo $data;

?>