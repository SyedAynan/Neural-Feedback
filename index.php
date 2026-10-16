<?php
// Root entry point for hosted deployments (Render).
// The real UI lives under FRONTEND/ — this just routes / to it so
// relative links like ../API/login.php keep working from any page.
header("Location: /FRONTEND/index.html", true, 302);
exit;
