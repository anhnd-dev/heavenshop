<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | ORDER STATUS
    |--------------------------------------------------------------------------
    */

    public const STATUS_PENDING = 'pending';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_SHIPPING = 'shipping';

    public const STATUS_DELIVERED = 'delivered';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_RETURNED = 'returned';

    /*
    |--------------------------------------------------------------------------
    | PAYMENT STATUS
    |--------------------------------------------------------------------------
    */

    public const PAYMENT_PENDING = 'pending';

    public const PAYMENT_PAID = 'paid';

    public const PAYMENT_FAILED = 'failed';

    public const PAYMENT_REFUNDED = 'refunded';

    /*
    |--------------------------------------------------------------------------
    | PAYMENT METHOD
    |--------------------------------------------------------------------------
    */

    public const PAYMENT_COD = 'cod';

    public const PAYMENT_VNPAY = 'vnpay';

    public const PAYMENT_MOMO = 'momo';

    public const PAYMENT_ZALOPAY = 'zalopay';

    /*
    |--------------------------------------------------------------------------
    | ORDER BADGE
    |--------------------------------------------------------------------------
    */

    public const ORDER_BADGE_CLASSES = [

        self::STATUS_PENDING => 'warning',

        self::STATUS_CONFIRMED => 'primary',

        self::STATUS_SHIPPING => 'info',

        self::STATUS_DELIVERED => 'success',

        self::STATUS_CANCELLED => 'danger',

        self::STATUS_RETURNED => 'dark',
    ];

    /*
    |--------------------------------------------------------------------------
    | PAYMENT BADGE
    |--------------------------------------------------------------------------
    */

    public const PAYMENT_BADGE_CLASSES = [

        self::PAYMENT_PENDING => 'warning',

        self::PAYMENT_PAID => 'success',

        self::PAYMENT_FAILED => 'danger',

        self::PAYMENT_REFUNDED => 'dark',
    ];

    /*
    |--------------------------------------------------------------------------
    | ORDER FLOW
    |--------------------------------------------------------------------------
    */

    public const ORDER_FLOW = [

        self::STATUS_PENDING => [

            self::STATUS_CONFIRMED,
            self::STATUS_CANCELLED,
        ],

        self::STATUS_CONFIRMED => [

            self::STATUS_SHIPPING,
            self::STATUS_CANCELLED,
        ],

        self::STATUS_SHIPPING => [

            self::STATUS_DELIVERED,
            self::STATUS_RETURNED,
        ],

        self::STATUS_DELIVERED => [],

        self::STATUS_CANCELLED => [],

        self::STATUS_RETURNED => [],
    ];

    /*
    |--------------------------------------------------------------------------
    | PAYMENT FLOW
    |--------------------------------------------------------------------------
    */

    public const PAYMENT_FLOW = [

        self::PAYMENT_PENDING => [

            self::PAYMENT_PAID,
            self::PAYMENT_FAILED,
        ],

        self::PAYMENT_PAID => [

            self::PAYMENT_REFUNDED,
        ],

        self::PAYMENT_FAILED => [],

        self::PAYMENT_REFUNDED => [],
    ];

    /*
    |--------------------------------------------------------------------------
    | LABELS
    |--------------------------------------------------------------------------
    */

    public const ORDER_STATUS_LABELS = [

        self::STATUS_PENDING => 'Chờ xác nhận',

        self::STATUS_CONFIRMED => 'Đã xác nhận',

        self::STATUS_SHIPPING => 'Đang giao hàng',

        self::STATUS_DELIVERED => 'Đã giao hàng',

        self::STATUS_CANCELLED => 'Đã hủy',

        self::STATUS_RETURNED => 'Đã trả hàng',
    ];

    public const PAYMENT_STATUS_LABELS = [

        self::PAYMENT_PENDING => 'Chờ thanh toán',

        self::PAYMENT_PAID => 'Đã thanh toán',

        self::PAYMENT_FAILED => 'Thanh toán thất bại',

        self::PAYMENT_REFUNDED => 'Đã hoàn tiền',
    ];

    public const PAYMENT_METHOD_LABELS = [

        self::PAYMENT_COD => 'Thanh toán khi nhận hàng',

        self::PAYMENT_VNPAY => 'VNPay',

        self::PAYMENT_MOMO => 'MoMo',

        self::PAYMENT_ZALOPAY => 'ZaloPay',
    ];

    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'order_code',

        'customer_id',
        'coupon_id',
        'customer_address_id',

        'shipping_name',
        'shipping_phone',
        'shipping_email',

        'shipping_province',
        'shipping_district',
        'shipping_ward',
        'shipping_address',

        'note',

        'shipping_method',
        'shipping_fee',

        'payment_method',
        'payment_status',

        'order_status',

        'subtotal',
        'discount_amount',
        'grand_total',

        'paid_at',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'returned_at',
        'refunded_at',

        'payment_deadline',

        'cancel_reason',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'subtotal' => 'decimal:2',

        'shipping_fee' => 'decimal:2',

        'discount_amount' => 'decimal:2',

        'grand_total' => 'decimal:2',

        'paid_at' => 'datetime',

        'confirmed_at' => 'datetime',

        'shipped_at' => 'datetime',

        'delivered_at' => 'datetime',

        'cancelled_at' => 'datetime',

        'returned_at' => 'datetime',

        'refunded_at' => 'datetime',

        'payment_deadline' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function customerAddress()
    {
        return $this->belongsTo(CustomerAddress::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentTransactions()
    {
        return $this->hasMany(
            PaymentTransaction::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeOfCustomer(
        $query,
        int $customerId
    ) {
        return $query->where(
            'customer_id',
            $customerId
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getFullAddressAttribute(): string
    {
        return implode(', ', [

            $this->shipping_address,

            $this->shipping_ward,

            $this->shipping_district,

            $this->shipping_province,
        ]);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::ORDER_STATUS_LABELS[$this->order_status] ?? 'Không xác định';
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return self::PAYMENT_STATUS_LABELS[$this->payment_status] ?? 'Không xác định';
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return self::PAYMENT_METHOD_LABELS[$this->payment_method] ?? strtoupper($this->payment_method);
    }

    public function getOrderBadgeAttribute(): string
    {
        $class = self::ORDER_BADGE_CLASSES[$this->order_status] ?? 'secondary';

        return sprintf(
            '<span class="badge badge-%s">%s</span>',
            $class,
            $this->status_label
        );
    }

    public function getPaymentBadgeAttribute(): string
    {
        $class = self::PAYMENT_BADGE_CLASSES[$this->payment_status] ?? 'secondary';

        return sprintf(
            '<span class="badge badge-%s">%s</span>',
            $class,
            $this->payment_status_label
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS HELPERS
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->order_status
            === self::STATUS_PENDING;
    }

    public function isConfirmed(): bool
    {
        return $this->order_status
            === self::STATUS_CONFIRMED;
    }

    public function isShipping(): bool
    {
        return $this->order_status
            === self::STATUS_SHIPPING;
    }

    public function isDelivered(): bool
    {
        return $this->order_status
            === self::STATUS_DELIVERED;
    }

    public function isCancelled(): bool
    {
        return $this->order_status
            === self::STATUS_CANCELLED;
    }

    public function isReturned(): bool
    {
        return $this->order_status
            === self::STATUS_RETURNED;
    }

    public function isPaid(): bool
    {
        return $this->payment_status
            === self::PAYMENT_PAID;
    }

    public function isPendingPayment(): bool
    {
        return $this->payment_status
            === self::PAYMENT_PENDING;
    }

    public function isRefunded(): bool
    {
        return $this->payment_status
            === self::PAYMENT_REFUNDED;
    }

    /*
    |--------------------------------------------------------------------------
    | FLOW HELPERS
    |--------------------------------------------------------------------------
    */

    public function availableOrderStatuses(): array
    {
        return self::ORDER_FLOW[$this->order_status] ?? [];
    }

    public static function getOrderStatusLabel(
        string $status
    ): string {

        return self::ORDER_STATUS_LABELS[$status]
            ?? 'Không xác định';
    }

    public function availableOrderStatusOptions(): array
    {
        return collect(
            $this->availableOrderStatuses()
        )
            ->mapWithKeys(fn($status) => [
                $status => self::getOrderStatusLabel($status)
            ])
            ->toArray();
    }

    public static function getPaymentStatusLabel(
        string $status
    ): string {
        return self::PAYMENT_STATUS_LABELS[$status]
            ?? 'Không xác định';
    }

    public function availablePaymentStatuses(): array
    {
        if (
            in_array(
                $this->order_status,
                [
                    self::STATUS_CANCELLED,
                    self::STATUS_RETURNED,
                ]
            )
        ) {
            return [];
        }

        return self::PAYMENT_FLOW[$this->payment_status] ?? [];
    }

    public function availablePaymentStatusOptions(): array
    {
        return collect(
            $this->availablePaymentStatuses()
        )
            ->mapWithKeys(fn($status) => [
                $status => self::getPaymentStatusLabel($status)
            ])
            ->toArray();
    }

    public function canMoveTo(
        string $status
    ): bool {

        return in_array(
            $status,
            $this->availableOrderStatuses()
        );
    }

    public function canChangePaymentTo(
        string $status
    ): bool {

        return in_array(
            $status,
            $this->availablePaymentStatuses()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BUSINESS RULES
    |--------------------------------------------------------------------------
    */

    public function canCustomerCancel(): bool
    {
        return in_array(
            $this->order_status,
            [
                self::STATUS_PENDING,
                self::STATUS_CONFIRMED,
            ]
        );
    }

    public function canAdminCancel(): bool
    {
        return !in_array(
            $this->order_status,
            [
                self::STATUS_DELIVERED,
                self::STATUS_CANCELLED,
                self::STATUS_RETURNED,
            ]
        );
    }

    public function canReturn(): bool
    {
        return in_array(
            $this->order_status,
            [
                self::STATUS_SHIPPING,
                self::STATUS_DELIVERED,
            ]
        );
    }

    public function isExpiredPayment(): bool
    {
        return $this->payment_status
            === self::PAYMENT_PENDING
            &&
            $this->payment_deadline
            &&
            $this->payment_deadline->isPast();
    }
}
