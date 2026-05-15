<?php
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

if (
    empty($data["name"]) ||
    empty($data["email"]) ||
    empty($data["password"]) ||
    empty($data["role"])
) {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit;
}

$name     = $conn->real_escape_string($data["name"]);
$email    = $conn->real_escape_string($data["email"]);
$password = password_hash($data["password"], PASSWORD_DEFAULT);
$role     = $conn->real_escape_string($data["role"]);

// Validate role
$validRoles = ["student", "faculty", "admin", "hod"];
if (!in_array($role, $validRoles)) {
    echo json_encode(["success" => false, "message" => "Invalid role"]);
    exit;
}

// Check for duplicate email
$check = $conn->query("SELECT id FROM users WHERE email='$email'");
if ($check->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Email already exists"]);
    exit;
}

// Generate username from name
$username = strtolower(str_replace(" ", "_", $name));

$stmt = $conn->prepare(
    "INSERT INTO users (name, email, password, role, username) VALUES (?, ?, ?, ?, ?)"
);
$stmt->bind_param("sssss", $name, $email, $password, $role, $username);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Account created successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Registration failed"]);
}

$stmt->close();
$conn->close();
