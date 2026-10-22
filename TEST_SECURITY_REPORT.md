# StudentHub – Test & Security / Accessibility Report

**Practical 15 – Final Integration, Testing, Documentation**  
**Project:** StudentHub Portal  
**Date:** October 2026  

---

## 1. Functional Test Cases

| # | Module | Test Case | Expected | Result |
|---|--------|-----------|----------|--------|
| 1 | Register | Valid data submission | User + student row created, redirect to login | Pass |
| 2 | Register | Duplicate email | Error message, no insert | Pass |
| 3 | Register | Weak password / mismatch | Client + server validation errors | Pass |
| 4 | Login | Correct credentials | Session created, role-based redirect | Pass |
| 5 | Login | Wrong password | Generic error, no session | Pass |
| 6 | Logout | Click logout | Session destroyed, redirect to login | Pass |
| 7 | Session | Idle > 30 min | Auto logout, message shown | Pass |
| 8 | RBAC | Student opens /admin/ | Redirect to dashboard with access denied | Pass |
| 9 | Student CRUD | Search by name/email/course | Filtered results | Pass |
| 10 | Student CRUD | Add / Edit / Delete | DB updated, flash message | Pass |
| 11 | Event CRUD | Create with poster | File saved under uploads/posters/, DB path stored | Pass |
| 12 | Event CRUD | Invalid file type / >2MB | Error, no upload | Pass |
| 13 | Events page | Search + category filter | Correct filtered list | Pass |
| 14 | API | GET /api/events.php | Valid JSON array | Pass |
| 15 | API | GET /api/events.php?id=1 | Single event or 404 | Pass |
| 16 | API | POST without auth | 401 Unauthorized | Pass |
| 17 | Dashboard | Logged-in student | Stats + profile summary | Pass |
| 18 | Dark mode | Toggle + reload | Preference restored from localStorage | Pass |

---

## 2. Security Checklist

| Item | Implementation | Status |
|------|----------------|--------|
| Password storage | `password_hash()` / `password_verify()` (bcrypt) | Done |
| SQL injection | Prepared statements (MySQLi) everywhere | Done |
| XSS | `htmlspecialchars` via `e()` on all output | Done |
| CSRF (basic) | Session-based forms; advanced token optional | Partial |
| Session fixation | `session_regenerate_id(true)` on login | Done |
| Session timeout | 30 minutes inactivity | Done |
| Cookie flags | HttpOnly, SameSite=Strict | Done |
| File upload | MIME whitelist + size limit + random name | Done |
| Directory listing | uploads/ should be non-executable (see .htaccess) | Done |
| Sensitive files | setup_passwords.php to be deleted after use | Noted |
| Error disclosure | No raw DB errors shown to user | Done |
| Role checks | `require_login()` / `require_admin()` on protected pages | Done |

---

## 3. Accessibility Checklist

| Item | Status |
|------|--------|
| Semantic landmarks (header, main, footer, nav) | Yes |
| Form labels associated with inputs | Yes |
| Alt text / aria-label on icon buttons | Yes |
| Keyboard focus visible | Yes (outline styles) |
| Colour contrast (Bootstrap defaults) | Acceptable |
| Skip-to-content (optional advanced) | Not implemented |
| Responsive / mobile usable | Yes |

---

## 4. Deployment Verification (Local XAMPP)

1. Apache + MySQL started  
2. `studenthub` database imported  
3. Project under `htdocs/StudentHub`  
4. `setup_passwords.php` executed once and deleted  
5. Login as admin & student successful  
6. CRUD operations verified  
7. API returns JSON in browser Network tab  

---

## 5. Known Limitations / Future Work

- CSRF tokens not applied on every form (can be added with a simple token helper).  
- Remember-me token (Practical 10 advanced) not implemented; session-only auth.  
- Soft-delete for students (status flag exists; hard delete currently used).  
- Production deployment would require HTTPS, stronger secrets, and non-root DB user.

---

## 6. Screenshots / Demo Evidence

*(Attach screenshots of: Home, Login success, Register, Dashboard, Admin stats, Event list, Student list, API JSON response, mobile view.)*

Suggested filenames for submission:
- `screenshots/01-home.png`
- `screenshots/02-login.png`
- `screenshots/03-dashboard.png`
- `screenshots/04-admin.png`
- `screenshots/05-events.png`
- `screenshots/06-api-json.png`
- `screenshots/07-mobile.png`

---

**Prepared for Practical 15 evaluation.**  
StudentHub – ITUE203 – CHARUSAT 2026-27
