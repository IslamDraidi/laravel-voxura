# Virtual Try-On Setup

Voxura's Virtual Try-On feature lets logged-in customers upload a full-body
photo, generate a 3D body mesh via fal.ai's SAM 3D Body endpoint, fit the
product's existing 3D model onto that body, and view the result in the
browser.

## Prerequisites

- PHP / Laravel 11 environment already running (same machine as the queue worker)
- Python 3.10+ available on the server
- An account on [fal.ai](https://fal.ai) with billing enabled
- A working `php artisan storage:link` symlink from `public/storage` to `storage/app/public`

## 1. Install Python dependencies

The garment-fitting step shells out to a Python script powered by `trimesh`.

```bash
pip install -r scripts/requirements_tryon.txt
```

Smoke test the script:

```bash
python3 scripts/fit_garment.py
# {"success": false, "error": "Usage: fit_garment.py <body.glb> <product.glb> <output.glb> <category>"}
```

## 2. Configure environment

Append to `.env`:

```
TRYON_ENABLED=true
TRYON_BODY_PROVIDER=fal
FAL_KEY=your_fal_api_key_here
FAL_SAM3D_ENDPOINT=fal-ai/sam-3/3d-body
PYTHON_PATH=/usr/bin/python3
TRYON_FIT_TIMEOUT=90
TRYON_REQUEST_TIMEOUT=300
```

Get a `FAL_KEY` from <https://fal.ai/dashboard/keys>.

## 3. Run migrations

```bash
php artisan migrate
```

This creates the `virtual_tryons` table and adds reusable body-model columns
to the `users` table.

## 4. Symlink storage (if not already done)

```bash
php artisan storage:link
```

Try-on artifacts live under `storage/app/public/{tryons,bodies,tryon_results}`
and are served from `/storage/...`.

## 5. Run the queue worker

The fitting pipeline runs as a queued job — make sure a worker is running:

```bash
php artisan queue:work --queue=default
```

## 6. Daily cleanup

A scheduled command `tryon:cleanup` runs daily to remove photos that
the customer did not consent to keep, plus failed try-ons older than 7 days.

If you don't already have the Laravel scheduler running, register it as a cron entry:

```
* * * * * cd /path/to/voxura && php artisan schedule:run >> /dev/null 2>&1
```

You can also run the cleanup manually:

```bash
php artisan tryon:cleanup
```

## Troubleshooting

- **"Try-on service is misconfigured"** — `FAL_KEY` is missing or invalid.
- **Job fails with `Model3DTimeoutException`** — `TRYON_REQUEST_TIMEOUT`
  is too low, or fal.ai is overloaded. Increase the value or retry.
- **Python script fails** — confirm `PYTHON_PATH` points to a Python with
  `trimesh` installed for the queue worker's user.
- **Result GLB looks misaligned** — the product 3D model's coordinate frame
  may not match the body. The fitting script auto-detects the longest axis
  as 'up'; bake your product GLBs with a Y-up convention for best results.
