<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create branches');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20|unique:branches,code',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'business_hours' => 'nullable|array',
            'is_24_hours' => 'boolean',
            'is_active' => 'boolean',
            'is_main_branch' => 'boolean',
            'opened_at' => 'nullable|date',
            'settings' => 'nullable|array',
            'notes' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
        ];
    }
}
