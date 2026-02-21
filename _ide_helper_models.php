<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $mobile
 * @property string|null $address_line1
 * @property string|null $address_line2
 * @property string|null $city
 * @property string|null $state
 * @property string|null $postal_code
 * @property string $country
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property array<array-key, mixed>|null $business_hours
 * @property bool $is_24_hours
 * @property bool $is_active
 * @property bool $is_main_branch
 * @property \Illuminate\Support\Carbon|null $opened_at
 * @property \Illuminate\Support\Carbon|null $closed_at
 * @property array<array-key, mixed>|null $settings
 * @property string|null $notes
 * @property string|null $manager_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Customer> $customers
 * @property-read int|null $customers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Expense> $expenses
 * @property-read int|null $expenses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerFeedback> $feedback
 * @property-read int|null $feedback_count
 * @property-read float|null $average_rating
 * @property-read string $formatted_phone
 * @property-read string $full_address
 * @property-read bool $is_open
 * @property-read array $rating_distribution
 * @property-read array $status
 * @property-read string|null $todays_hours
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InventoryItem> $inventoryItems
 * @property-read int|null $inventory_items_count
 * @property-read \App\Models\User|null $manager
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PettyCash> $pettyCashes
 * @property-read int|null $petty_cashes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserBranchRole> $userBranchRoles
 * @property-read int|null $user_branch_roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $usersWithRoles
 * @property-read int|null $users_with_roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch byCode(string $code)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch inCity(string $city)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch mainBranch()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereBusinessHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereIs24Hours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereIsMainBranch($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereManagerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereOpenedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch withoutTrashed()
 */
	class Branch extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $branch_id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $mobile
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @property string|null $gender
 * @property string $customer_type
 * @property bool $is_active
 * @property bool $is_verified
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property string|null $verified_by
 * @property int $loyalty_points
 * @property int $total_orders
 * @property float $total_spent
 * @property \Illuminate\Support\Carbon|null $last_order_date
 * @property \Illuminate\Support\Carbon|null $customer_since
 * @property string|null $address_line1
 * @property string|null $address_line2
 * @property string|null $city
 * @property string|null $state
 * @property string|null $postal_code
 * @property string $country
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $delivery_instructions
 * @property string|null $id_type
 * @property string|null $id_number
 * @property string|null $tax_number
 * @property array<array-key, mixed>|null $preferences
 * @property array<array-key, mixed>|null $tags
 * @property string|null $notes
 * @property string|null $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerFeedback> $feedback
 * @property-read int|null $feedback_count
 * @property-read int|null $age
 * @property-read float|null $average_rating
 * @property-read string $full_address
 * @property-read string $full_name
 * @property-read array $loyalty_tier
 * @property-read string|null $primary_phone
 * @property-read int $total_feedback
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\User|null $verifiedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer corporate()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer forBranch(int $branchId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer search(string $search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer verified()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer vip()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCustomerSince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCustomerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereDeliveryInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereIdType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLastOrderDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLoyaltyPoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePreferences($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereTaxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereTotalOrders($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereTotalSpent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereVerifiedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer withoutTrashed()
 */
	class Customer extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $feedback_number
 * @property string $customer_id
 * @property string|null $order_id
 * @property string $branch_id
 * @property string|null $service_id
 * @property string|null $service_item_id
 * @property string|null $staff_id
 * @property int $rating
 * @property float|null $rating_score
 * @property int|null $quality_rating
 * @property int|null $timeliness_rating
 * @property int|null $staff_rating
 * @property int|null $value_rating
 * @property int|null $cleanliness_rating
 * @property int|null $communication_rating
 * @property string|null $comment
 * @property string|null $positive_feedback
 * @property string|null $negative_feedback
 * @property string|null $suggestions
 * @property array<array-key, mixed>|null $categories
 * @property array<array-key, mixed>|null $tags
 * @property string|null $staff_response
 * @property string|null $responded_by
 * @property \Illuminate\Support\Carbon|null $responded_at
 * @property bool $is_resolved
 * @property \Illuminate\Support\Carbon|null $resolved_at
 * @property string|null $resolved_by
 * @property string|null $resolution_notes
 * @property string $status
 * @property bool $is_public
 * @property bool $is_featured
 * @property bool $is_anonymous
 * @property bool $is_verified
 * @property string|null $verified_by
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property bool $is_flagged
 * @property string|null $flag_reason
 * @property string|null $flagged_by
 * @property string $source
 * @property string|null $source_reference
 * @property bool $needs_followup
 * @property \Illuminate\Support\Carbon|null $followup_date
 * @property string|null $followup_notes
 * @property int|null $satisfaction_score
 * @property array<array-key, mixed>|null $metadata
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Customer|null $customer
 * @property-read float|null $average_detailed_rating
 * @property-read string $customer_name
 * @property-read string $rating_stars
 * @property-read string|null $time_ago
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\User|null $resolvedBy
 * @property-read \App\Models\User|null $respondedBy
 * @property-read \App\Models\Service|null $service
 * @property-read \App\Models\ServiceItem|null $serviceItem
 * @property-read \App\Models\User|null $staff
 * @property-read \App\Models\User|null $verifiedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereCategories($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereCleanlinessRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereCommunicationRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereFeedbackNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereFlagReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereFlaggedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereFollowupDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereFollowupNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereIsAnonymous($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereIsFlagged($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereIsResolved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereNeedsFollowup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereNegativeFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback wherePositiveFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereQualityRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereRatingScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereResolutionNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereResolvedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereResolvedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereRespondedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereRespondedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereSatisfactionScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereServiceItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereSourceReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereStaffRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereStaffResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereSuggestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereTimelinessRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereValueRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback whereVerifiedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFeedback withoutTrashed()
 */
	class CustomerFeedback extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $expense_number
 * @property string|null $receipt_number
 * @property string|null $invoice_number
 * @property string $branch_id
 * @property string $expense_category_id
 * @property string|null $supplier_id
 * @property string $created_by
 * @property string|null $updated_by
 * @property string|null $approved_by
 * @property string $title
 * @property string|null $description
 * @property float $amount
 * @property float $tax_amount
 * @property float $total_amount
 * @property \Illuminate\Support\Carbon $expense_date
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property \Illuminate\Support\Carbon|null $paid_date
 * @property string $payment_method
 * @property string|null $payment_reference
 * @property string $payment_status
 * @property bool $is_taxable
 * @property numeric|null $tax_rate
 * @property string|null $tax_code
 * @property bool $is_recurring
 * @property string|null $recurring_frequency
 * @property \Illuminate\Support\Carbon|null $recurring_end_date
 * @property int|null $recurring_count
 * @property bool $is_budgeted
 * @property float|null $budget_amount
 * @property float|null $budget_variance
 * @property int $requires_approval
 * @property string $approval_status
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property string|null $rejection_reason
 * @property string|null $receipt_path
 * @property string|null $invoice_path
 * @property string|null $attachments
 * @property bool $is_inventory_purchase
 * @property string|null $inventory_item_id
 * @property float|null $quantity_purchased
 * @property float|null $unit_cost
 * @property string|null $utility_type
 * @property string|null $meter_number
 * @property numeric|null $units_consumed
 * @property string|null $staff_id
 * @property string|null $expense_type
 * @property bool $is_reconciled
 * @property \Illuminate\Support\Carbon|null $reconciled_at
 * @property string|null $reconciled_by
 * @property string|null $notes
 * @property string|null $internal_notes
 * @property string|null $metadata
 * @property string|null $tags
 * @property bool $is_flagged
 * @property string|null $flag_reason
 * @property string|null $flagged_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\ExpenseCategory|null $category
 * @property-read \App\Models\User|null $createdBy
 * @property-read string $formatted_amount
 * @property-read string $formatted_tax_amount
 * @property-read string $formatted_total
 * @property-read string|null $formatted_variance
 * @property-read \App\Models\Supplier|null $supplier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereApprovalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereAttachments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereBudgetAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereBudgetVariance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereExpenseCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereExpenseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereExpenseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereExpenseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereFlagReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereFlaggedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereInternalNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereInventoryItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereInvoicePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereIsBudgeted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereIsFlagged($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereIsInventoryPurchase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereIsReconciled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereIsRecurring($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereIsTaxable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereMeterNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense wherePaidDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense wherePaymentReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereQuantityPurchased($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereReceiptNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereReceiptPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereReconciledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereReconciledBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereRecurringCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereRecurringEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereRecurringFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereRequiresApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereTaxCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereUnitCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereUnitsConsumed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereUtilityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense withoutTrashed()
 */
	class Expense extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $branch_id
 * @property string $name
 * @property string $slug
 * @property string $code
 * @property string|null $description
 * @property string|null $parent_id
 * @property string|null $path
 * @property string $type
 * @property bool $is_taxable
 * @property numeric|null $tax_rate
 * @property string|null $tax_code
 * @property numeric|null $monthly_budget
 * @property numeric|null $yearly_budget
 * @property bool $track_budget
 * @property bool $alert_on_exceed
 * @property numeric $budget_alert_threshold
 * @property string|null $account_code
 * @property string|null $account_type
 * @property bool $requires_approval
 * @property numeric|null $approval_threshold
 * @property string|null $approver_id
 * @property string|null $color
 * @property string|null $icon
 * @property int $sort_order
 * @property bool $is_active
 * @property bool $is_system
 * @property array<array-key, mixed>|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $approver
 * @property-read \App\Models\Branch|null $branch
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ExpenseCategory> $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Expense> $expenses
 * @property-read int $expenses_count
 * @property-read string $full_name
 * @property-read bool $has_children
 * @property-read string $indented_name
 * @property-read int $level
 * @property-read array|null $monthly_utilization
 * @property-read array $status
 * @property-read float $total_expenses
 * @property-read string $type_color
 * @property-read string $type_icon
 * @property-read string $type_label
 * @property-read array|null $yearly_utilization
 * @property-read ExpenseCategory|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory forBranch($branchId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory nonSystem()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory ofType(string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory requiresApproval()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory root()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory search(string $search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory system()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory taxable()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereAccountCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereAccountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereAlertOnExceed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereApprovalThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereApproverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereBudgetAlertThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereIsSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereIsTaxable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereMonthlyBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereRequiresApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereTaxCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereTrackBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereYearlyBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory withBudget()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory withoutTrashed()
 */
	class ExpenseCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $inventory_item_id
 * @property string|null $branch_id
 * @property string $type
 * @property string $severity
 * @property string $title
 * @property string $message
 * @property array<array-key, mixed>|null $details
 * @property numeric|null $threshold_value
 * @property numeric|null $current_value
 * @property numeric|null $difference
 * @property \Illuminate\Support\Carbon|null $expiry_date
 * @property int|null $days_until_expiry
 * @property numeric|null $current_stock
 * @property numeric|null $minimum_stock
 * @property numeric|null $reorder_point
 * @property numeric|null $recorded_temperature
 * @property numeric|null $min_temperature
 * @property numeric|null $max_temperature
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $acknowledged_at
 * @property string|null $acknowledged_by
 * @property \Illuminate\Support\Carbon|null $resolved_at
 * @property string|null $resolved_by
 * @property string|null $resolution_notes
 * @property string|null $resolution_action
 * @property \Illuminate\Support\Carbon|null $dismissed_at
 * @property string|null $dismissed_by
 * @property string|null $dismissal_reason
 * @property array<array-key, mixed>|null $notifications_sent
 * @property \Illuminate\Support\Carbon|null $last_notified_at
 * @property int $notification_count
 * @property string|null $assigned_to
 * @property \Illuminate\Support\Carbon|null $assigned_at
 * @property bool $is_escalated
 * @property \Illuminate\Support\Carbon|null $escalated_at
 * @property string|null $escalated_to
 * @property string|null $comments
 * @property array<array-key, mixed>|null $comment_history
 * @property array<array-key, mixed>|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $acknowledgedBy
 * @property-read \App\Models\User|null $assignedTo
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\User|null $dismissedBy
 * @property-read \App\Models\User|null $escalatedTo
 * @property-read int $age_in_hours
 * @property-read bool $is_overdue
 * @property-read array $severity_info
 * @property-read array $status_info
 * @property-read string $threshold_display
 * @property-read string $time_ago
 * @property-read array $type_info
 * @property-read \App\Models\InventoryItem|null $inventoryItem
 * @property-read \App\Models\User|null $resolvedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert assignedTo($userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert createdBetween($startDate, $endDate)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert critical()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert escalated()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert expiry()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert forBranch($branchId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert forItem($itemId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert lowStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert ofType(string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert resolved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert unresolved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereAcknowledgedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereAcknowledgedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereAssignedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereAssignedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereCommentHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereCurrentStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereCurrentValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereDaysUntilExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereDifference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereDismissalReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereDismissedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereDismissedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereEscalatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereEscalatedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereInventoryItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereIsEscalated($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereLastNotifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereMaxTemperature($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereMinTemperature($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereMinimumStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereNotificationCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereNotificationsSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereRecordedTemperature($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereReorderPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereResolutionAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereResolutionNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereResolvedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereResolvedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereSeverity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereThresholdValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert withSeverity(string $severity)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryAlert withoutTrashed()
 */
	class InventoryAlert extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $branch_id
 * @property string|null $supplier_id
 * @property string $name
 * @property string $sku
 * @property string|null $barcode
 * @property string $category
 * @property string|null $sub_category
 * @property string|null $description
 * @property string|null $brand
 * @property string|null $model
 * @property string $unit_type
 * @property numeric|null $unit_size
 * @property string|null $unit_size_type
 * @property numeric $current_stock
 * @property numeric $minimum_stock
 * @property numeric|null $maximum_stock
 * @property numeric $reorder_point
 * @property numeric|null $reorder_quantity
 * @property numeric $unit_cost
 * @property numeric $average_cost
 * @property numeric|null $last_cost
 * @property numeric|null $selling_price
 * @property numeric|null $markup_percentage
 * @property string|null $location
 * @property string|null $aisle
 * @property string|null $rack
 * @property string|null $bin
 * @property bool $track_expiry
 * @property \Illuminate\Support\Carbon|null $expiry_date
 * @property int|null $shelf_life_days
 * @property \Illuminate\Support\Carbon|null $last_expiry_check
 * @property bool $track_batches
 * @property string|null $batch_number
 * @property string|null $lot_number
 * @property \Illuminate\Support\Carbon|null $manufacturing_date
 * @property numeric $total_value
 * @property bool $is_active
 * @property bool $is_taxable
 * @property numeric|null $tax_rate
 * @property bool $alert_on_low_stock
 * @property bool $alert_on_expiry
 * @property int $alert_before_days
 * @property string|null $image
 * @property array<array-key, mixed>|null $images
 * @property array<array-key, mixed>|null $documents
 * @property array<array-key, mixed>|null $specifications
 * @property array<array-key, mixed>|null $ingredients
 * @property array<array-key, mixed>|null $safety_info
 * @property numeric $total_quantity_used
 * @property numeric $total_quantity_purchased
 * @property numeric $total_quantity_wasted
 * @property \Illuminate\Support\Carbon|null $last_used_at
 * @property \Illuminate\Support\Carbon|null $last_purchased_at
 * @property \Illuminate\Support\Carbon|null $last_counted_at
 * @property string|null $notes
 * @property array<array-key, mixed>|null $metadata
 * @property array<array-key, mixed>|null $tags
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read string $display_name
 * @property-read array|null $expiry_status
 * @property-read string $formatted_total_value
 * @property-read string $formatted_unit_cost
 * @property-read string $full_location
 * @property-read bool $needs_reorder
 * @property-read array $stock_status
 * @property-read float $suggested_reorder_quantity
 * @property-read string $unit_display
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ServiceItem> $serviceItems
 * @property-read int|null $service_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InventoryAlert> $stockAlerts
 * @property-read int|null $stock_alerts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InventoryStockMovement> $stockMovements
 * @property-read int|null $stock_movements_count
 * @property-read \App\Models\Supplier|null $supplier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem expired()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem expiringSoon(int $days = 30)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem forBranch($branchId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem fromSupplier($supplierId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem inCategory(string $category)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem lowStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem needsReorder()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem orderByStockLevel(string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem outOfStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem search(string $search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereAisle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereAlertBeforeDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereAlertOnExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereAlertOnLowStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereAverageCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereBatchNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereBin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereCurrentStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereDocuments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereIngredients($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereIsTaxable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereLastCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereLastCountedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereLastExpiryCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereLastPurchasedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereLotNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereManufacturingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereMarkupPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereMaximumStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereMinimumStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereRack($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereReorderPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereReorderQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereSafetyInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereShelfLifeDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereSpecifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereSubCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereTotalQuantityPurchased($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereTotalQuantityUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereTotalQuantityWasted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereTotalValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereTrackBatches($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereTrackExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereUnitCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereUnitSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereUnitSizeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereUnitType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem withoutTrashed()
 */
	class InventoryItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $inventory_item_id
 * @property string|null $branch_id
 * @property string|null $created_by
 * @property string|null $approved_by
 * @property string $movement_type
 * @property string $direction
 * @property numeric $quantity
 * @property numeric|null $unit_cost
 * @property numeric|null $total_cost
 * @property numeric|null $previous_stock
 * @property numeric|null $new_stock
 * @property numeric|null $change_amount
 * @property string|null $reference_type
 * @property int|null $reference_id
 * @property string|null $from_branch_id
 * @property string|null $to_branch_id
 * @property string|null $reason
 * @property string|null $notes
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $cancelled_at
 * @property string|null $cancellation_reason
 * @property string|null $document_number
 * @property string|null $document_path
 * @property array<array-key, mixed>|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $approvedBy
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\User|null $createdBy
 * @property-read \App\Models\Branch|null $fromBranch
 * @property-read array $direction_info
 * @property-read string $formatted_quantity
 * @property-read string $formatted_total_cost
 * @property-read string $formatted_unit_cost
 * @property-read array $movement_type_info
 * @property-read string $reference_description
 * @property-read array $status_info
 * @property-read string $stock_change
 * @property-read \App\Models\InventoryItem|null $inventoryItem
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $reference
 * @property-read \App\Models\Branch|null $toBranch
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement adjustments()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement approved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement dateBetween($startDate, $endDate)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement forBranch($branchId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement forItem($itemId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement forReference(string $referenceType, $referenceId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement inbound()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement ofType(string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement outbound()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement purchases()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement sales()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement transfers()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement waste()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereCancellationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereChangeAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereDirection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereDocumentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereDocumentPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereFromBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereInventoryItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereMovementType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereNewStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement wherePreviousStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereReferenceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereToBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereTotalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereUnitCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockMovement withoutTrashed()
 */
	class InventoryStockMovement extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property string $branch_id
 * @property string $order_id
 * @property string $invoice_number
 * @property string $issue_date
 * @property string|null $due_date
 * @property numeric $subtotal
 * @property numeric $tax_amount
 * @property numeric $discount_amount
 * @property numeric $total_amount
 * @property string $status
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Order|null $order
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereIssueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice withoutTrashed()
 */
	class Invoice extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $order_id
 * @property string|null $payment_id
 * @property string|null $merchant_request_id
 * @property string|null $checkout_request_id
 * @property string|null $mpesa_receipt_number
 * @property string $phone_number
 * @property numeric $amount
 * @property string $status
 * @property array<array-key, mixed>|null $raw_payload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\Payment|null $payment
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MpesaTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MpesaTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MpesaTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MpesaTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MpesaTransaction whereCheckoutRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MpesaTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MpesaTransaction whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MpesaTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MpesaTransaction whereMerchantRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MpesaTransaction whereMpesaReceiptNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MpesaTransaction whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MpesaTransaction wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MpesaTransaction wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MpesaTransaction whereRawPayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MpesaTransaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MpesaTransaction whereUpdatedAt($value)
 */
	class MpesaTransaction extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $uuid
 * @property string $order_number
 * @property string|null $invoice_number
 * @property string $branch_id
 * @property string|null $customer_id
 * @property string $created_by
 * @property string|null $updated_by
 * @property string|null $assigned_to
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $status_updated_at
 * @property string|null $status_updated_by
 * @property string $order_type
 * @property string $service_type
 * @property string $payment_status
 * @property \Illuminate\Support\Carbon $order_date
 * @property \Illuminate\Support\Carbon|null $requested_pickup_date
 * @property \Illuminate\Support\Carbon|null $requested_delivery_date
 * @property \Illuminate\Support\Carbon|null $actual_pickup_date
 * @property \Illuminate\Support\Carbon|null $actual_delivery_date
 * @property \Illuminate\Support\Carbon|null $promised_completion_date
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property numeric $subtotal
 * @property numeric $discount_amount
 * @property numeric $tax_amount
 * @property numeric $delivery_fee
 * @property numeric $pickup_fee
 * @property numeric $extra_charges
 * @property numeric $total_amount
 * @property numeric $paid_amount
 * @property numeric $balance_due
 * @property string|null $discount_code
 * @property string|null $discount_type
 * @property numeric|null $discount_value
 * @property numeric|null $tax_rate
 * @property string|null $tax_description
 * @property string|null $delivery_address
 * @property string|null $delivery_contact_name
 * @property string|null $delivery_contact_phone
 * @property string|null $delivery_instructions
 * @property string|null $pickup_address
 * @property string|null $pickup_contact_name
 * @property string|null $pickup_contact_phone
 * @property string|null $pickup_instructions
 * @property string|null $customer_notes
 * @property string|null $staff_notes
 * @property array<array-key, mixed>|null $special_instructions
 * @property array<array-key, mixed>|null $metadata
 * @property array<array-key, mixed>|null $tags
 * @property bool $is_urgent
 * @property bool $is_insured
 * @property bool $requires_approval
 * @property bool $is_approved
 * @property string|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property bool $is_flagged
 * @property string|null $flag_reason
 * @property string|null $flagged_by
 * @property array<array-key, mixed>|null $status_history
 * @property array<array-key, mixed>|null $payment_history
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $approvedBy
 * @property-read \App\Models\User|null $assignedTo
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\User|null $createdBy
 * @property-read \App\Models\Customer|null $customer
 * @property-read \App\Models\CustomerFeedback|null $feedback
 * @property-read \App\Models\User|null $flaggedBy
 * @property-read int $days_since_ordered
 * @property-read string $formatted_balance
 * @property-read string $formatted_total
 * @property-read bool $is_overdue
 * @property-read array $order_type_info
 * @property-read array $payment_status_info
 * @property-read array $service_type_info
 * @property-read array $status_info
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderStatusLog> $statusLogs
 * @property-read int|null $status_logs_count
 * @property-read \App\Models\User|null $statusUpdatedBy
 * @property-read \App\Models\User|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order assignedTo($userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order cancelled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order dateBetween($startDate, $endDate)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order delivered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order flagged()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order forBranch($branchId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order forCustomer($customerId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order overdue()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order paid()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order processing()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order ready()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order search(string $search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order today()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order unpaid()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order urgent()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereActualDeliveryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereActualPickupDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereAssignedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereBalanceDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCustomerNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeliveryAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeliveryContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeliveryContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeliveryInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDiscountCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDiscountValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereExtraCharges($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereFlagReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereFlaggedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIsFlagged($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIsInsured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIsUrgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePickupAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePickupContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePickupContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePickupFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePickupInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePromisedCompletionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereRequestedDeliveryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereRequestedPickupDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereRequiresApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereServiceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereSpecialInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStaffNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatusHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatusUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatusUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTaxDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order withoutTrashed()
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $order_id
 * @property string|null $service_id
 * @property string|null $service_item_id
 * @property string|null $branch_id
 * @property string $name
 * @property string|null $description
 * @property string|null $sku
 * @property string|null $category
 * @property string|null $item_type
 * @property string|null $fabric_type
 * @property string|null $color
 * @property string|null $size
 * @property numeric $quantity
 * @property numeric $unit_price
 * @property numeric $discount_percentage
 * @property numeric $discount_amount
 * @property numeric $tax_rate
 * @property numeric $tax_amount
 * @property numeric $subtotal
 * @property numeric $total
 * @property array<array-key, mixed>|null $add_ons
 * @property array<array-key, mixed>|null $modifiers
 * @property numeric $add_ons_total
 * @property string|null $customer_notes
 * @property string|null $staff_notes
 * @property array<array-key, mixed>|null $special_instructions
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $status_updated_at
 * @property string|null $status_updated_by
 * @property string|null $assigned_to
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property bool $requires_inspection
 * @property bool $inspected
 * @property string|null $inspected_by
 * @property \Illuminate\Support\Carbon|null $inspected_at
 * @property string|null $inspection_notes
 * @property bool $track_inventory
 * @property string|null $inventory_item_id
 * @property numeric|null $inventory_quantity_used
 * @property bool $inventory_deducted
 * @property bool $is_urgent
 * @property bool $is_express
 * @property bool $is_insured
 * @property bool $is_flagged
 * @property string|null $flag_reason
 * @property array<array-key, mixed>|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $assignedTo
 * @property-read \App\Models\Branch|null $branch
 * @property-read string $display_name
 * @property-read array $formatted_add_ons
 * @property-read string $formatted_total
 * @property-read string $formatted_unit_price
 * @property-read bool $has_add_ons
 * @property-read int|null $processing_time
 * @property-read array $status_info
 * @property-read \App\Models\User|null $inspectedBy
 * @property-read \App\Models\InventoryItem|null $inventoryItem
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\Service|null $service
 * @property-read \App\Models\ServiceItem|null $serviceItem
 * @property-read \App\Models\User|null $statusUpdatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem assignedTo($userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem cancelled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem express()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem flagged()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem inCategory(string $category)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem inspected()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem ofType(string $itemType)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem processing()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem requiresInspection()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem urgent()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereAddOns($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereAddOnsTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereAssignedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCustomerNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereDiscountPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereFabricType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereFlagReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereInspected($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereInspectedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereInspectedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereInspectionNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereInventoryDeducted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereInventoryItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereInventoryQuantityUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereIsExpress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereIsFlagged($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereIsInsured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereIsUrgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereItemType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereModifiers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereRequiresInspection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereServiceItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereSpecialInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereStaffNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereStatusUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereStatusUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereTrackInventory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem withoutTrashed()
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $order_id
 * @property string|null $old_status
 * @property string $new_status
 * @property string|null $notes
 * @property string|null $changed_by
 * @property \Illuminate\Support\Carbon $changed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\User|null $changedBy
 * @property-read \App\Models\Order|null $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderStatusLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderStatusLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderStatusLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderStatusLog whereChangedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderStatusLog whereChangedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderStatusLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderStatusLog whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderStatusLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderStatusLog whereNewStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderStatusLog whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderStatusLog whereOldStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderStatusLog whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderStatusLog whereUpdatedAt($value)
 */
	class OrderStatusLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $payment_number
 * @property string|null $receipt_number
 * @property string $order_id
 * @property string|null $customer_id
 * @property string $branch_id
 * @property string $created_by
 * @property string|null $updated_by
 * @property string|null $confirmed_by
 * @property string $currency
 * @property numeric $amount
 * @property numeric $tip_amount
 * @property numeric $change_amount
 * @property numeric $refunded_amount
 * @property string $payment_method
 * @property string|null $payment_channel
 * @property string $status
 * @property string|null $transaction_id
 * @property string|null $reference_number
 * @property string|null $authorization_code
 * @property string|null $cheque_number
 * @property \Illuminate\Support\Carbon|null $cheque_date
 * @property string|null $cheque_bank
 * @property \Illuminate\Support\Carbon|null $cheque_cleared_at
 * @property string|null $card_last_four
 * @property string|null $card_type
 * @property string|null $card_holder_name
 * @property string|null $mobile_number
 * @property string|null $mobile_name
 * @property string|null $mobile_transaction_time
 * @property int|null $split_sequence
 * @property string|null $parent_payment_id
 * @property bool $is_reconciled
 * @property \Illuminate\Support\Carbon|null $reconciled_at
 * @property string|null $reconciled_by
 * @property array<array-key, mixed>|null $metadata
 * @property string|null $notes
 * @property string|null $staff_notes
 * @property bool $is_flagged
 * @property string|null $flag_reason
 * @property string|null $flagged_by
 * @property \Illuminate\Support\Carbon $payment_date
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Payment> $childPayments
 * @property-read int|null $child_payments_count
 * @property-read \App\Models\User|null $createdBy
 * @property-read \App\Models\Customer|null $customer
 * @property-read \App\Models\User|null $flaggedBy
 * @property-read string $formatted_amount
 * @property-read string $formatted_change
 * @property-read string $formatted_refunded
 * @property-read string $formatted_tip
 * @property-read string $formatted_total
 * @property-read bool $is_fully_refunded
 * @property-read string|null $masked_card
 * @property-read string|null $mpesa_full_name
 * @property-read array $payment_method_info
 * @property-read float $refundable_amount
 * @property-read array $status_info
 * @property-read \App\Models\Order|null $order
 * @property-read Payment|null $parentPayment
 * @property-read \App\Models\User|null $reconciledBy
 * @property-read \App\Models\User|null $refundedBy
 * @property-read \App\Models\User|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment card()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment cash()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment dateBetween($startDate, $endDate)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment failed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment flagged()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment forBranch($branchId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment forCustomer($customerId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment mpesa()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment onDate($date)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment reconciled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment refunded()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment search(string $search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment unreconciled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAuthorizationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCardHolderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCardLastFour($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCardType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereChangeAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereChequeBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereChequeClearedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereChequeDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereChequeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereConfirmedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereFlagReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereFlaggedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereIsFlagged($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereIsReconciled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereMobileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereMobileNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereMobileTransactionTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereParentPaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereReceiptNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereReconciledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereReconciledBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereRefundedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereSplitSequence($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStaffNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTipAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment withMethod(string $method)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment withoutTrashed()
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $fund_number
 * @property string $name
 * @property string $code
 * @property string $branch_id
 * @property string $custodian_id
 * @property numeric $opening_balance
 * @property numeric $current_balance
 * @property numeric $minimum_balance
 * @property numeric|null $maximum_balance
 * @property string $status
 * @property \Illuminate\Support\Carbon $established_date
 * @property \Illuminate\Support\Carbon|null $last_replenished_at
 * @property \Illuminate\Support\Carbon|null $last_counted_at
 * @property \Illuminate\Support\Carbon|null $closed_at
 * @property bool $auto_replenish
 * @property numeric|null $replenishment_threshold
 * @property numeric|null $replenishment_amount
 * @property string|null $replenishment_method
 * @property numeric|null $max_transaction_amount
 * @property numeric|null $daily_withdrawal_limit
 * @property int|null $max_transactions_per_day
 * @property bool $requires_approval
 * @property numeric|null $approval_threshold
 * @property string|null $approver_id
 * @property string|null $account_code
 * @property string|null $gl_account
 * @property string|null $location
 * @property string|null $description
 * @property string|null $purpose
 * @property \Illuminate\Support\Carbon|null $last_audited_at
 * @property string|null $last_audited_by
 * @property string|null $notes
 * @property array<array-key, mixed>|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $approver
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\User|null $custodian
 * @property-read string $formatted_balance
 * @property-read string $formatted_opening_balance
 * @property-read bool $is_below_minimum
 * @property-read bool $is_daily_limit_exceeded
 * @property-read bool $is_transaction_limit_exceeded
 * @property-read array $status_info
 * @property-read float $today_disbursements
 * @property-read int $today_transaction_count
 * @property-read \App\Models\User|null $lastAuditedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PettyCashTransaction> $transactions
 * @property-read int|null $transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash belowMinimum()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash forBranch($branchId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash forCustodian($userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash search(string $search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereAccountCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereApprovalThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereApproverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereAutoReplenish($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereCurrentBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereCustodianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereDailyWithdrawalLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereEstablishedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereFundNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereGlAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereLastAuditedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereLastAuditedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereLastCountedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereLastReplenishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereMaxTransactionAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereMaxTransactionsPerDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereMaximumBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereMinimumBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereOpeningBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereReplenishmentAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereReplenishmentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereReplenishmentThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereRequiresApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCash withoutTrashed()
 */
	class PettyCash extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $transaction_number
 * @property string $petty_cash_id
 * @property string $type
 * @property string $direction
 * @property numeric $amount
 * @property numeric $balance_before
 * @property numeric $balance_after
 * @property \Illuminate\Support\Carbon $transaction_date
 * @property \Illuminate\Support\Carbon $recorded_at
 * @property string|null $expense_category_id
 * @property string|null $reference_type
 * @property int|null $reference_id
 * @property string|null $receipt_number
 * @property string|null $payee_name
 * @property string|null $payee_type
 * @property string $description
 * @property string|null $notes
 * @property bool $requires_approval
 * @property string $approval_status
 * @property string|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property string|null $rejection_reason
 * @property string|null $receipt_path
 * @property array<array-key, mixed>|null $attachments
 * @property string $created_by
 * @property string|null $updated_by
 * @property array<array-key, mixed>|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $approvedBy
 * @property-read \App\Models\ExpenseCategory|null $category
 * @property-read \App\Models\User|null $createdBy
 * @property-read array $approval_status_info
 * @property-read string $formatted_amount
 * @property-read array $type_info
 * @property-read \App\Models\PettyCash|null $pettyCash
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $reference
 * @property-read \App\Models\User|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction dateBetween($startDate, $endDate)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction disbursements()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction inbound()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction outbound()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction pendingApproval()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction replenishments()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereApprovalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereAttachments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereBalanceAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereBalanceBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereDirection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereExpenseCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction wherePayeeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction wherePayeeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction wherePettyCashId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereReceiptNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereReceiptPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereRecordedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereReferenceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereRequiresApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereTransactionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PettyCashTransaction withoutTrashed()
 */
	class PettyCashTransaction extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $branch_id
 * @property string $name
 * @property string $slug
 * @property string $code
 * @property string|null $description
 * @property string|null $short_description
 * @property string $category
 * @property string|null $sub_category
 * @property array<array-key, mixed>|null $tags
 * @property string $pricing_type
 * @property numeric $base_price
 * @property numeric|null $minimum_charge
 * @property array<array-key, mixed>|null $price_tiers
 * @property int|null $estimated_duration
 * @property string|null $unit_type
 * @property numeric $min_quantity
 * @property numeric|null $max_quantity
 * @property bool $is_active
 * @property bool $is_visible_online
 * @property bool $requires_pickup
 * @property bool $requires_delivery
 * @property bool $is_express_available
 * @property numeric $express_multiplier
 * @property string|null $icon
 * @property string|null $image
 * @property array<array-key, mixed>|null $gallery
 * @property int $sort_order
 * @property bool $is_featured
 * @property bool $is_new
 * @property bool $has_discount
 * @property numeric|null $discount_percentage
 * @property \Illuminate\Support\Carbon|null $discount_until
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property array<array-key, mixed>|null $faqs
 * @property array<array-key, mixed>|null $instructions
 * @property array<array-key, mixed>|null $restrictions
 * @property array<array-key, mixed>|null $inclusions
 * @property array<array-key, mixed>|null $exclusions
 * @property bool $track_inventory
 * @property string|null $inventory_item_id
 * @property numeric|null $inventory_quantity_per_unit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read string $category_color
 * @property-read float $discount_amount
 * @property-read array|null $discount_status
 * @property-read string $display_name
 * @property-read string $duration_formatted
 * @property-read float $effective_price
 * @property-read float|null $express_price
 * @property-read array $status
 * @property-read \App\Models\InventoryItem|null $inventoryItem
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ServiceItem> $serviceItems
 * @property-read int|null $service_items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service expressAvailable()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service featured()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service forBranch($branchId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service inCategory(string $category)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service onDiscount()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service search(string $search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service visibleOnline()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereBasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereDiscountPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereDiscountUntil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereEstimatedDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereExclusions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereExpressMultiplier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereFaqs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereGallery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereHasDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereInclusions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereInventoryItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereInventoryQuantityPerUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereIsExpressAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereIsNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereIsVisibleOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereMaxQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereMetaKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereMinQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereMinimumCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service wherePriceTiers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service wherePricingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereRequiresDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereRequiresPickup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereRestrictions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereSubCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereTrackInventory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereUnitType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service withoutTrashed()
 */
	class Service extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $service_id
 * @property string|null $branch_id
 * @property string $name
 * @property string|null $slug
 * @property string|null $code
 * @property string|null $description
 * @property string|null $short_description
 * @property string $item_type
 * @property string|null $fabric_type
 * @property string|null $color
 * @property string|null $size
 * @property numeric $base_price
 * @property numeric|null $minimum_charge
 * @property string $pricing_model
 * @property array<array-key, mixed>|null $price_modifiers
 * @property bool $is_active
 * @property int|null $estimated_duration
 * @property array<array-key, mixed>|null $special_instructions
 * @property bool $track_inventory
 * @property string|null $inventory_item_id
 * @property numeric|null $inventory_quantity_per_unit
 * @property int $sort_order
 * @property bool $is_popular
 * @property bool $requires_special_handling
 * @property numeric|null $special_handling_fee
 * @property string|null $icon
 * @property string|null $image
 * @property array<array-key, mixed>|null $gallery
 * @property array<array-key, mixed>|null $care_instructions
 * @property array<array-key, mixed>|null $restrictions
 * @property array<array-key, mixed>|null $add_ons_available
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read string $display_name
 * @property-read float $effective_price
 * @property-read string $fabric_type_label
 * @property-read bool $has_price_modifiers
 * @property-read string $item_type_label
 * @property-read string $size_label
 * @property-read array $status
 * @property-read \App\Models\InventoryItem|null $inventoryItem
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \App\Models\Service $service
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem forBranch($branchId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem forService($serviceId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem ofType(string $itemType)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem popular()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem search(string $search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem specialHandling()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereAddOnsAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereBasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereCareInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereEstimatedDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereFabricType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereGallery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereInventoryItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereInventoryQuantityPerUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereIsPopular($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereItemType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereMinimumCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem wherePriceModifiers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem wherePricingModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereRequiresSpecialHandling($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereRestrictions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereSpecialHandlingFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereSpecialInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereTrackInventory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem withFabric(string $fabricType)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem withSize(string $size)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceItem withoutTrashed()
 */
	class ServiceItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property string|null $branch_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereValue($value)
 */
	class Setting extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $branch_id
 * @property string $name
 * @property string $code
 * @property string $business_type
 * @property string|null $tax_number
 * @property string|null $registration_number
 * @property string|null $contact_person
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $mobile
 * @property string|null $fax
 * @property string|null $website
 * @property string|null $address_line1
 * @property string|null $address_line2
 * @property string|null $city
 * @property string|null $state
 * @property string|null $postal_code
 * @property string $country
 * @property string|null $bank_name
 * @property string|null $bank_account_name
 * @property string|null $bank_account_number
 * @property string|null $bank_branch
 * @property string|null $bank_swift_code
 * @property string|null $bank_sort_code
 * @property string $payment_terms
 * @property int $payment_due_days
 * @property numeric|null $credit_limit
 * @property numeric $current_balance
 * @property array<array-key, mixed>|null $products_supplied
 * @property array<array-key, mixed>|null $service_areas
 * @property numeric|null $minimum_order_value
 * @property numeric|null $delivery_fee
 * @property int|null $lead_time_days
 * @property \Illuminate\Support\Carbon|null $contract_start_date
 * @property \Illuminate\Support\Carbon|null $contract_end_date
 * @property bool $is_exclusive
 * @property string|null $contract_file
 * @property numeric|null $rating
 * @property int $total_orders
 * @property numeric $total_spent
 * @property numeric|null $on_time_delivery_rate
 * @property numeric|null $quality_rating
 * @property bool $is_active
 * @property bool $is_approved
 * @property string|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property string|null $notes
 * @property array<array-key, mixed>|null $tags
 * @property array<array-key, mixed>|null $documents
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $approvedBy
 * @property-read \App\Models\Branch|null $branch
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Expense> $expenses
 * @property-read int|null $expenses_count
 * @property-read float|null $available_credit
 * @property-read string $business_type_label
 * @property-read string $full_address
 * @property-read bool $has_active_contract
 * @property-read float $outstanding_balance
 * @property-read string $payment_terms_label
 * @property-read string $primary_phone
 * @property-read string $rating_stars
 * @property-read array $status
 * @property-read float $total_paid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InventoryItem> $inventoryItems
 * @property-read int|null $inventory_items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier approved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier exclusive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier forBranch($branchId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier inCity(string $city)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier minRating(float $rating)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier ofType(string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier pendingApproval()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier search(string $search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier suppliesProduct(string $product)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier topRated()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereBankAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereBankAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereBankBranch($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereBankSortCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereBankSwiftCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereBusinessType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereContactPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereContractEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereContractFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereContractStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCreditLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCurrentBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereDocuments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereFax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereIsExclusive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereLeadTimeDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereMinimumOrderValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereOnTimeDeliveryRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePaymentDueDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePaymentTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereProductsSupplied($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereQualityRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereRegistrationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereServiceAreas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereTaxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereTotalOrders($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereTotalSpent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier withBalance()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier withCredit()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier withoutTrashed()
 */
	class Supplier extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $branch_id
 * @property bool $is_active
 * @property string|null $phone
 * @property string|null $profile_photo
 * @property string|null $job_title
 * @property string|null $bio
 * @property string|null $hired_at
 * @property string|null $last_login_at
 * @property string|null $last_login_ip
 * @property int $login_count
 * @property string|null $preferences
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserBranchRole> $branchRoles
 * @property-read int|null $branch_roles_count
 * @property-read \App\Models\Branch|null $current_branch
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User active()
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, ?string $guard = null, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereHiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereJobTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLoginCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePreferences($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfilePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, ?string $guard = null)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $user_id
 * @property string|null $branch_id
 * @property int $role_id
 * @property string|null $assigned_by
 * @property \Illuminate\Support\Carbon $assigned_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $assignedBy
 * @property-read \App\Models\Branch|null $branch
 * @property-read \Spatie\Permission\Models\Role $role
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBranchRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBranchRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBranchRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBranchRole whereAssignedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBranchRole whereAssignedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBranchRole whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBranchRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBranchRole whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBranchRole whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBranchRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBranchRole whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBranchRole whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBranchRole whereUserId($value)
 */
	class UserBranchRole extends \Eloquent {}
}

