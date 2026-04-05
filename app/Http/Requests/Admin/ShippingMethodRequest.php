<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ShippingMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name'               => 'required|string|max:100',
            'type'               => 'required|in:flat,per_unit,weight_based,free,custom',
            'base_rate'          => 'required|numeric|min:0',
            'per_unit_rate'      => 'nullable|numeric|min:0|required_if:type,per_unit',
            'weight_rate'        => 'nullable|numeric|min:0|required_if:type,weight_based',
            'weight_unit'        => 'in:kg,lb',
            'free_above'         => 'nullable|numeric|min:0',
            'min_order_amount'   => 'nullable|numeric|min:0',
            'max_order_amount'   => 'nullable|numeric|min:0|gte:min_order_amount',
            'max_weight'         => 'nullable|numeric|min:0',
            'channel'            => 'nullable|string|max:50',
            'estimated_days_min' => 'nullable|integer|min:0',
            'estimated_days_max' => 'nullable|integer|min:0|gte:estimated_days_min',
            'sort_order'         => 'integer|min:0',
            'name_en'            => 'nullable|string|max:100',
            'name_ar'            => 'nullable|string|max:100',
            'metadata'           => 'nullable|json',
        ];
    }

    public function messages(): array
    {
        return [
            'per_unit_rate.required_if'  => 'Per-unit rate is required for per-unit shipping type.',
            'weight_rate.required_if'    => 'Weight rate is required for weight-based shipping type.',
            'max_order_amount.gte'       => 'Max order amount must be greater than or equal to min order amount.',
            'estimated_days_max.gte'     => 'Max delivery days must be greater than or equal to min delivery days.',
        ];
    }
}
