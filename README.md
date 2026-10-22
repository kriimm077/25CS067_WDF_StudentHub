# StudentHub – Full-Stack Campus Portal

**Subject:** Web Development Frameworks (ITUE203)  
**Semester:** 3rd, 2026-27 (ODD)  
**University:** CHARUSAT – Faculty of Technology and Engineering  

Final integrated project covering Practicals 1–15: requirement analysis, semantic HTML, responsive UI, JavaScript interactivity, form validation, PHP backend, MySQL, secure auth, CRUD, AJAX/JSON API, admin dashboard, documentation, and local deployment.

---

## Features

| Module | Description |
|--------|-------------|
| Authentication | Register, Login, Logout with `password_hash` / `password_verify` |
| Sessions | Secure session, regenerate ID on login, 30-min timeout |
| RBAC | Student & Admin roles with protected routes |
| Student CRUD | Add / Edit / Delete / Search / Filter (admin) |
| Event CRUD | Full CRUD + poster upload (type & size validation) |
| Admin Dashboard | Stats, dynamic menu, audit log |
| JSON API | `GET/POST /api/events.php` for AJAX-ready consumption |
| UI | Bootstrap 5, dark-mode toggle (localStorage), responsive |

---

## Tech Stack

- **Frontend:** HTML5, CSS3, Bootstrap 5.3, JavaScript (ES6+)
- **Backend:** PHP 8+ (MySQLi prepared statements)
- **Database:** MySQL / MariaDB
- **Tools:** XAMPP, GitHub, VS Code / any editor

---

## Folder Structure

```
StudentHub/
├── admin/                  # Admin-only pages
│   ├── index.php           # Dashboard
│   ├── students.php        # Student list + search
│   ├── student_form.php    # Add/Edit student
│   ├── events.php          # Event list
│   └── event_form.php      # Add/Edit event + upload
├── api/
│   └── events.php          # JSON API endpoints
├── assets/
│   ├── css/style.css
│   └── js/app.js
├── includes/
│   ├── config.php          # DB credentials & constants
│   ├── db.php              # MySQLi connection
│   ├── auth.php            # Login helpers, RBAC, flash
│   ├── header.php
│   └── footer.php
├── sql/
│   └── studenthub.sql      # Schema + seed data
├── uploads/posters/        # Event poster uploads
├── docs/
│   └── TEST_SECURITY_REPORT.md
├── index.php, login.php, register.php, dashboard.php, ...
├── setup_passwords.php     # One-time demo password fix (DELETE after use)
├── README.md
└── .gitignore
```

---

## Local Setup (XAMPP)

1. **Start** Apache + MySQL in XAMPP.
2. **Import database**
   - Open phpMyAdmin → Import → select `sql/studenthub.sql` → Go.
3. **Copy project**
   - Place the whole folder inside `C:\xampp\htdocs\StudentHub` (or your document root).
4. **Configure** (if needed)
   - Edit `includes/config.php` for DB user/password (default XAMPP: root / empty).
5. **Set demo passwords** (one time)
   ```bash
   # Browser: http://localhost/StudentHub/setup_passwords.php
   # or CLI:  php setup_passwords.php
   ```
   Then **delete** `setup_passwords.php`.
6. **Open**
   - http://localhost/StudentHub/

### Demo Accounts

| Role    | Email                      | Password    |
|---------|----------------------------|-------------|
| Admin   | admin@studenthub.local     | Admin@123   |
| Student | student@studenthub.local   | Student@123 |

---

## Testing Checklist (summary)

- [x] Register new user → appears in users + students
- [x] Login / Logout / session timeout
- [x] Unauthorized access to `/admin/` redirects
- [x] Student CRUD (search, edit, delete)
- [x] Event CRUD + poster upload (type/size)
- [x] Admin dashboard stats & audit log
- [x] JSON API returns valid JSON
- [x] Dark mode persists via localStorage
- [x] Responsive on mobile / tablet / desktop

Full checklist: see `docs/TEST_SECURITY_REPORT.md`.

---

## Security Notes

- Passwords stored with `password_hash(PASSWORD_DEFAULT)`
- All DB queries use prepared statements
- XSS mitigated with `htmlspecialchars` (`e()` helper)
- Session cookie: HttpOnly + SameSite=Strict
- Upload: MIME type + size validation, random filename
- Admin routes protected by `require_admin()`

---

## GitHub

Repository: https://github.com/kriimm077/StudentHub-25CS067

```bash
git add .
git commit -m "Practical 15: Final integration, testing, documentation, deployment"
git push origin main
```

---

## Evaluation Mapping (Practical 15)

| Requirement | Status |
|-------------|--------|
| All modules integrated | Yes |
| Navigation & responsiveness | Yes |
| Auth, sessions, RBAC | Yes |
| Database operations end-to-end | Yes |
| Security & accessibility checklist | docs/TEST_SECURITY_REPORT.md |
| Documentation (README + setup) | Yes |
| Clean GitHub commits | Yes |
| Local XAMPP deployment | Yes |

---

**Course Faculty:** Dr. Martin Parmar, Dr. Mrugendra Rahevar, and team (CSPIT / DEPSTAR)
