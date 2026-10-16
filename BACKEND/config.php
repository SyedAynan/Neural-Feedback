<?php
// Database config — resolves from (in priority order):
//   1. Real environment variables (Render, Railway, Docker, CI)
//   2. .env file at repo root (local dev)
//   3. Built-in defaults (local XAMPP/MySQL)
//
// This file must stay silent on failure — any echo here would corrupt
// the JSON output of every API endpoint that includes it.

// .env fallback (local development only — .env is gitignored)
$env = @parse_ini_file(__DIR__ . "/../.env") ?: [];

// getenv() reads real env vars; fall back to .env, then local defaults
define("DB_HOST", getenv("DB_HOST") ?: ($env["DB_HOST"] ?? "localhost"));
define("DB_PORT", (int)(getenv("DB_PORT") ?: ($env["DB_PORT"] ?? 3306)));
define("DB_USER", getenv("DB_USER") ?: ($env["DB_USER"] ?? "root"));
define("DB_PASS", getenv("DB_PASS") ?: ($env["DB_PASS"] ?? ""));
define("DB_NAME", getenv("DB_NAME") ?: ($env["DB_NAME"] ?? "neural_feedback"));

// Optional: enable MySQL SSL (Aiven/managed MySQL require it off or configured).
// DB_SSL=1 → enables mysqli ssl options in connect.php
define("DB_SSL", getenv("DB_SSL") ?: ($env["DB_SSL"] ?? "0"));
