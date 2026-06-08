# ⚽ World Cup 2026 — Spoiler-Free Highlights App

A Laravel application that lets fans watch match highlights without spoilers.
Scores are hidden behind a **"Reveal Score"** button. Commentary language can be filtered. Videos are embedded from YouTube/Dailymotion.

---

## Features

| Feature | Description |
|---|---|
| Match Cards | Teams, date, stage — **no score visible** |
| Spoiler Shield | Score hidden by default, revealed on click |
| Language Filter | Filter highlights by Arabic / English / French / Spanish / Portuguese |
| Status Badges | 🔴 Live / ✅ Finished / 🕐 Upcoming |
| Admin Panel | Password-protected panel to add/edit matches and videos |
| Mobile-First | Fully responsive dark theme |

---

## Tech Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Database**: SQLite (dev) or MySQL/PostgreSQL (prod)
- **Frontend**: Blade templates, vanilla CSS & JS — no build step required
- **Video**: Embedded iframes (YouTube, Dailymotion) — no video hosting

---

## Quick Start

### 1. Clone & install dependencies

```bash
git clone <repo-url> worldcup-laravel
cd worldcup-laravel
composer install
```

### 2. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

```
DB_CONNECTION=sqlite          # or mysql
ADMIN_SECRET=your-secret-key  # protects the admin panel
```

### 3. Run migrations & seed sample data

```bash
touch database/database.sqlite   # for SQLite
php artisan migrate
php artisan db:seed              # adds 5 sample matches
```

### 4. Serve locally

```bash
php artisan serve
```

Visit: http://localhost:8000  
Admin: http://localhost:8000/admin/login

---

## Project Structure

```
worldcup-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── MatchController.php       # Home + match detail
│   │   │   ├── AdminController.php       # Admin CRUD
│   │   │   └── AdminLoginController.php  # Admin auth
│   │   └── Middleware/
│   │       └── AdminAuth.php             # Protects admin routes
│   └── Models/
│       ├── Match.php
│       └── Video.php
├── database/
│   ├── migrations/
│   │   ├── ..._create_matches_table.php
│   │   └── ..._create_videos_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php             # Public layout
│   │   │   └── admin.blade.php           # Admin layout
│   │   ├── matches/
│   │   │   ├── index.blade.php           # Home page
│   │   │   └── show.blade.php            # Match detail
│   │   └── admin/
│   │       ├── login.blade.php
│   │       ├── index.blade.php
│   │       ├── create.blade.php
│   │       ├── edit.blade.php
│   │       └── show.blade.php
│   ├── css/
│   │   ├── app.css                       # Public styles (dark theme)
│   │   └── admin.css                     # Admin styles
│   └── js/
│       └── app.js                        # Vanilla JS
├── public/
│   ├── css/                              # Compiled assets
│   └── js/
└── routes/
    └── web.php                           # All routes
```

---

## Routes

| Method | URL | Description |
|---|---|---|
| GET | `/` | Match list (no scores) |
| GET | `/match/{id}` | Match detail + highlights |
| GET | `/admin/login` | Admin login |
| POST| `/admin/login` | Authenticate |
| GET | `/admin` | Admin dashboard |
| GET | `/admin/matches/create` | New match form |
| POST| `/admin/matches` | Save match |
| GET | `/admin/matches/{id}` | Manage match + videos |
| GET | `/admin/matches/{id}/edit` | Edit match |
| PUT | `/admin/matches/{id}` | Update match |
| DELETE| `/admin/matches/{id}` | Delete match |
| POST| `/admin/matches/{id}/videos` | Add video |
| DELETE| `/admin/matches/{id}/videos/{vid}` | Remove video |

---

## Database Schema

### `matches`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| team1 / team2 | string | Team names |
| team1_flag / team2_flag | string | Flag image URLs |
| score1 / score2 | integer nullable | NULL until match played |
| match_date | datetime | Kickoff time |
| stage | string | "Group Stage", "Final", etc. |
| group_name | string nullable | "Group A" etc. |
| status | enum | upcoming / live / finished |

### `videos`

| Column | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| match_id | foreign key | → matches.id |
| title | string | "Full Highlights (Arabic)" |
| embed_url | string | YouTube/Dailymotion embed URL |
| platform | enum | youtube / dailymotion / twitter / other |
| language | enum | ar / en / fr / es / pt |
| duration_s | integer nullable | Seconds |

---

## Adding Videos

### Convert YouTube URL to Embed URL

```
Normal:  https://www.youtube.com/watch?v=VIDEO_ID
Embed:   https://www.youtube.com/embed/VIDEO_ID
```

The admin form auto-converts when you paste a YouTube or Dailymotion watch URL.

### Sources for Arabic Commentary

Search YouTube for: `ملخص مباراة` + team names  
Channels: beIN Sports عربي, SSC Sports, MBC Sport

---

## Production Deployment

```bash
# 1. Set environment
APP_ENV=production
APP_DEBUG=false
APP_KEY=<generate>
DB_CONNECTION=mysql
# fill DB credentials...

# 2. Optimise
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Web server — point document root to /public
```

---

## Legal

- Embed videos only from official channels (FIFA, beIN Sports official, etc.)
- Never download or re-host footage — embed only
- YouTube embedding is allowed under YouTube's terms via the official `<iframe>` embed player

---

*For fans who sleep through the 2am kickoffs. No spoilers, just football. ⚽*
