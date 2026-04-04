<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_new' => $this->boolean('is_new'),
            'color_swatches' => $this->parseColorSwatches(),
            'size_guide' => $this->parseSizeGuide(),
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'remove_images' => 'sometimes|array',
            'remove_images.*' => 'integer|exists:product_images,id',
            'sale_badge' => 'nullable|string|max:40',
            'is_new' => 'boolean',
            'max_order_quantity' => 'required|integer|min:1|max:99',
            'stock_alert_threshold' => 'required|integer|min:1|max:99',
            'delivery_estimate' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'fit' => 'nullable|string|max:255',
            'care_instructions' => 'nullable|string',
            'sku' => 'nullable|string|max:100',
            'shipping_returns' => 'nullable|string',
            'color_swatches_rows' => 'nullable|string',
            'size_guide_rows' => 'nullable|string',
            'color_swatches' => 'nullable|array|max:12',
            'color_swatches.*.name' => 'required|string|max:50',
            'color_swatches.*.hex' => ['required', 'regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/'],
            'size_guide' => 'nullable|array|max:12',
            'size_guide.*.size' => 'required|string|max:20',
            'size_guide.*.chest' => 'nullable|string|max:50',
            'size_guide.*.waist' => 'nullable|string|max:50',
            'size_guide.*.length' => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'color_swatches.*.hex.regex' => 'Each color swatch must use a valid hex color like #111827.',
        ];
    }

    private function parseColorSwatches(): array
    {
        $rows = preg_split('/\r\n|\r|\n/', (string) $this->input('color_swatches_rows', '')) ?: [];

        return collect($rows)
            ->map(fn (string $row) => trim($row))
            ->filter()
            ->map(function (string $row): array {
                $parts = array_map('trim', explode('|', $row));

                return [
                    'name' => $parts[0] ?? '',
                    'hex' => $parts[1] ?? '',
                ];
            })
            ->values()
            ->all();
    }

    private function parseSizeGuide(): array
    {
        $rows = preg_split('/\r\n|\r|\n/', (string) $this->input('size_guide_rows', '')) ?: [];

        return collect($rows)
            ->map(fn (string $row) => trim($row))
            ->filter()
            ->map(function (string $row): array {
                $parts = array_map('trim', explode('|', $row));

                return [
                    'size' => $parts[0] ?? '',
                    'chest' => $parts[1] ?? '',
                    'waist' => $parts[2] ?? '',
                    'length' => $parts[3] ?? '',
                ];
            })
            ->values()
            ->all();
    }
}
