<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: index.html");
  exit;
}
?>

<h1>Welcome, <?php echo htmlspecialchars($_SESSION["username"] ?? "Guest", ENT_QUOTES, "UTF-8"); ?> 👋</h1>
<a href="../API/logout.php">Logout</a>
