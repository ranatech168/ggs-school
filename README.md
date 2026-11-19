# Global Generation School — Laravel 13 website starter

A working, extensible starter for a multilingual, multi-branch school website and its CMS. It is **not a pixel-perfect copy** of the reference: the supplied Google Sites URL exposes an embedded page whose contents were not available to the source reader. The demo hero image is AI-generated and illustrative; replace it with a properly licensed, school-approved photograph. All school contact details and branch information must be supplied by the school.

## Features

- Public home, branches, news, individual stories, gallery and contact form.
- English, Khmer and Chinese public UI; AJAX language change, no browser reload. Enter all three versions of each content field in the admin; an empty translation falls back to English. Static public interface labels live in `lang/{en,km,zh}/site.php`. Admin UI is English.
- Bootstrap 5 vertical admin navigation, separate Blade files, responsive layout, image drag/drop and preview, validated upload (JPG/PNG/WebP, 5 MB max).
- jQuery AJAX forms, SweetAlert2 delete confirmation, Toastr notifications, Yajra server-side DataTables for the content list, Bootstrap 5 Laravel pagination elsewhere.
- Manual database-backed roles and permissions (no authorization package): manage content, manage users/roles, read messages. Super administrator is protected. **Permissions apply to the entire school, not to a single branch**; there is no branch-level data isolation.
- One consolidated application migration: `database/migrations/0000_00_00_000000_create_dental_ggsschool_tables.php`. Laravel's separate cache/jobs migrations remain. The migration filename is a requested name, not a dental-domain schema.

## Requirements

PHP 8.3–8.5 with Laravel's required extensions (including `pdo_sqlite` for SQLite, or `pdo_mysql` for MySQL), Composer, a web server and internet access for the frontend CDN libraries and fonts. For image validation the server should have PHP Fileinfo; GD is useful for running the included image tests. No Node build is required for this implementation.

## Quick start (SQLite)

```bash
cd ggs-school
composer install
cp .env.example .env
php artisan key:generate
mkdir -p database
touch database/database.sqlite
php artisan migrate
php artisan storage:link
```

Set `ADMIN_NAME`, `ADMIN_EMAIL`, and `ADMIN_PASSWORD` in `.env` (password must be at least 12 characters). Choose your own unique credentials; no password is included. Then seed once:

```bash
php artisan db:seed
php artisan serve
```

Visit `http://127.0.0.1:8000` and use `/login` for staff access. Remove the three `ADMIN_*` values from `.env` after creating the first administrator. The seeder only creates the account if both email and password are supplied, and it will not change an existing user's password. Never commit `.env` or a live database. If you omit credentials on the first seed, add them and run `php artisan db:seed` again.

### MySQL

Create an empty database, then set `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in `.env` before `php artisan migrate`. Use a supported MySQL version and PHP's MySQL PDO driver. SQLite is used by the automated tests; MySQL deployment should be checked in its intended environment.

## Content workflow

1. Sign in and open **Site settings** to update the school name, tagline and contact details.
2. Add branches, their translated names, addresses, descriptions, contact details and photos. Publish when ready.
3. In **Content**, edit the demo hero/about and add programs, news and gallery items; assign branch-specific items as appropriate. Publish each item when ready. The home page uses the first published hero and about item ordered by sort order then newest ID; news/programs/gallery display in that order too.
4. Enter English, Khmer and Chinese content. Text fields are plain text, not HTML. Slugs must be unique ASCII letters, digits, `_` or `-`.
5. Users with **Read contact messages** can view/delete enquiries under **Messages**. No email notification is sent. Deleting a branch with content or messages is blocked until those records are reassigned or removed.
6. An administrator can create users and roles under **Access control**. A user with **Manage users and roles** can grant full access to others. Only trusted administrators should hold this permission. The super-admin role cannot be edited or deleted in the UI; users cannot delete themselves or change their own role.

## Test

```bash
php artisan test
```

The included tests cover migration, public pages, language session, draft visibility, contact validation, permissions, uploads, branch deletion protection and admin page rendering. End-to-end local browser checks additionally exercised Chinese/Khmer switching, admin login, drag-and-drop-style image preview via file selection, branch creation, content publication and DataTables. The included automated suite uses SQLite and is not a substitute for production acceptance, security review or testing your chosen hosting setup.

## Deployment and limitations

- Set `APP_ENV=production`, `APP_DEBUG=false`, correct `APP_URL`, HTTPS, secure session cookies, trusted proxies and secure web-server configuration. Point the web server **only** at `public/`, make `storage` and `bootstrap/cache` writable, and arrange backups, restore tests, log monitoring and updates. See [Laravel deployment](https://laravel.com/docs/13.x/deployment).
- Libraries and fonts load from external CDNs; host/pin them locally if offline operation, a strict Content Security Policy, or supply-chain requirements demand it.
- This starter does not include branch-scoped staff access, student information, admission applications, email notifications, password-reset screens, audit logs, consent workflows, or automatic translation. It does not provide a visual page builder; the listed sections and settings are editable, while layout/navigation labels are in Blade/language files. Add and security-review any additional features before production.
- The supplied page is an inaccessible custom embed, so its exact structure, branding, and copy could not be verified. Supply screenshots, actual logo and content for a faithful implementation.
