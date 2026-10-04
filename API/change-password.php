<?php
session_start();
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require "../BACKEND/connect.php";

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$currentPassword = $data["currentPassword"] ?? "";
$newPassword = $data["newPassword"] ?? "";

if ($currentPassword === "" || $newPassword === "") {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    $conn->close();
    exit;
}

if (strlen($newPassword) < 6) {
    echo json_encode(["success" => false, "message" => "New password must be at least 6 characters"]);
    $conn->close();
    exit;
}

$userId = intval($_SESSION["user_id"]);
$stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row || !password_verify($currentPassword, $row["password"])) {
    echo json_encode(["success" => false, "message" => "Current password is incorrect"]);
    $conn->close();
    exit;
}

$newHash = password_hash($newPassword, PASSWORD_DEFAULT);
$updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$updateStmt->bind_param("si", $newHash, $userId);

if ($updateStmt->execute()) {
    echo json_encode(["success" => true, "message" => "Password updated successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update password"]);
}

$updateStmt->close();
$conn->close();
