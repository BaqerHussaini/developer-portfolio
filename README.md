# Portfolio — Laravel + PostgreSQL + Admin CMS

Personal portfolio website with a full PHP backend, PostgreSQL database and a browser-based
admin panel. Every piece of content on the public site is stored in the database and can be
edited at `/admin` without touching code.

> The original static HTML/CSS/JS version is kept in `legacy/` for reference.

---

## Requirements

- PHP 8.3 with `pdo_pgsql`, `mbstring`, `xml`, `curl`
- PostgreSQL 14+
- Composer

## Local setup

```bash
composer install

# database + app key
cp .env.example .env          # then edit the DB_* values
php artisan key:generate

php artisan migrate --seed    # creates tables, admin user and starter content
php artisan serve             # http://127.0.0.1:8000
```

Tests use a separate database (`portfolio_test`):

```bash
php artisan test
```

## Admin panel

- URL: `/admin`
- Email: `engineer.baqer.hussaini@gmail.com`
- Password: whatever is set in `ADMIN_PASSWORD` at seed time (see `.env`)

Change it later with:

```bash
php artisan admin:password --email=engineer.baqer.hussaini@gmail.com
```

The panel is session authenticated with CSRF protection and hashed (bcrypt) passwords.

## Database

| Table         | Purpose                                                        |
| ------------- | -------------------------------------------------------------- |
| `users`       | Admin accounts                                                  |
| `settings`    | Every editable text/JSON value shown on the public site         |
| `projects`    | Portfolio projects (title, summary, tags, links, publish flag)  |
| `experiences` | Timeline entries with real start/end dates                      |
| `skills`      | Skill names and proficiency levels                              |
| `stats`       | Hero counters (`4+`, `18`, …)                                   |
| `messages`    | Contact form submissions                                        |

## Structure

```
app/Http/Controllers/Admin/   admin CRUD controllers
app/Http/Middleware/AdminAuth admin route guard
app/Models/                   Setting, Project, Experience, Skill, Stat, Message
app/Support/Portfolio.php     assembles all site content for the front-end
app/Support/SettingsSchema.php defines the editable settings form
resources/views/site/         public pages (home, projects, experience, about, contact)
resources/views/admin/        admin screens
public/assets/css|js          plain CSS/JS — no build step required
database/migrations           PostgreSQL schema
database/seeders              admin account + starter content
```

## Deployment notes

- Point the web server document root at `public/`
- Set `APP_ENV=production`, `APP_DEBUG=false`, and a real `APP_KEY`
- Serve `public/assets` with long cache headers; keep hashed assets out of source control edits
- Back up the PostgreSQL database; all site content lives there
