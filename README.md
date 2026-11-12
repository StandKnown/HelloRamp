# HelloRamp

AI vehicle photography platform — functional replica of [helloramp.ai](https://helloramp.ai) built with Laravel.

Snap showroom car photos on a phone. The platform cleans backgrounds, removes reflections on glossy paint, standardizes angles, and publishes consistent, premium-feeling listings. Includes dealer dashboard, admin review queue, and Stripe subscription billing.

## Stack

- **Laravel 13** (framework)
- **Breeze (Blade + Tailwind)** for auth scaffolding
- **Laravel Cashier (Stripe)** for subscription billing
- **Pest 4** for testing (19 passing tests)
- **MySQL 8** (default; swap `DB_CONNECTION=sqlite` for local)
- **Vite** for assets

## What's included

### Marketing site (public)
- `/` — Home with hero, product tiles, metrics, featured case studies, CTA
- `/studio` · `/social` · `/engage` — product detail pages
- `/solutions` — single lot / dealer groups / online platforms
- `/pricing` — plans driven by the `plans` table
- `/case-studies` and `/case-studies/{slug}` — editable in admin
- `/contact` — lead form with validation

### Authenticated dashboard (`/dashboard`)
- Overview with vehicle count, listing count, processing queue, ready/approved
- Vehicle CRUD (`/vehicles`) scoped to the user's dealership
- Photo upload (multiple files per vehicle, per-angle) — each upload dispatches `ProcessPhotoJob`
- **Revisit** — re-enqueues a photo for processing and bumps its `revision` counter
- Billing (`/billing`) — subscribe to any plan, open the Stripe Billing Portal

### Admin panel (`/admin`, admin role only)
- Dashboard with platform-wide stats
- Users (list / edit role & dealership / delete)
- Case Studies (full CRUD)
- Plans (edit price, Stripe price ID, features, active/featured flags)
- Review queue (HelloTrust-style human QA — approve or reject+reprocess each AI output)

### Data model
- `dealerships`, `vehicles`, `photos` (status: pending → processing → ready → approved + `revision` for re-runs)
- `case_studies`, `plans`
- `users.role` (`admin`/`member`) and `users.dealership_id`
- Cashier tables: `subscriptions`, `subscription_items`

### Photo enhancement seam
The AI side is swappable. `App\Services\PhotoEnhancement\PhotoEnhancer` is the contract. `StubPhotoEnhancer` is the default implementation (no-op, copies the original) — used in dev & tests until a real provider is wired in.

To add a real driver:
1. Implement `PhotoEnhancer` (e.g. `ReplicatePhotoEnhancer`).
2. Register it in `App\Providers\PhotoEnhancementServiceProvider`'s `match ($driver)` block.
3. Set `PHOTO_DRIVER=yourname` in `.env`.

## Setup

```bash
git clone git@github.com:StandKnown/HelloRamp.git
cd HelloRamp

composer install
npm install && npm run build

cp .env.example .env
php artisan key:generate
php artisan storage:link

# Create the DB (adjust credentials for your MySQL)
mysql -uroot -e "CREATE DATABASE helloramp"

php artisan migrate --seed

php artisan serve
```

Seeded accounts:
- Admin: `admin@helloramp.test` / `password`
- Dealer member: `owner@helloramp.test` / `password`

## Running tests

```bash
./vendor/bin/pest
```

19 feature tests cover marketing routes, vehicle CRUD, photo upload + revisit, enhancement job, admin access control, billing, and contact form.

## Stripe (Cashier)

1. Add your Stripe keys to `.env`:
   ```
   STRIPE_KEY=pk_test_...
   STRIPE_SECRET=sk_test_...
   STRIPE_WEBHOOK_SECRET=whsec_...
   ```
2. Create your prices in the Stripe dashboard, then set each `Plan.stripe_price_id` via `/admin/plans`.
3. Subscribing hits `BillingController@subscribe`, which redirects to Stripe Checkout. The Billing Portal is at `/billing/portal`.
4. Point your Stripe webhook at `https://your-host/stripe/webhook` (provided by Cashier). Set `STRIPE_WEBHOOK_SECRET` locally for verification.

## Deployment (Forge)

- Default DB is MySQL — same as a fresh Forge site.
- Run queue worker: `php artisan queue:work --sleep=1 --tries=2` (or Horizon).
- Point Nginx root to `public/`.
- Set `APP_ENV=production`, `APP_DEBUG=false`, and all `STRIPE_*` vars.
- Build frontend: `npm ci && npm run build`.

## Project layout

```
app/
├── Http/Controllers/
│   ├── Marketing/        # Public pages
│   ├── Dashboard/        # Authenticated dealer UI
│   ├── Billing/          # Cashier integration
│   └── Admin/            # Admin panel
├── Jobs/ProcessPhotoJob.php
├── Models/               # Dealership, Vehicle, Photo, CaseStudy, Plan + User (Billable)
└── Services/PhotoEnhancement/
    ├── PhotoEnhancer.php        # interface
    ├── EnhancementResult.php    # DTO
    └── StubPhotoEnhancer.php    # default no-op driver
```

## Known limitations

- `StubPhotoEnhancer` does not actually enhance photos — it copies the original and marks it as "cleaned". A real provider (Replicate, Stability, bespoke model) needs to be wired in before this is production-useful.
- The contact form logs leads but does not persist or notify sales.
- No DMS integrations (vAuto, DealerCenter, etc.) — stubs on the Solutions page only.
- Social (`Instagram`, `TikTok`, etc. publishing) and Engage (AI avatar video) are described in the marketing site only; no implementation.
