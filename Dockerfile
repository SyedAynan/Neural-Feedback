# ============================================================
# File: Dockerfile
#
# Purpose:
#     Docker image for FacultyLens (Neural-Feedback) — PHP 8.2 + Apache.
#     Used when the host doesn't offer a native PHP runtime (Render
#     "Docker" language, Railway, Fly.io, VPS, etc.).
#
# Layout:
#     Repo root = Apache docroot (/var/www/html). index.php at root
#     redirects / → /FRONTEND/index.html; .htaccess (honored because
#     the php:apache image sets AllowOverride All) blocks /BACKEND/,
#     /DATABASE/, /.env from the web.
#
# Run locally:
#     docker build -t neural-feedback .
#     docker run -p 8080:80 --env-file .env neural-feedback
# ============================================================

FROM php:8.2-apache

# mysqli is required by BACKEND/connect.php — it's NOT enabled in the
# base image; docker-php-ext-install compiles it in.
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Serve the entire repo root — relative paths like ../API/login.php
# in FRONTEND/*.js resolve to /API/login.php correctly this way.
COPY . /var/www/html/

# Apache runs as www-data — make sure it can read everything.
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
