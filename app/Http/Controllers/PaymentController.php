<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use App\Models\MpesaTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments
     */
    public function index(Request $request)
    {
        $query = Payment::with(['order.customer', 'processedBy'])
            ->whereHas('order', function($q) {
                $q->where('branch_id', Auth::user()->branch_id);
            });

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        $payments = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }

    /**
     * Store a new payment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,mpesa,card,bank_transfer,cheque',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::findOrFail($validated['order_id']);

            // Check if payment exceeds balance
            if ($validated['amount'] > $order->balance_due) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount exceeds balance due'
                ], 422);
            }

            // Create payment record
            $payment = Payment::create([
                'order_id' => $validated['order_id'],
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'reference_number' => $validated['reference_number'],
                'payment_date' => now(),
                'processed_by' => Auth::id(),
                'notes' => $validated['notes'] ?? null,
            ]);

            // Update order payment status
            $newPaidAmount = $order->paid_amount + $validated['amount'];
            $newBalance = $order->total_amount - $newPaidAmount;

            $paymentStatus = $newBalance <= 0 ? 'paid' : 'partially_paid';

            $order->update([
                'paid_amount' => $newPaidAmount,
                'balance_due' => $newBalance,
                'payment_status' => $paymentStatus,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully',
                'data' => $payment->load('order')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to record payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified payment
     */
    public function show(Payment $payment)
    {
        return response()->json([
            'success' => true,
            'data' => $payment->load(['order.customer', 'processedBy', 'mpesaTransaction'])
        ]);
    }

    /**
     * Initiate M-Pesa STK Push
     */
    public function initiateMpesa(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'phone_number' => 'required|string|min:10|max:12',
            'amount' => 'required|numeric|min:1',
        ]);

        $order = Order::findOrFail($validated['order_id']);

        try {
            // Call M-Pesa service (you'll need to implement this)
            $mpesaResponse = app('App\Services\MpesaService')->stkPush(
                $validated['phone_number'],
                $validated['amount'],
                $order->order_number
            );

            // Create pending M-Pesa transaction record
            MpesaTransaction::create([
                'order_id' => $order->id,
                'merchant_request_id' => $mpesaResponse['MerchantRequestID'],
                'checkout_request_id' => $mpesaResponse['CheckoutRequestID'],
                'phone_number' => $validated['phone_number'],
                'amount' => $validated['amount'],
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'M-Pesa request sent. Please check your phone.',
                'data' => $mpesaResponse
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate M-Pesa payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * M-Pesa callback handler
     */
    public function mpesaCallback(Request $request)
    {
        $callbackData = $request->all();

        try {
            $resultCode = $callbackData['Body']['stkCallback']['ResultCode'] ?? null;
            $checkoutRequestId = $callbackData['Body']['stkCallback']['CheckoutRequestID'] ?? null;

            $mpesaTransaction = MpesaTransaction::where('checkout_request_id', $checkoutRequestId)->first();

            if (!$mpesaTransaction) {
                return response()->json(['success' => false, 'message' => 'Transaction not found'], 404);
            }

            if ($resultCode == 0) {
                // Payment successful
                $callbackMetadata = $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'] ?? [];

                $mpesaTransaction->update([
                    'status' => 'completed',
                    'mpesa_receipt_number' => collect($callbackMetadata)->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? null,
                    'transaction_date' => now(),
                    'result_description' => $callbackData['Body']['stkCallback']['ResultDesc'] ?? null,
                ]);

                // Create payment record
                Payment::create([
                    'order_id' => $mpesaTransaction->order_id,
                    'amount' => $mpesaTransaction->amount,
                    'payment_method' => 'mpesa',
                    'reference_number' => $mpesaTransaction->mpesa_receipt_number,
                    'payment_date' => now(),
                    'processed_by' => null, // System processed
                ]);

                // Update order
                $order = Order::find($mpesaTransaction->order_id);
                $newPaidAmount = $order->paid_amount + $mpesaTransaction->amount;
                $newBalance = $order->total_amount - $newPaidAmount;

                $order->update([
                    'paid_amount' => $newPaidAmount,
                    'balance_due' => $newBalance,
                    'payment_status' => $newBalance <= 0 ? 'paid' : 'partially_paid',
                ]);
            } else {
                // Payment failed
                $mpesaTransaction->update([
                    'status' => 'failed',
                    'result_description' => $callbackData['Body']['stkCallback']['ResultDesc'] ?? 'Unknown error',
                ]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('M-Pesa callback error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get payment summary for dashboard
     */
    public function summary()
    {
        $branchId = Auth::user()->branch_id;
        $today = now()->startOfDay();

        $summary = [
            'today' => [
                'cash' => Payment::whereHas('order', fn($q) => $q->where('branch_id', $branchId))
                    ->where('payment_method', 'cash')
                    ->whereDate('payment_date', $today)
                    ->sum('amount'),
                'mpesa' => Payment::whereHas('order', fn($q) => $q->where('branch_id', $branchId))
                    ->where('payment_method', 'mpesa')
                    ->whereDate('payment_date', $today)
                    ->sum('amount'),
                'card' => Payment::whereHas('order', fn($q) => $q->where('branch_id', $branchId))
                    ->where('payment_method', 'card')
                    ->whereDate('payment_date', $today)
                    ->sum('amount'),
                'total' => Payment::whereHas('order', fn($q) => $q->where('branch_id', $branchId))
                    ->whereDate('payment_date', $today)
                    ->sum('amount'),
            ],
            'this_month' => Payment::whereHas('order', fn($q) => $q->where('branch_id', $branchId))
                ->whereMonth('payment_date', now()->month)
                ->sum('amount'),
        ];

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }
}
