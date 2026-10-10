# 🎓 FacultyLens — Neural Feedback System

> Anonymous, structured student-to-faculty feedback for the **Department of Data Science Engineering** — neon-glassmorphism UI, PHP + MySQL backend, role-based access.

[![CI](https://github.com/SyedAynan/Neural-Feedback/actions/workflows/php-lint.yml/badge.svg)](https://github.com/SyedAynan/Neural-Feedback/actions/workflows/php-lint.yml)
![Stack](https://img.shields.io/badge/Stack-PHP%20%2B%20MySQL%20%2B%20Vanilla%20JS-blue)
![Theme](https://img.shields.io/badge/Theme-Neon%20Glassmorphism-blueviolet)
![License](https://img.shields.io/badge/License-MIT-green)
![Status](https://img.shields.io/badge/Status-Complete-brightgreen)

---

## ✨ Features

| Area | What it does |
|---|---|
| 🔐 **Auth** | Login / Signup with role-based access — Student, Faculty, Admin, HoD |
| 📊 **Dashboard** | Live stats, rating charts, feedback trends (`FRONTEND/dashboard.html`) |
| 🧑‍🏫 **Faculty directory** | Individual profile pages with portfolios + feedback forms (`FRONTEND/Faculties/`) |
| ⚖️ **Compare** | Side-by-side faculty comparison tool (`FRONTEND/compare.html`) |
| 👤 **Profiles** | Per-user profile page (`FRONTEND/profile.html`) |
| 🏆 **Leaderboard** | Podium-ranked faculty leaderboard (`FRONTEND/leaderboard.html`) |
| ⭐ **Submit feedback** | Star-rated feedback form + thank-you page (`FRONTEND/essentials/submit-feedback.html`) |
| 📈 **Reports** | Personal feedback history with visual trends (`FRONTEND/essentials/report.html`) |
| 🛠️ **Admin panel** | User management, feedback logs, system controls (`FRONTEND/admin.html`) |
| 🔑 **Password recovery** | Forgot-password flow with OTP UI (`FRONTEND/forgot-password.html`) |
| 📤 **Export** | CSV export + print support |
| 🚫 **Custom 404** | Themed not-found page (`404.html`) |

---

## 🧰 Tech stack

- **Frontend** — Vanilla HTML/CSS/JS, Poppins font, glassmorphism theme (`FRONTEND/theme.css`), CSS/SVG charts
- **Backend** — Plain PHP, session auth, prepared statements (`BACKEND/`, `API/`)
- **Database** — MySQL / MariaDB, 3 tables + seed data (`DATABASE/schema.sql`)
- **Local hosting** — XAMPP / WAMP / MAMP (Apache + PHP + MySQL)

---

## 🗂 Project structure

```
.
├── 404.html                  # Themed not-found page
├── API/                      # JSON endpoints (login, register, logout, feedback, rating)
├── BACKEND/                  # config.php (.env reader), connect.php, auth.php guard
├── DATABASE/
│   └── schema.sql            # users + faculty + feedback tables, 9 faculty + 2 demo users
├── FRONTEND/
│   ├── index.html            # Login        signup.html       dashboard.html
│   ├── compare.html          # Comparison   profile.html      leaderboard.html
│   ├── forgot-password.html  # Recovery     admin.html        theme.css / signup.css
│   ├── Faculties/            # Directory grid + 9 profile pages + thank_u page
│   └── essentials/           # Portal, stats, report, contact, submit-feedback
├── .env.example              # Copy to .env and fill in (never commit .env)
└── .github/workflows/        # CI: PHP syntax lint on every push/PR
```

---

## ⚡ Quick start

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (or WAMP/MAMP) — Apache + PHP 8 + MySQL
- A modern browser

### 1. Deploy to web root

```bash
# XAMPP example:
cp -r Neural-Feedback/ C:/xampp/htdocs/facultylens/
```

### 2. Create the database

Import `DATABASE/schema.sql` (creates `neural_feedback`, tables, 9 faculty, 2 demo users):

```bash
mysql -u root < DATABASE/schema.sql
```

…or via **phpMyAdmin → Import → `DATABASE/schema.sql`**.

### 3. Configure credentials

```bash
cp .env.example .env
# then edit .env if your MySQL user/password differ from root/(empty)
```

`BACKEND/config.php` reads `.env` (`DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`).

### 4. Open in browser

```
http://localhost/facultylens/FRONTEND/index.html
```

### Demo credentials

| Role | Email | Password |
|---|---|---|
| Admin | `admin@facultylens.edu` | `admin123` |
| Student | `student@facultylens.edu` | `student123` |

> The login page also runs in **demo mode** without the PHP backend — any email/password reaches the dashboard for UI preview. Real auth needs Apache + MySQL running.

---

## 🔌 API endpoints

| Method | Endpoint | Purpose |
|---|---|---|
| `POST` | `API/register.php` | Create account (validates role, bcrypt-hashes password) |
| `POST` | `API/login.php` | Verify credentials, start session |
| `POST`/`GET` | `API/logout.php` | Destroy session (JSON for AJAX, redirect for browser) |
| `POST` | `API/feedback.php` | Save rated feedback (teacher, subject, clarity/knowledge/interaction, comment) |
| `GET` | `API/rating.php` | Per-teacher average rating + feedback counts |

All JSON endpoints send CORS headers and answer `OPTIONS` preflights.

---

## 🗄 Database

- **`users`** — id, name, email (unique), bcrypt password, role (`student|faculty|admin|hod`), username, login state, timestamps
- **`faculty`** — id, name, designation, department, subject, email, photo, qualification, experience, expertise
- **`feedback`** — id, user/faculty refs, teacher, subject, clarity/knowledge/interaction (1–10), comment, sentiment, timestamp

Seeded with **9 faculty** and **2 demo users** (password: `password` — demo-only, change immediately).

---

## 🔒 Security

- `password_hash()` / `password_verify()` (bcrypt) — no plaintext passwords
- Prepared statements on auth paths; session guard in `BACKEND/auth.php`
- Role-based access (`student` / `faculty` / `admin` / `hod`)
- `.env` is gitignored — copy `.env.example`, never commit secrets
- Known limits (honest): demo credentials ship in the seed, `API/db.php` is a legacy hardcoded-credential file, CORS is wide-open (`*`), no rate limiting / CSRF tokens. See [SECURITY.md](SECURITY.md). Do not expose this to the public internet as-is.

---

## 🎨 Design system

- **Font**: Poppins (Google Fonts) · **BG**: deep dark `#0a0a0f` with animated purple glows
- **Cards**: glassmorphism — `backdrop-filter: blur(18px)` + subtle borders
- **Accent**: purple gradient `#9b6bff → #6d2cff` · **Charts**: CSS bars + SVG lines

---

## 🤝 Contributing

PRs welcome — see [CONTRIBUTING.md](CONTRIBUTING.md) for branch, commit, and PHP conventions.

## 📝 License

MIT — see [LICENSE](LICENSE). Educational project for the Department of Data Science Engineering; not a production student-information system.
