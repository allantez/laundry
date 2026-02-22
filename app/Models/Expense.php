<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Traits\HasUuid;

class Expense extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $fillable = [
        'expense_number',
        'branch_id',
        'expense_category_id',
        'supplier_id',
        'created_by',
        'updated_by',
        'title',
        'description',
        'amount',
        'tax_amount',
        'total_amount',
        'expense_date',
        'due_date',
        'payment_status',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS (IMPORTANT FIX HERE)
    |--------------------------------------------------------------------------
    |
    | Use float instead of decimal to avoid string return type.
    | Laravel decimal cast returns string → breaks number_format + math.
    |
    */

    protected function casts(): array
    {
        return [
            'amount' => 'float',
            'tax_amount' => 'float',
            'total_amount' => 'float',
            'budget_amount' => 'float',
            'budget_variance' => 'float',
            'quantity_purchased' => 'float',
            'unit_cost' => 'float',

            'recurring_count' => 'integer',

            'expense_date' => 'datetime',
            'due_date' => 'datetime',
            'paid_date' => 'datetime',
            'recurring_end_date' => 'datetime',

            'approved_at' => 'datetime',
            'reconciled_at' => 'datetime',

            'is_taxable' => 'boolean',
            'is_recurring' => 'boolean',
            'is_budgeted' => 'boolean',
            'is_inventory_purchase' => 'boolean',
            'is_reconciled' => 'boolean',
            'is_flagged' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (FIXED number_format WARNINGS)
    |--------------------------------------------------------------------------
    */

    public function getFormattedAmountAttribute(): string
    {
        return 'KES ' . number_format((float) $this->amount, 2);
    }

    public function getFormattedTaxAmountAttribute(): string
    {
        return 'KES ' . number_format((float) $this->tax_amount, 2);
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'KES ' . number_format((float) $this->total_amount, 2);
    }

    public function getFormattedVarianceAttribute(): ?string
    {
        if ($this->budget_variance === null) {
            return null;
        }

        $variance = (float) $this->budget_variance;
        $prefix = $variance > 0 ? '+' : '';

        return 'KES ' . $prefix . number_format($variance, 2);
    }

    /*
    |--------------------------------------------------------------------------
    | RECURRING LOGIC (FIXED Carbon + addDay errors)
    |--------------------------------------------------------------------------
    */

    public function createRecurringInstance(): ?self
    {
        if (!$this->is_recurring || !$this->expense_date instanceof Carbon) {
            return null;
        }

        $baseDate = $this->expense_date->copy();

        $nextDate = match ($this->recurring_frequency) {
            'daily' => $baseDate->addDay(),
            'weekly' => $baseDate->addWeek(),
            'monthly' => $baseDate->addMonth(),
            'quarterly' => $baseDate->addMonths(3),
            'yearly' => $baseDate->addYear(),
            default => null,
        };

        if (!$nextDate instanceof Carbon) {
            return null;
        }

        if (
            $this->recurring_end_date instanceof Carbon &&
            $nextDate->greaterThan($this->recurring_end_date)
        ) {
            return null;
        }

        if ($this->recurring_count !== null && $this->recurring_count <= 0) {
            return null;
        }

        $newExpense = $this->replicate();

        $newExpense->expense_number = self::generateExpenseNumber();
        $newExpense->expense_date = $nextDate;
        $newExpense->due_date = $nextDate->copy()->addDays(30);
        $newExpense->payment_status = 'pending';

        if ($this->recurring_count !== null) {
            $newExpense->recurring_count = $this->recurring_count - 1;
        }

        $newExpense->save();

        return $newExpense;
    }

    /*
    |--------------------------------------------------------------------------
    | SUMMARY (FIXED format() + nullable Carbon)
    |--------------------------------------------------------------------------
    */

    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'expense_number' => $this->expense_number,
            'title' => $this->title,
            'category' => $this->category?->name,
            'supplier' => $this->supplier?->name,
            'amount' => $this->formatted_amount,
            'tax' => $this->formatted_tax_amount,
            'total' => $this->formatted_total,
            'date' => $this->expense_date instanceof Carbon
                ? $this->expense_date->format('Y-m-d')
                : null,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | BOOTED (SAFE AUTH + SAFE GLOBAL SCOPE)
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function (self $expense): void {

            if (empty($expense->expense_number)) {
                $expense->expense_number = self::generateExpenseNumber();
            }

            $expense->total_amount =
                (float) $expense->amount +
                (float) $expense->tax_amount;

            if (Auth::check()) {
                $expense->created_by = Auth::id();
            }
        });

        static::updating(function (self $expense): void {

            if ($expense->isDirty(['amount', 'tax_amount'])) {
                $expense->total_amount =
                    (float) $expense->amount +
                    (float) $expense->tax_amount;
            }

            if (Auth::check()) {
                $expense->updated_by = Auth::id();
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Global Branch Scope (NO IDE WARNINGS)
        |--------------------------------------------------------------------------
        */

        static::addGlobalScope('branch', function ($builder) {

            if (app()->runningInConsole() || !Auth::check()) {
                return;
            }

            $user = Auth::user();

            // Avoid IDE error by checking method existence
            if (!is_object($user)) {
                return;
            }

            if (!method_exists($user, 'hasBranchRole') ||
                !method_exists($user, 'branchRoles')) {
                return;
            }

            if ($user->hasBranchRole('Super Admin', null)) {
                return;
            }

            $branchIds = $user->branchRoles()
                ->whereNotNull('branch_id')
                ->pluck('branch_id');

            if ($branchIds->isNotEmpty()) {
                $builder->whereIn('branch_id', $branchIds);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | NUMBER GENERATOR
    |--------------------------------------------------------------------------
    */

    public static function generateExpenseNumber(): string
    {
        $year = now()->year;
        $month = now()->format('m');

        $lastExpense = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->latest('id')
            ->first();

        $newNumber = $lastExpense
            ? str_pad((int) substr($lastExpense->expense_number, -4) + 1, 4, '0', STR_PAD_LEFT)
            : '0001';

        return "EXP-{$year}{$month}-{$newNumber}";
    }
}
