# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Wedding website for Letícia e Brendon. Laravel 10 backend with Vite + Alpine.js frontend, MySQL database, and Mercado Pago payment integration.

## Commands

```bash
# Development
npm run dev                              # Vite dev server (watch + HMR)
php artisan serve                        # Laravel at http://localhost:8000

# Build
npm run build                            # Compile assets to public/build/
php artisan config:cache                 # Cache config for production

# Database
php artisan migrate --seed               # Run migrations + seed gift/guest data
php artisan release:expired-gifts        # Release reservations past their deadline

# Testing
php artisan test                         # Run PHPUnit test suite
php artisan test --filter=SomeTest       # Run a single test class

# Deployment
php artisan prepare:deploy               # Pre-deploy preparation
php artisan cache:pages                  # Pre-warm HTTP response cache
```

## Architecture

### Auth: Guest model, not User

The site uses a custom `Guest` model for authentication — the standard Laravel `User` model is unused. The Guest model overrides `getAuthPassword()` to use the `senha` column (not `password`). Middleware aliases: `guest.auth` (requires login), `guest.redirect` (redirect if already logged in).

### Admin panel (Filament)

Painel administrativo em `/painel` (Filament v3), restrito aos **noivos**. Acesso gated por `Guest::isDono()` (tipo ∈ `noivo`/`noiva`) via `canAccessPanel()`. O middleware `EnsurePanelOwner` redireciona convidados comuns pro site (em vez do 403 padrão do Filament) — por isso está no `$middlewarePriority` do Kernel, **antes** do `Authenticate`. Login do painel é por `nome_completo` (custom `App\Filament\Pages\Auth\Login`), reaproveitando o guard `guest`. O link "Painel" no header só aparece pra quem passa no `isDono()`.

Resources em `app/Filament/Resources/`: `GuestResource` (convidados + RelationManager `dependentes` = delegação de presença), `GiftResource` (presentes, upload de foto em `storage/gifts`), `GiftOrderResource` (presenteados, somente leitura). Widget `StatsOverview` no dashboard. O painel já inclui `DoNotCacheResponse` no seu middleware (não precisa do alias por rota). Cor primária e fonte do painel ficam no `AdminPanelProvider`.

**Deploy:** o Filament adiciona dependências em `vendor/` e assets em `public/css|js/{filament,forms,support}` que precisam ir pra produção (FTP do `vendor/` + assets, ou `composer install` no servidor). `composer.json` tem `config.platform.php = 8.2` pra resolver pacotes compatíveis com a Hostinger.

### Response caching

Full-page HTTP caching is applied globally via Spatie ResponseCache. Add the `doNotCacheResponse` middleware alias to any route that serves dynamic/personalized content (e.g. gift inventory). Changing cached routes without clearing cache won't reflect immediately in development.

O cache profile é o `App\Support\GuestAwareCacheProfile`: **nunca cacheia (nem serve cache para) requisições com convidado logado** no guard `guest`. Isso é obrigatório porque o site autentica pelo guard `guest`, mas o profile padrão do Spatie consulta o guard default (`web`) — sem o profile custom, todos os convidados logados compartilhavam a mesma entrada de cache e um recebia a página personalizada do outro (formulário de presença com IDs alheios → confirmações salvas como 'N'). Ao testar comportamento de cache, logue via `POST /guest/login` — `actingAs()` troca o guard default (`shouldUse`) e mascara esse bug.

### Frontend: component-based Blade + WPF3

Pages are assembled entirely from Blade components under `resources/views/components/site/`. The CSS framework is a **custom utility system called WPF3** (not Tailwind) defined in `resources/scss/site/wpf/`. It generates classes with SCSS — utilities like `.dp-flex`, `.center()`, `.obj-pos()`, `mob:` breakpoint prefix, and `.bc()` (border-color). When adding styles, extend WPF3 classes or write component SCSS in `resources/scss/site/components/`.

### Gift system states

