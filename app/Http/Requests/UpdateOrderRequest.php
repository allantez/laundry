<?php
// app/Http/Requests/UpdateOrderRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled in controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'nullable|exists:customers,id',
            'order_type' => 'sometimes|in:pickup,delivery,walk_in',
            'service_type' => 'sometimes|in:regular,express',
            'requested_pickup_date' => 'nullable|date',
            'requested_delivery_date' => 'nullable|date',
            'delivery_address' => 'nullable|string',
            'delivery_contact_name' => 'nullable|string',
            'delivery_contact_phone' => 'nullable|string',
            'customer_notes' => 'nullable|string',
            'items' => 'sometimes|array|min:1',
            'items.*.service_id' => 'required_with:items|exists:services,id',
            'items.*.service_item_id' => 'nullable|exists:service_items,id',
            'items.*.name' => 'required_with:items|string',
            'items.*.quantity' => 'required_with:items|numeric|min:0.01',
            'items.*.unit_price' => 'required_with:items|numeric|min:0',
        ];
    }
}
