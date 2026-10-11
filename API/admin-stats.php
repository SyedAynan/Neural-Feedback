<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require "../BACKEND/connect.php";

function scalar($conn, $sql) {
    $res = $conn->query($sql);
    if (!$res) return 0;
    $row = $res->fetch_row();
    return intval($row[0] ?? 0);
}

echo json_encode([
    "success"  => true,
    "users"    => scalar($conn, "SELECT COUNT(*) FROM users"),
    "feedback" => scalar($conn, "SELECT COUNT(*) FROM feedback"),
    "faculty"  => scalar($conn, "SELECT COUNT(*) FROM faculty"),
    "online"   => scalar($conn, "SELECT COUNT(*) FROM users WHERE is_logged_in = 1")
]);

$conn->close();
