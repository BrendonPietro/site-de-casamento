# Wedding website — Letícia & Brendon

[![Tests](https://github.com/BrendonPietro/site-de-casamento/actions/workflows/tests.yml/badge.svg)](https://github.com/BrendonPietro/site-de-casamento/actions/workflows/tests.yml)

🇧🇷 [Versão em português](README.md)

The complete website for our wedding, built from scratch and actually used by our guests: invitation, event information, RSVP, a gift registry with online payments through Mercado Pago, and an admin panel for the bride and groom to keep track of everything.

> Personal project published as a portfolio piece. The wedding has already happened and the site is offline. The guest data in this repository is **fictional**.

## Features

- **Guest area:** each guest signs in with their own login and sees personalized content.
- **RSVP with delegation:** a guest can confirm attendance for others (e.g. a couple, or parents confirming for their children).
- **Gift registry:** whole gifts or gifts split into shares, 30-day reservations and automatic release of expired reservations (`php artisan release:expired-gifts`).
- **Mercado Pago payments:** checkout, webhook with signature validation, order and share updates, and a confirmation e-mail for the gift.
- **Couple's admin panel (Filament 3):** guests and delegations, gifts (with photo upload), who gave what, and a stats overview. Access restricted to the couple; regular guests are redirected to the site.
- **Event content:** countdown, the couple's timeline, ceremony and reception with maps, dress code, guest guide and interest in the shuttle service.

## Technical highlights

- **Full-page caching with signed-in guests:** Spatie ResponseCache uses a custom profile (`GuestAwareCacheProfile`) that never caches or serves cached pages to signed-in users. This fixed a bug where one guest received another guest's personalized page.
- **Authentication through a dedicated guard (`guest`)**, reused by the Filament panel login.
- **Custom CSS (WPF3):** a utility-class system written in SCSS, no Tailwind, with breakpoint prefixes.
- **Automated tests** for checkout, the Mercado Pago webhook, RSVP and the admin panel.

## Stack

PHP 8.2 · Laravel 10 · Filament 3 · Alpine.js · Vite · SCSS · MySQL · Mercado Pago SDK · Spatie ResponseCache

## Running locally

```bash
composer install
npm install
cp .env.example .env            # set the database, SENHA_NOIVOS and the Mercado Pago test keys
php artisan key:generate
php artisan migrate --seed      # creates the couple, the fictional guests and the gift registry
npm run dev
php artisan serve
```

The seeder reads `database/dados/guests.txt`, `delegacoes_presenca.txt` and `gifts.txt`. Each guest's password is the last 4 digits of their phone number (or their first name if there is no phone). The panel is at `/painel`.

## Tests

```bash
php artisan test
```

## Documentation (in Portuguese)

- [docs/painel.md](docs/painel.md): admin panel
- [docs/mercadopago.md](docs/mercadopago.md): credentials, webhook, test cards and production checklist
- [CLAUDE.md](CLAUDE.md): architecture and deploy on Hostinger

---

Built by [Brendon Pietro](https://github.com/BrendonPietro).
