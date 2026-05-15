# 🎓 FacultyLens — Neural Feedback System

> A premium neon-glassmorphism faculty feedback portal for the **Department of Data Science Engineering**

![Theme](https://img.shields.io/badge/Theme-Neon%20Glassmorphism-blueviolet)
![Stack](https://img.shields.io/badge/Stack-PHP%20%2B%20MySQL%20%2B%20Vanilla%20JS-blue)
![Status](https://img.shields.io/badge/Status-Complete-brightgreen)

---

## 📋 Overview

FacultyLens enables anonymous, structured feedback from students to faculty. It features:

- **Login / Signup** — Role-based auth (Student, Faculty, Admin, HoD)
- **Dashboard** — Real-time stats, rating charts, feedback trends
- **Faculty Directory** — Individual profiles with portfolios & feedback forms
- **Feedback History** — Department-wide analytics with per-faculty breakdown
- **Reports** — Student's personal feedback history with visual trends
- **Admin Panel** — User management, feedback logs, system controls
- **Elite Portal** — Feature showcase, AI assistant UI, contact support

---

## 🗂 Project Structure

```
Neural-Feedback-main/
├── .env                        # Database credentials
├── DATABASE/
│   └── schema.sql              # MySQL schema + seed data
├── API/
│   ├── db.php                  # Standalone DB connection (legacy)
│   ├── login.php               # Login endpoint (password_verify)
│   ├── register.php            # Registration endpoint
│   ├── logout.php              # Logout + session destroy
│   ├── feedback.php            # Submit feedback
│   └── rating.php              # Get faculty ratings
├── BACKEND/
│   ├── config.php              # Reads .env for DB config
│   ├── connect.php             # Central DB connection
│   └── auth.php                # Session auth guard
└── FRONTEND/
    ├── index.html              # Login page
    ├── signup.html             # Registration page
    ├── dashboard.html          # Main dashboard
    ├── dashboard.js            # Dashboard logic
    ├── login.js                # Login logic
    ├── admin.html              # Admin panel
    ├── theme.css               # Global theme styles
    ├── signup.css               # Signup-specific styles
    ├── assets/
    │   └── teacher.png         # Login illustration
    ├── Faculties/
    │   ├── faculties.html      # Faculty directory grid
    │   ├── hod dashboard.html  # HoD profile + feedback
    │   ├── GAGANA_MAM.html     # Faculty profile pages
    │   ├── sri_sir.html
    │   ├── harshita_mam.html
    │   ├── shalini_mam.html
    │   ├── OS_mam.html
    │   ├── Ramya_mam.html
    │   ├── Pallavi_mam.html
    │   ├── seema_mam.html
    │   ├── thank_u.html        # Feedback success page
    │   └── *.png               # Faculty photos
    └── essentials/
        ├── admin.html          # Elite Feedback Portal
        ├── feedback.html       # Feedback statistics
        ├── report.html         # Student feedback history
        └── contact.html        # Support confirmation
```

---

## ⚡ Quick Start

### Prerequisites
- **XAMPP** / **WAMP** / **MAMP** (Apache + PHP + MySQL)
- A modern web browser

### Setup

1. **Clone / extract** into your web server root:
   ```bash
   # For XAMPP:
   cp -r Neural-Feedback-main/ C:/xampp/htdocs/facultylens/
   ```

2. **Create the database** — import the schema:
   ```bash
   mysql -u root < DATABASE/schema.sql
   ```
   Or open **phpMyAdmin** → Import → select `DATABASE/schema.sql`

3. **Configure** `.env` if your MySQL credentials differ:
   ```
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=
   DB_NAME=neural_feedback
   ```

4. **Open in browser**:
   ```
   http://localhost/facultylens/FRONTEND/index.html
   ```

### Demo Credentials
| Role    | Email                     | Password     |
|---------|---------------------------|--------------|
| Admin   | admin@facultylens.edu     | admin123     |
| Student | student@facultylens.edu   | student123   |

> **Note**: The login works in "demo mode" even without the PHP backend — just enter any email/password and you'll be redirected to the dashboard.

---

## 🎨 Design System

- **Font**: Poppins (Google Fonts)
- **Background**: Deep dark `#0a0a0f` with animated purple glow effects
- **Cards**: Glassmorphism — `backdrop-filter: blur(18px)` + subtle borders
- **Accent**: Purple gradient (`#9b6bff → #6d2cff`)
- **Hover**: Lift + glow intensification
- **Charts**: CSS-only bar charts and SVG line charts

---

## 🔒 Security Features

- Password hashing with `password_hash()` / `password_verify()`
- Prepared statements (SQL injection prevention)
- Session-based authentication
- CORS headers for API endpoints
- Role-based access control (student/faculty/admin/hod)

---

## 📝 License

This project is for educational purposes — Department of Data Science Engineering.
