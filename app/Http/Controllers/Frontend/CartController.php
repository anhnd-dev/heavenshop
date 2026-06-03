<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\ProductVariant;

use App\Services\Frontend\CartService;
use App\Services\Frontend\CouponService;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CouponService $couponService
    ) {}

    /*
    |--------------------------------------------------------------------------
    | CART PAGE
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $customerId = Auth::guard('customer')->id();

        $cart = $this->cartService->getCart();

        $subtotal = $this->cartService->subtotalSelected();

        $shipping = $this->cartService->shipping($subtotal);

        $discount = session(
            'applied_coupon.discount',
            0
        );

        $total = max(
            $subtotal + $shipping - $discount,
            0
        );

        // customer
        $customer = null;

        $addresses = collect();

        $defaultAddress = null;

        if (Auth::guard('customer')->check()) {

            $customer = Customer::query()
                ->with(['addresses' => function ($q) {
                    $q->orderByDesc('is_default');
                }])
                ->find($customerId);

            $addresses = $customer->addresses;

            $defaultAddress = $addresses->first();
        }

        // coupons
        $availableCoupons = $this->couponService
            ->availableCoupons(
                $subtotal,
                Auth::guard('customer')->id()
            );

        // suggest product
        $suggestProducts = Product::query()
            ->active()
            ->latest()
            ->take(8)
            ->get();

        return view(
            'frontend.cart.index',
            compact(
                'cart',
                'subtotal',
                'shipping',
                'discount',
                'total',

                'customer',
                'addresses',
                'defaultAddress',

                'availableCoupons',
                'suggestProducts'
            )
        );
    }

    // =========================
    // RENDER CART ITEMS AJAX
    // =========================
    public function items()
    {
        $cart = $this->cartService->getCart();

        $subtotal = $this->cartService
            ->subtotalSelected();

        $shipping = $this->cartService
            ->shipping();

        $discount = session(
            'applied_coupon.discount',
            0
        );

        $total = max(
            $subtotal + $shipping - $discount,
            0
        );

        $availableCoupons = $this->couponService
            ->availableCoupons(
                $subtotal,
                auth('customer')->id()
            );

        return response()->json([

            'items' => view(
                'frontend.cart.partials.items',
                compact(
                    'cart',
                    'subtotal',
                    'shipping',
                    'discount',
                    'total',
                    'availableCoupons'
                )
            )->render(),

            'fixedBar' => view(
                'frontend.cart.partials.fixed-bar',
                compact(
                    'subtotal',
                    'shipping',
                    'discount',
                    'total'
                )
            )->render()
        ]);
    }

    // =========================
    // ADD TO CART
    // =========================
    public function add(Request $request)
    {
        $request->validate([

            'quantity' => 'required|integer|min:1',

            'variant_id' => 'nullable|integer',

            'product_id' => 'nullable|integer',

            'color_id' => 'nullable|integer',

            'size_id' => 'nullable|integer',
        ]);

        if ($request->variant_id) {

            $variant = ProductVariant::query()
                ->with([
                    'product.variants.color',
                    'product.variants.size',
                    'color',
                    'size'
                ])
                ->find($request->variant_id);
        } else {

            $variant = ProductVariant::query()
                ->with([
                    'product.variants.color',
                    'product.variants.size',
                    'color',
                    'size'
                ])
                ->where('product_id', $request->product_id)
                ->where('color_id', $request->color_id)
                ->where('size_id', $request->size_id)
                ->first();
        }

        if (!$variant) {

            return response()->json([
                'status' => 404,
                'message' => 'Biến thể không tồn tại'
            ], 404);
        }

        if ($variant->stock <= 0) {

            return response()->json([
                'status' => 400,
                'message' => 'Sản phẩm đã hết hàng'
            ], 400);
        }

        // Get cart
        $cart = $this->cartService->getCart();

        $cartKey = $variant->id;

        $currentQty = isset($cart[$cartKey])
            ? $cart[$cartKey]['quantity']
            : 0;

        $newQty = $currentQty + $request->quantity;

        // Check stock
        if ($newQty > $variant->stock) {

            return response()->json([
                'status' => 400,
                'message' => 'Chỉ còn ' . $variant->stock . ' sản phẩm'
            ], 400);
        }

        // Update exist item
        if (isset($cart[$cartKey])) {

            $cart[$cartKey]['quantity'] = $newQty;
        }

        // New item
        else {

            $cart[$cartKey] = $this->buildCartItem(
                $variant,
                $request->quantity
            );
        }

        $this->cartService->putCart($cart);

        return response()->json([
            'status' => 200,
            'message' => 'Đã thêm vào giỏ hàng'
        ]);
    }

    // =========================
    // UPDATE QUANTITY
    // =========================
    public function update(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        $variant = ProductVariant::query()
            ->find($request->variant_id);

        if (!$variant) {

            return response()->json([
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        if ($request->quantity > $variant->stock) {

            return response()->json([
                'message' => 'Vượt quá tồn kho'
            ], 400);
        }

        $cart = $this->cartService->getCart();

        if (!isset($cart[$variant->id])) {

            return response()->json([
                'message' => 'Sản phẩm không có trong giỏ'
            ], 404);
        }

        $cart[$variant->id]['quantity']
            = $request->quantity;

        $this->cartService->putCart($cart);

        session()->forget('applied_coupon');

        $subtotal = $this->cartService
            ->subtotalSelected();

        $shipping = $this->cartService
            ->shipping($subtotal);

        $discount = session(
            'applied_coupon.discount',
            0
        );

        $total = max(
            $subtotal + $shipping,
            0
        );

        return response()->json([

            'message' => 'Cập nhật thành công',

            'summary' => [

                'subtotal' => $subtotal,

                'shipping' => $shipping,

                'discount' => $discount,

                'total' => $total,
            ]
        ]);
    }

    // =========================
    // CHANGE VARIANT
    // =========================
    public function changeVariant(Request $request)
    {
        $request->validate([

            'old_variant_id' => 'required|integer',

            'color_id' => 'required|integer',

            'size_id' => 'required|integer',
        ]);

        $cart = $this->cartService->getCart();

        if (!isset($cart[$request->old_variant_id])) {

            return response()->json([
                'message' => 'Sản phẩm không tồn tại'
            ], 404);
        }

        $oldItem = $cart[$request->old_variant_id];

        $newVariant = ProductVariant::query()
            ->with([
                'product.variants.color',
                'product.variants.size',
                'product',
                'color',
                'size'
            ])
            ->where('product_id', $oldItem['product_id'])
            ->where('color_id', $request->color_id)
            ->where('size_id', $request->size_id)
            ->first();

        if (!$newVariant) {

            return response()->json([
                'message' => 'Biến thể không tồn tại'
            ], 404);
        }

        if ($newVariant->stock <= 0) {

            return response()->json([
                'message' => 'Biến thể đã hết hàng'
            ], 400);
        }

        // same variant
        if ($newVariant->id == $request->old_variant_id) {

            return response()->json([
                'message' => 'Không có thay đổi'
            ]);
        }

        $quantity = min(
            $oldItem['quantity'],
            $newVariant->stock
        );

        // remove old
        unset($cart[$request->old_variant_id]);

        if (isset($cart[$newVariant->id])) {

            $cart[$newVariant->id]['quantity'] += $quantity;
        } else {

            $cart[$newVariant->id] = $this->buildCartItem(
                $newVariant,
                $quantity,
                $oldItem['selected'] ?? true
            );
        }

        $this->cartService->putCart($cart);

        session()->forget('applied_coupon');

        return response()->json([

            'status' => 200,

            'message' => 'Đã cập nhật biến thể'
        ]);
    }

    // =========================
    // SELECT ITEM
    // =========================
    public function select(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|integer',
            'selected' => 'required|boolean'
        ]);

        $this->cartService->toggleItem(
            $request->variant_id,
            $request->selected
        );

        session()->forget('applied_coupon');

        return response()->json([
            'status' => 200,
            'message' => 'Đã cập nhật'
        ]);
    }

    // =========================
    // SELECT ALL
    // =========================
    public function selectAll(Request $request)
    {
        $request->validate([
            'selected' => 'required|boolean'
        ]);

        $this->cartService->toggleAll(
            $request->selected
        );

        session()->forget('applied_coupon');

        return response()->json([
            'status' => 200,
            'message' => 'Đã cập nhật'
        ]);
    }

    // =========================
    // REMOVE ITEM
    // =========================
    public function remove(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|integer',
        ]);

        $cart = $this->cartService->getCart();

        if (!isset($cart[$request->variant_id])) {

            return response()->json([
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng'
            ], 404);
        }

        unset($cart[$request->variant_id]);

        $this->cartService->putCart($cart);

        session()->forget('applied_coupon');

        return response()->json([
            'status' => 200,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng'
        ]);
    }

    // =========================
    // CLEAR CART
    // =========================
    public function clear()
    {
        session()->forget([
            'cart',
            'applied_coupon'
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Đã xóa toàn bộ giỏ hàng'
        ]);
    }

    // =========================
    // APPLY COUPON
    // =========================
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        // =========================
        // SUBTOTAL SELECTED
        // =========================
        $subtotal = $this->cartService
            ->subtotalSelected();

        // Không có sản phẩm được chọn
        if ($subtotal <= 0) {

            return response()->json([
                'status' => 400,
                'message' => 'Vui lòng chọn sản phẩm'
            ], 400);
        }

        // =========================
        // VALIDATE COUPON
        // =========================
        $result = $this->couponService
            ->validateCoupon(
                $request->coupon_code,
                $subtotal,
                auth('customer')->id()
            );

        // Coupon invalid
        if (!$result['success']) {

            return response()->json([
                'status' => 400,
                'message' => $result['message']
            ], 400);
        }

        // =========================
        // DATA
        // =========================
        $coupon = $result['coupon'];

        $discount = $result['discount'];

        // =========================
        // SAVE SESSION
        // =========================
        session()->put('applied_coupon', [

            'id' => $coupon->id,

            'code' => $coupon->code,

            'discount' => $discount,
        ]);

        // =========================
        // TOTAL
        // =========================
        $shipping = $this->cartService
            ->shipping($subtotal);

        $total =
            $subtotal +
            $shipping -
            $discount;

        // tránh âm tiền
        $total = max($total, 0);

        return response()->json([

            'status' => 200,

            'message' => 'Áp mã thành công',

            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
            ],

            'summary' => [

                'subtotal' => $subtotal,

                'shipping' => $shipping,

                'discount' => $discount,

                'total' => $total,
            ]
        ]);
    }

    // =========================
    // REMOVE COUPON
    // =========================
    public function removeCoupon()
    {
        session()->forget('applied_coupon');

        return response()->json([
            'status' => 200,
            'message' => 'Đã xóa coupon'
        ]);
    }

    // =========================
    // MINI CART HEADER
    // =========================
    public function miniCart()
    {
        $cart = $this->cartService->getCart();

        $count = collect($cart)->count();

        $subtotal = collect($cart)->sum(function ($item) {

            return $item['price'] * $item['quantity'];
        });

        return response()->json([

            'count' => $count,

            'subtotal' => $subtotal,

            'html' => view(
                'frontend.cart.partials.mini-cart',
                compact('cart', 'subtotal')
            )->render()
        ]);
    }

    private function buildCartItem(
        ProductVariant $variant,
        int $quantity,
        bool $selected = true
    ): array {

        return [

            'variant_id' => $variant->id,

            'product_id' => $variant->product_id,

            'product_name' => $variant->product->name,

            'product_slug' => $variant->product->slug,

            'image' => $variant->image
                ?? $variant->product->image,

            'price' => $variant->price,

            'quantity' => $quantity,

            'color' => optional($variant->color)->name,

            'size' => optional($variant->size)->name,

            'stock' => $variant->stock,

            'selected' => $selected,

            'variants' => $variant->product
                ->variants
                ->map(function ($v) {

                    return [

                        'id' => $v->id,

                        'color_id' => $v->color_id,

                        'color_name' => optional($v->color)->name,

                        'size_id' => $v->size_id,

                        'size_name' => optional($v->size)->name,

                        'price' => $v->price,

                        'stock' => $v->stock,

                        'image' => $v->image
                            ?? $v->product->image,
                    ];
                })
                ->values()
                ->toArray()
        ];
    }
}
