# AI Tools Hub — v2.0

Free online tools website (16 tools) built with **PHP 8 + MySQL + vanilla JS**.
Most tools run 100 % in the browser; PHP/MySQL power the shared layout, contact form,
newsletter, usage statistics, feedback votes and the admin panel.

## Folder structure

```
ai_tool_hub/
├── index.php, about.php, contact.php, privacy-policy.php, terms.php, sitemap.php, 404.php
├── sitemap-xml.php, robots.php      → served as /sitemap.xml and /robots.txt
├── .htaccess                        → clean URLs, security headers, caching, blocks private folders
├── .env.example                     → copy to .env and edit
│
├── config/
│   ├── config.php                   → loads .env, defines constants
│   └── database.php                 → PDO connection + db_* helpers (site works even if DB is down)
│
├── includes/
│   ├── bootstrap.php                → include first on every page
│   ├── functions.php                → e(), url(), asset(), CSRF, rate limiter, json_out(), ad_slot()
│   ├── tools-data.php               → ★ single source of truth for all tools (name, SEO text, FAQ…)
│   ├── stats.php                    → usage counters, popular tools
│   ├── header.php  /  footer.php    → site header (navbar) and footer (newsletter, links, cookie bar)
│   ├── tool-start.php / tool-end.php→ shared layout for every tool page (SEO, JSON-LD, FAQ, related)
│   ├── admin-header.php / admin-footer.php
│   └── partials/ navbar.php, breadcrumb.php, tool-card.php
│
├── tools/                           → one small PHP file per tool (only the tool UI)
│   └── image-compressor.php, image-converter.php, image-resizer.php, cgpa-calculator.php,
│       age-calculator.php, emi-calculator.php, bmi-calculator.php, percentage-calculator.php,
│       word-counter.php, case-converter.php, fancy-fonts.php, lorem-ipsum-generator.php,
│       password-generator.php, json-formatter.php, base64-tool.php, unit-converter.php
│
├── api/                             → JSON endpoints (CSRF + rate-limit protected)
│   └── contact.php, newsletter.php, track.php, feedback.php
│
├── admin/                           → login.php, index.php (dashboard), messages.php, subscribers.php, logout.php
│
├── assets/
│   ├── css/ style.css, admin.css
│   └── js/  main.js, image-utils.js, tools/<slug>.js (one script per tool)
│
├── database/ schema.sql, seed.sql   → MySQL tables + initial data
├── scripts/  create-admin.php, generate-seed.php
└── storage/logs/                    → error / app logs (not web-accessible)
```

## Installation (XAMPP / cPanel / VPS)

1. **Copy** the `ai_tool_hub` folder to `htdocs` (XAMPP) or `public_html` (cPanel).
2. **Create the database** — phpMyAdmin → Import `database/schema.sql`, then `database/seed.sql`
   (or: `mysql -u root -p < database/schema.sql && mysql -u root -p ai_tool_hub < database/seed.sql`).
3. **Configure**: copy `.env.example` to `.env` and set `DB_USER`, `DB_PASS`, `APP_SECRET`
   (and `APP_URL` on production). On XAMPP in a sub-folder, set `CLEAN_URLS=false` if mod_rewrite is off.
4. **Create your admin user**: `php scripts/create-admin.php yourname` (password ≥ 10 chars).
   Then open `/admin/login`.
5. Optional: add your AdSense client/slot IDs and GA4 id in `.env`. Without them, neutral placeholders are shown
   (set `SHOW_AD_PLACEHOLDERS=false` to hide them completely).

Requirements: PHP 8.1+ (PDO MySQL, mbstring), MySQL 5.7+/MariaDB 10.3+, Apache with mod_rewrite.

## Adding a new tool

1. Add an entry to `includes/tools-data.php` (name, icon, category, short text, SEO intro, steps, FAQ).
2. Create `tools/<slug>.php` (copy any existing tool page — only the tool UI goes inside).
3. Create `assets/js/tools/<slug>.js` (use `ZH.copy()`, `ZH.toast()`, `ZH.used()` helpers).
4. Run `php scripts/generate-seed.php` and re-import `database/seed.sql` (optional — rows are also created automatically on first use).
The home page, search, footer, sitemap, JSON-LD and related-tools sections update automatically.

## Features

* Shared header / footer / navbar, dark mode (remembers choice, respects system preference)
* Live search (press `/`), category filters, ★ favorites and "recently used" (stored in the browser)
* Popular tools (from real usage data), "New" badges, breadcrumbs, related tools
* Per-tool SEO: meta tags, canonical URL, Open Graph, JSON-LD (WebApplication, FAQPage, Breadcrumb), dynamic sitemap.xml + robots.txt
* Contact form → MySQL (+ optional e-mail), newsletter with CSV export, 👍/👎 feedback per tool
* Security: prepared statements, CSRF tokens, honeypot fields, IP-hash rate limiting, hardened session cookies,
  bcrypt admin passwords, `.htaccess` blocking of `config/`, `includes/`, `database/`, `storage/`
* Admin dashboard: totals, 14-day chart, top tools, messages inbox, subscribers
* Cookie notice, Privacy Policy and Terms updated to match what the site really stores

## Database tables

`categories`, `tools`, `tool_usage_daily`, `tool_feedback`, `contact_messages`,
`newsletter_subscribers`, `admin_users`, `rate_limits` + view `v_tool_stats`.

## Notes

* The cookie notice is a simple informational banner. If you target EU/UK visitors with personalised ads you
  need a Google-certified consent management platform (CMP) in addition.
* Nothing from the tools (images, text) is uploaded; only anonymous counters and form submissions reach the server.

License: MIT — © 2026 Muhammad Zahid