`Gift` model has computed states: `isAvailable()`, `isReserved()`, `isSold()`, `isQuotaBased()`. Quota-based gifts track `cotas_vendidas` / `num_cotas`. Single gifts can be reserved with a 30-day auto-release timer. The `release:expired-gifts` artisan command clears stale reservations and should run on a schedule.

### Payment flow (Mercado Pago)

`GiftCheckoutController` initiates the checkout. After payment, Mercado Pago calls the webhook handled by `MercadoPagoWebhookController`, which validates the signature, updates the `GiftOrder` and gift quota/sold status, then sends `GiftPurchasedMail`. Redirect endpoints (`/gift-payment/success`, `/failure`, `/pending`) are separate from the webhook.

### Cloudflare tunnel support

`AppServiceProvider` detects `*.trycloudflare.com` and forces HTTPS + rewrites the root URL. This is needed for local-to-public tunneling during development and webhook testing.

### Key env variables

```env
MERCADO_PAGO_PUBLIC_KEY=
MERCADO_PAGO_ACCESS_TOKEN=
MERCADO_PAGO_WEBHOOK_SECRET=   # Used to validate incoming webhook signatures
MAIL_MAILER=                   # Use 'array' for local testing
```

## Deploy via FTP + SSH (Hostinger)

O `public_html` na Hostinger corresponde à pasta `public/` do Laravel — o `artisan` fica um nível acima.

```bash
# Acesso SSH
ssh -p PORTA USUARIO@IP   # dados em hPanel → Avançado → Acesso SSH

# Raiz do Laravel — está dentro de /laravel, NÃO em public_html
# Estrutura: ~/domains/leticiaebrendon.com/{DO_NOT_UPLOAD_HERE, laravel, public_html}
cd ~/domains/leticiaebrendon.com/laravel

# Após subir arquivos via FTP, limpar o cache de respostas
php artisan responsecache:clear

# Se mudou .env ou config
php artisan config:cache

# Se mudou rotas
php artisan route:cache

# Se mudou views
php artisan view:cache
```

**Fluxo de deploy de assets (FTP):**
1. `npm run build` — compila SCSS/JS para `public/build/` (os hashes mudam a cada build)
2. Sobe via FTP a pasta `public/build/` inteira **para a docroot `public_html/build/`**
3. ⚠️ **Sincronize as DUAS pastas `build/`** (ver abaixo) — senão o site quebra com 404 nos assets
4. SSH → `cd ~/domains/leticiaebrendon.com && php artisan responsecache:clear`

**⚠️ Armadilha: existem duas pastas `build/` no servidor (não é symlink):**
- `public_html/build/` — docroot, de onde o navegador **baixa** os assets.
- `laravel/public/build/` — de onde o `@vite` **lê** o `manifest.json` (porque `public_path()` = `laravel/public`).

Se o deploy atualizar só uma, o `@vite` emite um hash que não existe na docroot → `GET /build/assets/app-XXXX.js 404` → JS não carrega (header transparente, hambúrguer mobile morto, lista de presentes vazia, confirmação de presença sem reagir, `Swiper is not defined`). Diagnóstico e correção:

```bash
cd ~/domains/leticiaebrendon.com
# Diagnóstico: os hashes devem ser iguais
grep -o 'app-[^"]*\.js' laravel/public/build/manifest.json
grep -o 'app-[^"]*\.js' public_html/build/manifest.json
# Correção: sincroniza a docroot (fonte da verdade) → laravel
cp -rf public_html/build/. laravel/public/build/
cd laravel && php artisan responsecache:clear
```

## Documentação

- [docs/painel.md](docs/painel.md) — painel administrativo (Filament): acesso/permissões, recursos, tema, testes, deploy e troubleshooting
- [docs/mercadopago.md](docs/mercadopago.md) — credenciais, configuração de webhook, cartões de teste, fluxo completo e checklist de produção

## Vite entry points

- `resources/scss/site/index.scss` — all styles
- `resources/js/site/app.js` — Alpine.js init, Swiper, FlipDown imports
