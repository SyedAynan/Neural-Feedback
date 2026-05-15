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

require "../BACKEND/connect.php";

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data["email"]) || empty($data["password"])) {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit;
}

$email = $conn->real_escape_string($data["email"]);

$stmt = $conn->prepare("SELECT id, name, username, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
    // Verify hashed password
    if (password_verify($data["password"], $row["password"])) {
        $_SESSION["user_id"]  = $row["id"];
        $_SESSION["username"] = $row["username"] ?: $row["name"];
        $_SESSION["role"]     = $row["role"];

        $conn->query(
            "UPDATE users SET is_logged_in=1, last_login=NOW() WHERE id={$row['id']}"
        );

        echo json_encode([
            "success"  => true,
            "username" => $row["username"] ?: $row["name"],
            "role"     => $row["role"]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid password"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "User not found"]);
}

$stmt->close();
$conn->close();
