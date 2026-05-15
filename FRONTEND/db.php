<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$conn = new mysqli("localhost", "root", "", "neural_feedback");

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}
?>
