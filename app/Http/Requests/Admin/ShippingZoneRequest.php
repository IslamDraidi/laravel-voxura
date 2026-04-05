<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ShippingZoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name'               => 'required|string|max:100',
            'countries'          => 'required|array|min:1',
            'countries.*'        => ['required', 'string', 'size:2', 'regex:/^[A-Z]{2}$/'],
            'regions'            => 'nullable|string|max:1000',
            'methods'            => 'nullable|array',
            'methods.*'          => 'exists:shipping_methods,id',
            'rate_overrides'     => 'nullable|array',
            'rate_overrides.*'   => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'countries.required'      => 'At least one country is required.',
            'countries.*.size'        => 'Each country must be a 2-letter ISO code (e.g. US, JO).',
            'countries.*.regex'       => 'Country codes must be uppercase letters (e.g. US, JO).',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('countries')) {
            $this->merge([
                'countries' => array_map('strtoupper', (array) $this->countries),
            ]);
        }
    }
}
