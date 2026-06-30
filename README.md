# Lachman Sons Drycleaners

A premium, animation-rich marketing website for **Lachman Sons Drycleaners** (LS Dry Cleaners) — trusted since 1980, serving South Delhi & Gurgaon. Built with plain **PHP 8 + SQL** (no framework), so it runs anywhere PHP runs.

> Free doorstep pickup & delivery · expert dry cleaning, steam ironing, leather & wedding-wear care.

---

## Highlights

- **Zero-config to run** — ships with a self-creating **SQLite** database (seed data included). Switch to **MySQL** with one env var.
- **High-end front end** — custom cursor, magnetic buttons, scroll-reveal, parallax, 3D tilt cards, animated counters, dual testimonial marquees, animated hero. Fully responsive (mobile / tablet / desktop) and respects `prefers-reduced-motion`.
- **Real 3D icons** from [3dicons.co](https://3dicons.co) (CC0), bundled locally.
- **Lead capture that actually works** — pickup booking + newsletter forms with CSRF protection, honeypot, server-side validation, and **rate limiting**.
- **Notifications** — booking requests email the shop (and confirm the customer) via **SMTP / `mail()` / log**, plus an **instant "Book on WhatsApp"** button that pre-fills a message.
- **Admin dashboard** at `/admin` (HTTP Basic Auth) to view pickup requests & subscribers.
- **SEO + PWA** — JSON-LD `DryCleaningOrLaundry` structured data, Open Graph/Twitter cards, `robots.txt`, `sitemap.xml`, web manifest, custom 404.
- **CI** — GitHub Actions lints across PHP 8.2/8.3/8.4 and smoke-tests every endpoint.

---

## Quick start

Requires PHP 8.2+ with `pdo_sqlite` (bundled in standard PHP).

```bash
git clone https://github.com/imsharmaolama/dryclean.git
cd dryclean
php -S localhost:8000 -t public public/router.php
# open http://localhost:8000
```

> `public/router.php` is only for the dev server (so unknown URLs hit the custom 404). Apache/Nginx don't use it.

The database is created and seeded automatically on the first request — no migration step needed.

Optionally configure environment:

```bash
cp config/.env.example config/.env
# edit config/.env (DB, email, admin password, rate limits)
```

---

## Project structure

```
dryclean/
├── public/                 # web root (point your server here)
│   ├── index.php           # homepage (assembles all sections)
│   ├── 404.php             # custom not-found page
│   ├── admin/index.php     # Basic-Auth dashboard
│   ├── api/
│   │   ├── booking.php      # pickup request endpoint (JSON)
│   │   └── newsletter.php   # newsletter signup endpoint (JSON)
│   ├── assets/{css,js,img}  # styles, animation engine, 3D icons
│   ├── manifest.webmanifest · robots.txt · sitemap.xml · .htaccess
├── src/                    # application code (outside web root)
│   ├── Database.php         # PDO connection (SQLite / MySQL)
│   ├── Mailer.php           # SMTP / mail() / log mailer (no deps)
│   ├── RateLimiter.php      # file-based per-IP limiter
│   ├── env.php · helpers.php · repositories.php · bootstrap.php
├── config/
│   ├── config.php · .env.example
├── database/
│   ├── init.php             # idempotent schema + seed
│   ├── seed_data.php        # all site content & pricing
│   └── schema.mysql.sql     # reference MySQL schema
├── data/                   # runtime (sqlite db, logs) — git-ignored
└── .github/workflows/ci.yml
```

Application code lives **outside** `public/`, so only intended files are web-accessible.

---

## Configuration (`config/.env`)

| Variable | Default | Purpose |
|---|---|---|
| `APP_ENV` | `production` | `local` shows detailed errors |
| `BASE_URL` | _(empty)_ | Absolute site URL (enables canonical/OG URLs) |
| `DB_DRIVER` | `sqlite` | `sqlite` or `mysql` |
| `DB_HOST/PORT/NAME/USER/PASS` | — | MySQL connection (when `mysql`) |
| `MAIL_DRIVER` | `log` | `log`, `mail`, or `smtp` |
| `MAIL_TO` | — | Shop inbox for new pickup requests |
| `SMTP_HOST/PORT/SECURE/USER/PASS` | — | SMTP creds (`secure`: `tls`/`ssl`/`none`) |
| `ADMIN_USER` / `ADMIN_PASS` | `admin` / _change me_ | Dashboard login |
| `ADMIN_PASS_HASH` | — | bcrypt hash (preferred over plain pass) |
| `RATE_LIMIT_MAX` / `RATE_LIMIT_WINDOW` | `8` / `600` | Form submissions per IP per window (sec) |

Real environment variables always override the `.env` file.

### Database

- **SQLite (default):** nothing to do — `data/lsdrycleaners.sqlite` is created and seeded automatically.
- **MySQL:** set `DB_DRIVER=mysql` and the `DB_*` vars. Tables + seed are created on first run; `database/schema.mysql.sql` is provided for reference. To re-seed: `php database/init.php --fresh`.

### Email notifications

Set `MAIL_DRIVER=smtp` and the `SMTP_*` values (e.g. Gmail/Workspace, Zoho, your host's SMTP). On each booking the shop gets a formatted email and the customer gets a confirmation. With `MAIL_DRIVER=log` (default) messages are written to `data/notifications.log` so nothing breaks before SMTP is configured.

### Admin dashboard

Visit `/admin` and log in with `ADMIN_USER` / `ADMIN_PASS`. For production, generate a hash and use `ADMIN_PASS_HASH`:

```bash
php -r "echo password_hash('your-strong-password', PASSWORD_DEFAULT), PHP_EOL;"
```

---

## Deployment

**Shared hosting / cPanel:** upload the project, set the domain's document root to the `public/` folder, copy `.env.example` to `config/.env`, and ensure `data/` is writable (`chmod 775`).

**Apache:** point the vhost `DocumentRoot` at `public/`. The included `.htaccess` adds caching, gzip, security headers and the 404 page.

**Nginx (example):**

```nginx
server {
    listen 80;
    server_name lsdrycleaners.in www.lsdrycleaners.in;
    root /var/www/dryclean/public;
    index index.php;

    location / { try_files $uri $uri/ =404; }
    error_page 404 /404.php;
    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
    location ~ /\.(env|git) { deny all; }
}
```

Set `BASE_URL=https://your-domain` in `config/.env`, and update the domain in `public/sitemap.xml`.

---

## Security notes

- CSRF tokens on all POST endpoints, honeypot field, server-side validation, per-IP rate limiting.
- Security headers via PHP (works on built-in server) and `.htaccess`.
- Secrets live in `config/.env` (git-ignored) and outside the web root.
- Admin is behind HTTP Basic Auth — always serve it over HTTPS in production.

## Credits

- 3D icons: [3dicons.co](https://3dicons.co) (CC0)
- Fonts: Sora, Plus Jakarta Sans, Fraunces (Google Fonts)
