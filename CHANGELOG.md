# Changelog

All notable changes to Yapr.

## 2026-07-16

- Fixed SQL injection with `mysqli_real_escape_string()` across all pages
- Added password hashing with `password_hash()` / `password_verify()`
- Added database indexes for performance optimization
- Seeded database with 17 posts and 46 comments for demo
- Removed router.php and package.json; added .htaccess for XAMPP
- Fixed mirror workflow to remove stale files on clean repo

## 2026-07-14

- Stripped AI tooling and process docs; switched Tailwind from CLI to CDN; removed Vercel, Docker, CI, and other deployment artifacts. Cleaned repo for submission.
