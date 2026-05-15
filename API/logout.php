<?php
session_start();
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require "../BACKEND/connect.php";

if (isset($_SESSION["user_id"])) {
    $id = intval($_SESSION["user_id"]);
    $stmt = $conn->prepare("UPDATE users SET is_logged_in=0 WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

session_destroy();

// If called via AJAX, return JSON
if (
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'
) {
    echo json_encode(["success" => true, "message" => "Logged out"]);
} else {
    // Browser redirect
    header("Location: ../FRONTEND/index.html");
}
