# SafeVoice GBV Reporting System

SafeVoice is a simple web-based Gender-Based Violence reporting and support system. It allows users to register, login, submit GBV reports, upload evidence, and track report status. Administrators can view all reports, search reports, update status, view users, delete inappropriate reports, and view simple statistics.

## Technology Stack

- Frontend: HTML, CSS, JavaScript
- Backend: PHP
- Database: MySQL
- Frameworks: None

## Folder Structure

```text
safevoice-gbv/
├── admin/
│   ├── dashboard.php
│   ├── reports.php
│   └── users.php
├── assets/
│   ├── css/style.css
│   └── js/app.js
├── config/
│   └── db.php
├── includes/
│   ├── auth.php
│   ├── footer.php
│   ├── header.php
│   ├── helpers.php
│   └── sidebar.php
├── uploads/
│   └── evidence/
├── dashboard.php
├── database.sql
├── index.php
├── login.php
├── logout.php
├── profile.php
├── register.php
├── report_new.php
├── report_view.php
└── reports.php
```

## Installation Guide

1. Copy the project folder into your local server directory.
   - XAMPP example: `htdocs/safevoice-gbv`
   - WAMP example: `www/safevoice-gbv`

2. Start Apache and MySQL.

3. Open phpMyAdmin and import `database.sql`.
   - The database name is `safevoice_gbv`.

4. Open `config/db.php` and confirm the database settings:

```php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'safevoice_gbv';
```

5. Visit the system in your browser:

```text
http://localhost/safevoice-gbv/
```

## Sample Login Details

Administrator:

```text
Email: admin@safevoice.test
Password: password
```

User:

```text
Email: asha@example.com
Password: password
```

## Main Features

- User registration and login
- User profile update
- GBV report submission
- Evidence upload for JPG, PNG, and PDF files
- User report list and status tracking
- Admin dashboard with statistics
- Admin report search
- Admin report status update
- Admin user list
- Admin report deletion

## Security Features

- Password hashing using PHP `password_hash`
- Login verification using `password_verify`
- SQL injection protection using prepared statements
- Session-based authentication
- Basic input validation
- Restricted upload file types and file size

## Notes

- Evidence files are saved in `uploads/evidence/`.
- Maximum evidence file size is 5MB.
- Allowed evidence file types are JPG, JPEG, PNG, and PDF.
- This system is intentionally simple and suitable for a university final-year project.
