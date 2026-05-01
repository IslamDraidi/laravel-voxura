<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessVirtualTryOnJob;
use App\Models\Product;
use App\Models\VirtualTryon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TryOnController extends Controller
{
    public function initiate(Request $request, Product $product): JsonResponse
    {
        if (! config('model3d.tryon.enabled', true)) {
            return response()->json([
                'success' => false,
                'message' => 'Virtual try-on is currently unavailable. Please try again later.',
            ], 503);
        }

        if (! $product->is3DReady()) {
            return response()->json([
                'success' => false,
                'message' => 'This product does not have a 3D model yet.',
            ], 422);
        }

        $maxKb = (int) config('model3d.tryon.max_photo_size_mb', 10) * 1024;

        $validated = $request->validate([
            'photo'         => "required|image|max:{$maxKb}",
            'height_cm'     => 'nullable|integer|between:100,250',
            'photo_consent' => 'nullable|boolean',
            'save_body'     => 'nullable|boolean',
        ]);

        $userId = $request->user()->id;

        // Dedup: an in-flight try-on for the same user+product already exists
        $existing = VirtualTryon::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->whereIn('status', ['pending', 'processing_body', 'processing_fit'])
            ->latest('id')
            ->first();

        if ($existing) {
            return response()->json([
                'success'  => true,
                'tryon_id' => $existing->id,
                'reused'   => true,
            ]);
        }

        $photoStorageDir = trim((string) config('model3d.tryon.photo_storage', 'tryons'), '/');
        $photoPath = $request->file('photo')->store("{$photoStorageDir}/{$userId}", 'public');

        $tryon = VirtualTryon::create([
            'user_id'       => $userId,
            'product_id'    => $product->id,
            'photo_path'    => $photoPath,
            'height_cm'     => $validated['height_cm'] ?? null,
            'photo_consent' => (bool) ($validated['photo_consent'] ?? false),
            'status'        => 'pending',
            'queued_at'     => now(),
        ]);

        ProcessVirtualTryOnJob::dispatch($tryon)->delay(now()->addSeconds(2));

        return response()->json([
            'success'  => true,
            'tryon_id' => $tryon->id,
        ]);
    }

    public function status(Request $request, VirtualTryon $tryon): JsonResponse
    {
        $this->authorizeOwnership($request, $tryon);

        return response()->json([
            'id'                  => $tryon->id,
            'status'              => $tryon->status,
            'body_generated_at'   => $tryon->body_generated_at,
            'result_generated_at' => $tryon->result_generated_at,
            'error_message'       => $tryon->error_message,
            'result_url'          => $tryon->isReady() ? $tryon->getResultUrl() : null,
            'body_url'            => $tryon->isReady() ? $tryon->getBodyUrl() : null,
        ]);
    }

    public function delete(Request $request, VirtualTryon $tryon): JsonResponse
    {
        $this->authorizeOwnership($request, $tryon);

        $disk = Storage::disk('public');
        $user = $request->user();

        if ($tryon->photo_path && ! $tryon->photo_consent) {
            $disk->delete($tryon->photo_path);
        }

        // Only delete the body file if it isn't the user's saved reusable model
        if ($tryon->body_model_path && $tryon->body_model_path !== $user->body_model_path) {
            $disk->delete($tryon->body_model_path);
        }

        if ($tryon->result_model_path) {
            $disk->delete($tryon->result_model_path);
        }

        $tryon->delete();

        return response()->json(['success' => true]);
    }

    public function history(Request $request): View
    {
        $tryons = $request->user()
            ->tryons()
            ->with('product')
            ->latest()
            ->paginate(10);

        return view('profile.tryons', ['tryons' => $tryons]);
    }

    private function authorizeOwnership(Request $request, VirtualTryon $tryon): void
    {
        abort_if($tryon->user_id !== $request->user()->id, 403);
    }
}
