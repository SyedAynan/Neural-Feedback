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

$query = "
    SELECT teacher,
    ROUND(AVG(clarity), 2) AS clarity,
    ROUND(AVG(knowledge), 2) AS knowledge,
    ROUND(AVG(interaction), 2) AS interaction,
    ROUND(AVG((clarity + knowledge + interaction) / 3), 2) AS rating,
    COUNT(*) AS total_feedback
    FROM feedback
    GROUP BY teacher
    ORDER BY rating DESC
";

$result = $conn->query($query);

if (!$result) {
    echo json_encode(["success" => false, "message" => "Failed to load ratings"]);
    $conn->close();
    exit;
}

$labels    = [];
$ratings   = [];
$counts    = [];
$metrics   = [];

while ($row = $result->fetch_assoc()) {
    $labels[]  = $row["teacher"];
    $ratings[] = floatval($row["rating"]);
    $counts[]  = intval($row["total_feedback"]);
    $metrics[$row["teacher"]] = [
        "clarity"     => floatval($row["clarity"]),
        "knowledge"   => floatval($row["knowledge"]),
        "interaction" => floatval($row["interaction"])
    ];
}

echo json_encode([
    "success" => true,
    "labels"  => $labels,
    "ratings" => $ratings,
    "counts"  => $counts,
    "metrics" => $metrics
]);

$conn->close();
