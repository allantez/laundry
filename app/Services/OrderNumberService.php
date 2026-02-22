<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;

class OrderNumberService
{
    /**
     * Generate a unique order number.
     *
     * Format: {BRANCH_CODE}-{YEAR}-{SEQUENCE}
     * Example: MAIN-2026-00001
     */
    public function generate(Branch $branch): string
    {
        $year = now()->year;
        $branchCode = strtoupper($branch->code);

        return DB::transaction(function () use ($year, $branch, $branchCode) {

            // Get last order for this branch and year
            $lastOrder = Order::where('branch_id', $branch->id)
                ->whereYear('created_at', $year)
                ->whereNotNull('order_number')
                ->orderByDesc('created_at')
                ->lockForUpdate() // Prevent race condition
                ->first();

            $nextSequence = 1;

            if ($lastOrder) {
                // Extract last sequence
                $parts = explode('-', $lastOrder->order_number);
                $lastSequence = (int) end($parts);
                $nextSequence = $lastSequence + 1;
            }

            return sprintf(
                '%s-%s-%05d',
                $branchCode,
                $year,
                $nextSequence
            );
        });
    }
}
