# Security Policy

## Supported versions

| Version | Supported |
|---|---|
| `main` (latest) | ✅ |
| Older commits / forks | ❌ Best-effort only |

## Reporting a vulnerability

**Do not open a public issue for security reports.** Instead:

1. Use GitHub's **Private vulnerability reporting** on this repo
   (Security tab → Report a vulnerability), or
2. Contact the maintainer directly via their GitHub profile.

Include: affected file/endpoint, steps to reproduce, and impact. Expect an
acknowledgement within 7 days.

## Known limitations (by design, educational project)

- `DATABASE/schema.sql` ships **demo credentials** (`admin123` / `student123`)
  with a publicly known bcrypt hash. Change or remove them before any shared
  deployment.
- All endpoints use `BACKEND/connect.php` + `.env` for DB access (legacy
  hardcoded-credential connection files were removed).
- API CORS headers allow `*` (local-demo convenience, not internet-safe).
- No rate limiting, CSRF tokens, or password-strength policy yet.
- `.env` (real DB credentials) must never be committed — it is gitignored;
  copy `.env.example` to create it.

Do not expose this app to the public internet without addressing the above.
