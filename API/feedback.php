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

if (empty($data["teacher"])) {
    echo json_encode(["success" => false, "message" => "Teacher name is required"]);
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO feedback 
    (user_id, teacher, subject, clarity, knowledge, interaction, comment)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

$userId = intval($_SESSION["user_id"]);
$subject = $data["subject"] ?? null;
$clarity = intval($data["clarity"] ?? 0);
$knowledge = intval($data["knowledge"] ?? 0);
$interaction = intval($data["interaction"] ?? 0);
$comment = $data["comment"] ?? "";

$stmt->bind_param(
    "issiiis",
    $userId,
    $data["teacher"],
    $subject,
    $clarity,
    $knowledge,
    $interaction,
    $comment
);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Feedback saved"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to save feedback"]);
}

$stmt->close();
$conn->close();
