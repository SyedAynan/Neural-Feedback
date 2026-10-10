# Contributing to FacultyLens

Thanks for contributing! Small, focused PRs beat big rewrites.

## Workflow

1. Fork / branch from `main`: `feat/<short-name>` or `fix/<short-name>`
2. Keep changes scoped — one feature or fix per PR
3. Follow the conventions below, then open a PR against `main`
4. Fill in the PR template (what / why / how tested)

## Conventional commits

```
feat: add CSV export to leaderboard
fix: correct dashboard redirect after logout
docs: clarify XAMPP setup steps
chore: extend .gitignore for composer artifacts
```

Types: `feat`, `fix`, `docs`, `style`, `refactor`, `test`, `chore`.

## PHP conventions (this repo)

- **Prepared statements** for every query with user input (`$conn->prepare` +
  `bind_param`) — never interpolate `$_POST`/JSON into SQL.
- **Passwords**: `password_hash()` on write, `password_verify()` on login.
  Never log, echo, or commit passwords or hashes.
- Session-protected pages must `require ../BACKEND/connect.php` /
  `BACKEND/auth.php` as the existing endpoints do.
- Validate at the boundary: required fields, role allow-list
  (`student|faculty|admin|hod`), rating ranges — return JSON
  `{"success": false, "message": "..."}` on rejection.
- No new secrets in code — DB config comes from `.env` via
  `BACKEND/config.php`. Copy `.env.example` to `.env` locally.

## Frontend conventions

- Match the existing neon-glassmorphism theme (`FRONTEND/theme.css`,
  Poppins, purple gradient) — reuse classes before adding new ones.
- All pages need `<meta viewport>`, `lang="en"`, and a descriptive `<title>`.

## What not to commit

`.env`, database dumps with real data, `vendor/`, IDE configs, OS files —
`.gitignore` already covers these. Check `git status` before pushing.
