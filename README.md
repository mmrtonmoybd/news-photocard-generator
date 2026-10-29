# News Photocard Generator

A web app for creating news photocards: branded, share-ready images that combine a news photo, headline, date, logo, and footer text inside a frame. It supports Bangla text and dates, and paid packages through SSLCommerz.

Built with Laravel 11 and Filament 3.

## Features

### Customer panel (`/`)

- Sign up and log in
- **Create photocards:** pick a frame, add a headline with its own colour, and upload a news photo with an optional border colour
- Optional date in English or Bangla (Bangla calendar), with its own colour
- Optional logo and footer text, saved for reuse
- Upload your own **frames**, **logos**, and **footer texts**
- Download cards as PNG, JPG, JPEG, or WEBP
- Shareable link for each card (`/social/share/{hash}`)
- **Packages:** buy a time-based subscription or a pack of download credits, paid through SSLCommerz
- Transaction history

### Admin panel (`/admin`)

- Manage customers and admins
- Create **packages**: credit-based or a number of days, with a price
- View all transactions
- **Settings** for brand name, logo, and SSLCommerz credentials (store ID, password, sandbox mode)
- Roles and permissions (Filament Shield), profile management (Filament Breezy), activity log, and themes

## Tech stack

| Layer    | Packages                                                 |
| -------- | -------------------------------------------------------- |
| Backend  | PHP 8.2+, Laravel 11                                     |
| Admin UI | Filament 3, Filament Shield, Filament Breezy             |
| Images   | Spatie Image, Intervention Image                         |
| Payments | SSLCommerz                                               |
| Fonts    | Noto Sans Bengali, SolaimanLipi (Bangla text on images)  |

## Getting started

### Requirements

- PHP 8.2+ with the GD or Imagick extension
- Composer
- Node.js and npm
- MySQL or another database supported by Laravel

### Installation

```bash
git clone https://github.com/mmrtonmoybd/news-photocard-generator.git
cd news-photocard-generator

composer install
cp .env.example .env
php artisan key:generate
# set the DB_* values in .env
php artisan migrate --seed
php artisan storage:link
npm install && npm run build
```

The seeder creates a super admin, `superadmin@example.com`, with password `superadmin`. Change it right away.

Then sign in at `/admin`:

1. Under **Settings**, set the brand name and logo, and add your SSLCommerz store ID and password. Turn on sandbox mode while testing.
2. Under **Packages**, create at least one package customers can buy.

### Run locally

```bash
php artisan serve
```

The customer panel is at `http://localhost:8000` and the admin panel at `http://localhost:8000/admin`.

## Author

Moshiur Rahman — [rmedha037@gmail.com](mailto:rmedha037@gmail.com)
