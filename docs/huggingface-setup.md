# HuggingFace Setup for 3D Generation

1. Go to [huggingface.co](https://huggingface.co/) → Settings → Access Tokens.
2. Create a new token with **Read** permission.
3. Add it to your `.env`:

   ```
   HF_API_TOKEN=hf_xxxxxxxxxxxxxxxx
   ```

4. Accept the model/space terms (open in a browser while logged in):
   - https://huggingface.co/Qwen/Qwen2.5-VL-7B-Instruct
   - https://huggingface.co/spaces/JeffreyXiang/TRELLIS

5. Clear config cache: `php artisan config:clear`.

6. Start the queue worker:

   ```bash
   php artisan queue:work --tries=3 --timeout=180
   ```

7. Create a product with 2–3 images in the admin panel. Watch the status card
   transition `queued → processing → ready`.

## Troubleshooting

- **Status stuck on `queued`** — queue worker isn't running.
- **Status = `failed` with 401/403** — token invalid or terms not accepted.
- **Status = `failed` with 503** — the HF Space is cold-starting; retry works
  automatically, but first-run can take a few minutes.
- **TRELLIS API shape changed** — edit
  [app/Services/AI/TrellisService.php](../app/Services/AI/TrellisService.php),
  specifically the `image_to_3d` endpoint name and the `data` array in
  `startGeneration()`.
