<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TaxRateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name'                => 'required|string|max:100',
            'rate'                => [
                'required', 'numeric', 'min:0',
                $this->type === 'percentage' ? 'max:100' : 'max:999999',
            ],
            'type'                => 'required|in:percentage,fixed,compound',
            'scope'               => 'required|in:product,category,order,shipping',
            'category_id'         => 'nullable|exists:categories,id|required_if:scope,category',
            'country'             => 'nullable|string|size:2',
            'region'              => 'nullable|string|max:100',
            'postal_code_pattern' => 'nullable|string|max:255',
            'channel'             => 'nullable|string|max:50',
            'priority'            => 'integer|min:0',
            'is_inclusive'        => 'boolean',
            'apply_to_shipping'   => 'boolean',
            'valid_from'          => 'nullable|date',
            'valid_to'            => 'nullable|date|after_or_equal:valid_from',
            'name_en'             => 'nullable|string|max:100',
            'name_ar'             => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'rate.max'                     => 'Percentage tax rate cannot exceed 100%.',
            'category_id.required_if'      => 'A category is required when scope is set to "category".',
            'valid_to.after_or_equal'      => 'Valid to date must be on or after the valid from date.',
            'country.size'                 => 'Country must be a 2-letter ISO code (e.g. US, JO).',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('country')) {
            $this->merge(['country' => strtoupper($this->country)]);
        }
    }
}
