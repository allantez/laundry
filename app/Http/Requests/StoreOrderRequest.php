<?php
// app/Http/Requests/StoreOrderRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'order_type' => 'required|in:pickup,delivery,walk_in',
            'service_type' => 'required|in:regular,express',
            'requested_pickup_date' => 'nullable|date',
            'requested_delivery_date' => 'nullable|date',
            'delivery_address' => 'nullable|string',
            'delivery_contact_name' => 'nullable|string',
            'delivery_contact_phone' => 'nullable|string',
            'customer_notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.service_item_id' => 'nullable|exists:service_items,id',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ];
    }
}
