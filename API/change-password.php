<?php
session_start();
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit;
}

require "../BACKEND/connect.php";

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data["current"]) || empty($data["password"])) {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit;
}

if (strlen($data["password"]) < 6) {
    echo json_encode(["success" => false, "message" => "Password must be at least 6 characters"]);
    exit;
}

$id = intval($_SESSION["user_id"]);

$stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if (!$row = $res->fetch_assoc()) {
    echo json_encode(["success" => false, "message" => "User not found"]);
    exit;
}

if (!password_verify($data["current"], $row["password"])) {
    echo json_encode(["success" => false, "message" => "Current password is incorrect"]);
    exit;
}
$stmt->close();

$newHash = password_hash($data["password"], PASSWORD_DEFAULT);
$upd = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$upd->bind_param("si", $newHash, $id);

if ($upd->execute()) {
    echo json_encode(["success" => true, "message" => "Password updated"]);
} else {
    echo json_encode(["success" => false, "message" => "Password change failed"]);
}

$upd->close();
$conn->close();
