<?php
session_start();
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
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

$userId = intval($_SESSION["user_id"]);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userStmt = $conn->prepare("SELECT id, name, email, role, created_at FROM users WHERE id = ?");
    $userStmt->bind_param("i", $userId);
    $userStmt->execute();
    $userResult = $userStmt->get_result();
    $user = $userResult->fetch_assoc();
    $userStmt->close();

    if (!$user) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "User not found"]);
        exit;
    }

    $statsStmt = $conn->prepare("
        SELECT
            COUNT(*) AS feedback_given,
            COUNT(DISTINCT teacher) AS faculty_rated,
            ROUND(AVG((clarity + knowledge + interaction) / 3), 1) AS avg_score
        FROM feedback
        WHERE user_id = ?
    ");
    $statsStmt->bind_param("i", $userId);
    $statsStmt->execute();
    $statsResult = $statsStmt->get_result();
    $stats = $statsResult->fetch_assoc() ?: [];
    $statsStmt->close();

    echo json_encode([
        "success" => true,
        "user" => [
            "id" => intval($user["id"]),
            "name" => $user["name"],
            "email" => $user["email"],
            "role" => $user["role"],
            "created_at" => $user["created_at"]
        ],
        "stats" => [
            "feedback_given" => intval($stats["feedback_given"] ?? 0),
            "faculty_rated" => intval($stats["faculty_rated"] ?? 0),
            "avg_score" => floatval($stats["avg_score"] ?? 0)
        ]
    ]);
    $conn->close();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $name = trim($data["name"] ?? "");

    if ($name === "") {
        echo json_encode(["success" => false, "message" => "Name is required"]);
        $conn->close();
        exit;
    }

    if (strlen($name) > 120) {
        echo json_encode(["success" => false, "message" => "Name is too long"]);
        $conn->close();
        exit;
    }

    $username = strtolower(preg_replace('/\s+/', '_', $name));

    $updateStmt = $conn->prepare("UPDATE users SET name = ?, username = ? WHERE id = ?");
    $updateStmt->bind_param("ssi", $name, $username, $userId);

    if ($updateStmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Profile updated successfully",
            "name" => $name
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update profile"]);
    }

    $updateStmt->close();
    $conn->close();
    exit;
}

http_response_code(405);
echo json_encode(["success" => false, "message" => "Method not allowed"]);
$conn->close();
