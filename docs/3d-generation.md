# AI 3D Model Generation

When an admin saves a product with gallery images, a background job asks **Qwen-VL**
to pick the best image, then **TRELLIS** to generate a `.glb` 3D model.

## Running the queue worker

3D generation runs as a queued job. To process jobs in development:

```bash
php artisan queue:work --queue=default --tries=3 --timeout=180
```

For auto-restart on code changes, use:

```bash
php artisan queue:listen
```

## Managing failed jobs

```bash
php artisan queue:failed         # list failures
php artisan queue:retry all      # retry all failed
php artisan queue:flush          # delete all failed
```

## Environment

Required in `.env`:

```
HF_API_TOKEN=hf_xxxxxxxxxxxxxxxx
MODEL3D_GENERATION_ENABLED=true
QUEUE_CONNECTION=database
```

Optional overrides:

```
HF_QWEN3_VL_SPACE=Qwen/Qwen2.5-VL-7B-Instruct
HF_TRELLIS_SPACE=JeffreyXiang/TRELLIS
MODEL3D_MAX_RETRIES=3
MODEL3D_TIMEOUT=120
```

## Admin visibility

- `/admin/products` — small colored dot per product (grey/amber/orange/green/red).
- `/admin/products/{id}/edit` — full status card with auto-polling, re-generate button.

## Notes

- TRELLIS is a Gradio Space; `TrellisService` calls its `/gradio_api/call/image_to_3d`
  endpoint and streams the result. If that Space's API name or schema changes, update
  [app/Services/AI/TrellisService.php](../app/Services/AI/TrellisService.php).
- Product saves never fail because of 3D generation — it's best-effort and async.
- Generated `.glb` files live in `storage/app/public/models/{product_id}/model.glb`
  and are served via the `storage:link` symlink.
