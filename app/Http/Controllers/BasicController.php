<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\CustomerCart;
use App\Models\Product;
use App\Models\ShippingAddress;
use App\Models\Size;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BasicController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function loadCartCount() // :GET
    {
        if (Auth::guard('customer')->check()) {
            $cartRecord = CustomerCart::where('customer_id ', Auth::guard('customer')->id())->first();
            $carts = $cartRecord ? json_decode($cartRecord->cart_data, true) : ['items' => []];
        } else {
            $carts = session('cart', ['items' => []]);
        }

        $cartCount = count($carts['items']);

        return response()->json(['status' => 200, 'cartCount' => $cartCount]);
    }

    public function loadCartDropdown() // :GET
    {
        if (Auth::guard('customer')->check()) {
            $cartRecord = CustomerCart::where('customer_id ', Auth::guard('customer')->id())->first();
            $carts = $cartRecord ? json_decode($cartRecord->cart_data, true) : ['items' => []];
        } else {
            $carts = session('cart', ['items' => []]);
        }

        // Tính tổng tiền giỏ hàng
        $subtotal = 0;
        $discountTotal = 0;
        $priceTotal = 0;

        foreach ($carts['items'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $output = '';

        if (isset($carts) && count($carts['items']) > 0) {
            foreach ($carts['items'] as $item) {
                $product = Product::find($item['product_id']);
                $color = Color::find($item['color']);
                $size = Size::find($item['size']);
                $priceTotal += $product->price * $item['quantity'];
                $discountTotal += ($product->price * $product->discount * $item['quantity']) / 100;

                $output .= '
                    <li class="cart-item">
                        <div class="product-image">
                            <a href="' . route('product.details', ['slug' => $product->slug]) . '"><img
                                    src="' . url('uploads/product/' . $product->image) . '"
                                    class="cart-thumb" alt="' . $product->name . '" /></a>
                        </div>
                        <div class="fw-600" style="margin-left: 12px">
                            <a href="' . route('product.details', ['slug' => $product->slug]) . '" style="color: #000; font-weight: 400;">' . shortenText($product->name, 32) . '</a>
                            <div style="display: flex; align-items: center; justify-content: space-between; margin: 18px 0 6px;">
                                <div class="fs-14" style="line-height: 1.4rem;">
                                    <span class="fs-14" style="padding: 0px 8px; border-radius: 50%; margin: 0 6px; background: ' . $color->code . ';"></span>
                                    <span class="fs-14" style="font-weight: 100;">' . $color->name . ' / ' . $size->name . '</span>
                                </div>
                                <div style="display: flex; height: 30px; line-height: 26px; margin-right: 16px;">
                                    <button type="button" class="minus-cart-quantity" data-row_id="' . $item['rowId'] . '" data-product_id="' . $product->id . '"><i class="fa fa-minus"></i></button>
                                    <input class="qty-text quantity-input-cart" type="text" id="1" value="' . $item['quantity'] . '"
                                        aria-label="qty-text" data-row_id="' . $item['rowId'] . '" data-product_id="' . $product->id . '"/>
                                    <button type="button" class="plus-cart-quantity" data-row_id="' . $item['rowId'] . '" data-product_id="' . $product->id . '"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; line-height: 32px; align-items: baseline;">';
                                if ($product->discount > 0) {
                                    $output .= '
                                        <div style="display: flex;">
                                            <span style="color: #fa6338; margin-right: 6px;">' . number_format($item['price'], 0, ',', '.') . '₫</span>
                                            <div class="" style="background: rgb(250 99 56 / 10%); padding: 0 12px; height: 30px;">
                                                <span style="vertical-align: super; font-size: 14px; color: #fa6338">(-' . number_format($product->discount, 0, ',', '.') . '%)</span>
                                            </div>
                                        </div>
                                    ';
                                } else {
                                    $output .= '
                                        <span style="color: var(--dark-gray);">' . number_format($item['price'], 0, ',', '.') . '₫</span>
                                    ';
                                }

                                $output .= '
                                <button type="button" class="remove-item-cart fs-20 fw-500" data-rowid="' . $item['rowId'] . '"><i class="fa fa-trash-alt"></i></button>
                            </div>
                        </div>
                    </li>
                ';
            }

            $output .= '
                <li class="cart-total">
                    <div class="fs-16 alt-font mb-25px">
                        <div style="display: flex; flex-direction: column;">
                            <span class="">Giá bán lẻ: ' . number_format($priceTotal, 0, ',', '.') . '₫</span>
                            <span class="" style="color: #fa6338; margin-bottom: 4px;">Tiết kiệm: ' . number_format($discountTotal, 0, ',', '.') . '₫</span>
                            <span class="">Tổng cộng:
                                <span style="color: #fa6338; font-size: 18px">
                                    ' . number_format($subtotal, 0, ',', '.') . '₫
                                </span>
                            </span>
                        </div>
                    </div>
                    <a href="' . route('cart.index') . '"
                        class="btn btn-large btn-transparent-light-gray border-color-extra-medium-gray">View
                        cart</a>
                    <a href="checkout.html"
                        class="btn btn-large btn-dark-gray btn-box-shadow">Checkout</a>
                </li>
            ';
        } else {
            $output .= '
                <li class="cart-item align-items-center"><i
                        class="fa fa-shopping-cart pe-5px"></i> Giỏ hàng đang rỗng</li>
            ';
        }

        echo $output;
    }

    public function shippingAddressStore() // :POST
    {
        DB::beginTransaction();

        try {
            $data = $this->request->all();

            $data['customer_id'] = Auth::guard('customer')->id();

             // Nếu is_default = true, cập nhật tất cả các bản ghi khác về false
            if ($this->request->has('is_default') && $this->request->is_default) {
                ShippingAddress::where('customer_id', $data['customer_id'])
                ->where('is_default', true)
                ->update(['is_default' => false]);
            }

            // Tạo một địa chỉ giao hàng mới
            ShippingAddress::create($data);

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Thêm thông tin vận chuyển thành công"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi thêm sản phẩm']);
        }
    }

    public function shippingAddressEdit() // :GET
    {
        $id = $this->request->id;
        $shippingAddress = ShippingAddress::find($id);
        return response()->json($shippingAddress);
    }

    public function shippingAddressUpdate() // :PUT
    {
        DB::beginTransaction();

        try {
            $data = $this->request->all();
            $data['customer_id'] = Auth::guard('customer')->id();

            // Loại bỏ shipping_address_id khỏi $data nếu tồn tại
            unset($data['shipping_address_id']);

            // Kiểm tra shipping_address_id hợp lệ
            $shippingAddress = ShippingAddress::where([
                ['customer_id', $data['customer_id']],
                ['id', $this->request->shipping_address_id]
            ])->firstOrFail();

            // Nếu is_default = true, cập nhật tất cả các bản ghi khác về false
            if ($this->request->has('is_default') && $this->request->is_default) {
                ShippingAddress::where('customer_id', $data['customer_id'])
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }

            $shippingAddress->update($data);

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Cập nhật thông tin vận chuyển thành công"]);
        } catch (\Throwable $th) {
            dd($th);
            DB::rollback();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi cập nhật thông tin vận chuyển']);
        }
    }
}
