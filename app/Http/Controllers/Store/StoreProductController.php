<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Jobs\Generate3DModelJob;
use App\Models\Product;
use App\Services\Store3DCreditService;
use Illuminate\Http\JsonResponse;

class StoreProductController extends Controller
{
    public function __construct(private Store3DCreditService $credits) {}

    public function generate3D(Product $product): JsonResponse
    {
        $store = auth()->user()->store;

        abort_if($product->store_id !== $store->id, 403, 'This product does not belong to your store.');

        $image = $product->images()->first();
        abort_if(! $image, 422, 'Please upload at least one product image before generating a 3D model.');

        if ($product->is3DProcessing()) {
            return response()->json([
                'success' => false,
                'message' => '3D generation is already in progress for this product.',
                'status'  => $product->model3d_status,
            ], 422);
        }

        $check = $this->credits->canGenerate($store);

        if (! $check['allowed']) {
            return response()->json([
                'success' => false,
                'reason'  => $check['reason'],
                'message' => $check['message'],
                'balance' => $store->credits_balance,
            ], 422);
        }

        if (! $this->credits->deductCredit($store)) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reserve credit. Please try again.',
            ], 422);
        }

        $product->update([
            'model3d_status'              => 'queued',
            'model3d_requested_by_store'  => $store->id,
            'model3d_requested_at'        => now(),
            'model3d_error'               => null,
        ]);

        Generate3DModelJob::dispatch($product, $store->id)->onQueue('default');

        return response()->json([
            'success' => true,
            'message' => '3D generation started. You can track progress below.',
            'status'  => 'queued',
            'balance' => $store->fresh()->credits_balance,
        ]);
    }

    public function check3DStatus(Product $product): JsonResponse
    {
        $store = auth()->user()->store;

        abort_if($product->store_id !== $store->id, 403);

        return response()->json([
            'status'     => $product->model3d_status,
            'model_url'  => $product->model3d_path ? $product->get3DModelUrl() : null,
            'is_ready'   => $product->is3DReady(),
            'is_pending' => $product->is3DProcessing(),
            'error'      => $product->model3d_error,
            'balance'    => $store->credits_balance,
        ]);
    }
}
