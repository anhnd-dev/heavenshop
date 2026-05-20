<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Customer;
use App\Models\District;
use App\Models\Province;
use App\Models\ShippingAddress;
use App\Models\ShippingRate;
use App\Models\Size;
use App\Models\Ward;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index() // :GET
    {
        return view('pages.checkout.index');
    }

    public function loadView() // :GET
    {
        $carts = Cart::instance('cart')->content();
        $coupon = Session::get('coupon');
        $shippingMethod = Session::get('shipping_method');

        $customer_id = Auth::guard('customer')->id();
        $shippingAddress = ShippingAddress::where([
            ['customer_id', $customer_id],
            ['is_default', 1]
        ])->first();

        // $province = Province::where('id', $shippingAddress->province_id)->first();
        // $district = District::where('id', $shippingAddress->district_id)->first();
        // $ward = Ward::where('id', $shippingAddress->ward_id)->first();

        // $subtotal = 0;
        // $discount = 0;
        // $discount_value = '';
        // $shippingFee = 0;
        // $shipping_text = '';

        $output = '';

        // $output .= '
        //      <div class="row align-items-start">
        //         <div class="col-lg-8">
        //             <div class="bg-white-transparent p-25px md-pe-15px md-mb-50px xs-mb-35px">';
        // if ($shippingAddress) {
        //     $output .= '
        //                         <span class="fs-22 alt-font fw-600 text-dark-gray mb-20px d-block fw-700" style="text-transform: capitalize; ">Địa chỉ giao hàng</span>
        //                         <div class="shipping-address-cart__body">
        //                             <div class="shipping-address-item">
        //                                 <div class="shipping-address-item__content">
        //                                     <div class="shipping-address-item__header">
        //                                         <span>' . $shippingAddress->first_name . ' ' . $shippingAddress->last_name . '</span>
        //                                         <span>' . $shippingAddress->phone . '</span>
        //                                     </div>
        //                                     <p>' . $shippingAddress->address . '</p>
        //                                     <p>' . $province->name . ', ' . $district->name . ', ' . $ward->name . '</p>
        //                                 </div>
        //                                 <button type="button" class="editIcon btn btn-custom" id="' . $shippingAddress->id . '" data-bs-toggle="modal" data-bs-target="#editShippingAddressModal">
        //                                     Sửa địa chỉ
        //                                 </button>
        //                             </div>
        //                         </div>
        //                     ';
        // } else {
        //     $output .= '
        //                         <span class="fs-24 alt-font fw-600 text-dark-gray mb-20px d-block" style="text-transform: capitalize;">Địa chỉ giao hàng</span>
        //                         <form id="add_shipping_address_form">
        //                             ' . csrf_field() . '
        //                             <div class="row">
        //                                 <div class="col-md-6 mb-20px">
        //                                     <label class="mb-10px">Họ <span class="text-red">*</span></label>
        //                                     <input class="border-radius-4px input-small" name="first_name" type="text" aria-label="first-name"
        //                                         required />
        //                                 </div>
        //                                 <div class="col-md-6 mb-20px">
        //                                     <label class="mb-10px">Tên <span class="text-red">*</span></label>
        //                                     <input class="border-radius-4px input-small" name="last_name" type="text" aria-label="last-name"
        //                                         required />
        //                                 </div>
        //                                 <div class="col-12 mb-20px">
        //                                     <label class="mb-10px">Phone <span class="text-red">*</span></label>
        //                                     <input class="border-radius-4px input-small" name="phone" type="text" aria-label="phone"
        //                                         required />
        //                                 </div>

        //                                 <div class="col-12">
        //                                     <label class="mb-10px">Địa chỉ đường phố <span class="text-red">*</span></label>
        //                                     <textarea class="border-radius-4px textarea-small" name="address" rows="3" cols="3"
        //                                         placeholder="Địa chỉ đường phố, căn hộ, v.v"></textarea>
        //                                 </div>

        //                                 <div class="col-6 mb-20px">
        //                                     <label class="mb-10px" for="state1">Tỉnh <span class="text-red">*</span></label>
        //                                     <select name="province_id" id="province" class="form-select select-small border-radius-4px">
        //                                         <option>Chọn tỉnh/thành phố</option>
        //                                     </select>
        //                                 </div>

        //                                 <div class="col-6 mb-20px">
        //                                     <label class="mb-10px" for="state1">Thành phố <span class="text-red">*</span></label>
        //                                     <select name="district_id" id="district" class="form-select select-small border-radius-4px">
        //                                         <option>Chọn quận/huyện</option>
        //                                     </select>
        //                                 </div>

        //                                 <div class="col-12 mb-20px">
        //                                     <label class="mb-10px">Khu vực <span class="text-red">*</span></label>
        //                                     <select name="ward_id" id="ward" class="form-select select-small border-radius-4px">
        //                                         <option>Chọn phường/xã</option>
        //                                     </select>
        //                                 </div>

        //                                 <div class="col-12 mb-20px">
        //                                     <div class="position-relative terms-condition-box text-start d-flex align-items-center">
        //                                         <label>
        //                                             <input type="checkbox" name="is_default" value="1"
        //                                                 class="check-box align-middle" />
        //                                             <span class="box">Đặt địa chỉ mặc định</span>
        //                                             <a class="accordion-toggle" data-bs-toggle="collapse"
        //                                                 data-bs-parent="#accordion1" href="#collapseFour"></a>
        //                                         </label>
        //                                     </div>
        //                                 </div>

        //                                 <div class="col-4">
        //                                     <button type="submit" class="btn btn-dark-gray btn-large btn-switch-text btn-round-edge btn-box-shadow w-100 mt-30px">
        //                                         <span>
        //                                             <span class="btn-double-text" data-text="Lưu" id="add_shipping_address_btn">Lưu</span>
        //                                         </span>
        //                                     </button>
        //                                 </div>
        //                             </div>
        //                         </form>
        //                     ';
        // }
        // $output .= '
        //             </div>

        //             <div class="bg-white-transparent p-25px md-pe-15px md-mb-50px xs-mb-35px">';
        // if ($shippingAddress) {
        //     $output .= '
        //                         <span class="fs-22 alt-font text-dark-gray mb-20px d-block fw-700" style="text-transform: capitalize;">Chi tiết đơn hàng</span>
        //                         <div class="shipping-address-cart__body">
        //                             <div class="shipping-address-item">
        //                                 <div class="shipping-address-item__content">
        //                                     <div class="shipping-address-item__header">
        //                                         <span>Nguyễn Đức Anh</span>
        //                                         <span>01288032567</span>
        //                                     </div>
        //                                     <p>147 Hà Đông</p>
        //                                     <p>478 Hà Đông, Hà Nội</p>
        //                                 </div>
        //                                 <button class="btn btn-custom">
        //                                     Sửa địa chỉ
        //                                 </button>
        //                             </div>
        //                         </div>
        //                     ';
        // }
        // $output .= '
        //             </div>
        //         </div>

        //         <div class="col-lg-4">
        //             <div class="bg-white-transparent border-radius-6px p-25px lg-p-25px your-order-box">
        //                 <span class="fs-22 alt-font fw-600 text-dark-gray mb-5px d-block fw-700">Tóm tắt đơn hàng</span>
        //                 <table class="w-100 total-price-table your-order-table">
        //                     <tbody>
        //                         <tr>
        //                             <th class="w-60 lg-w-55 xs-w-50 fw-600 text-dark-gray alt-font">
        //                                 Product
        //                             </th>
        //                             <td class="fw-600 text-dark-gray alt-font">Total</td>
        //                         </tr>';

        // if ($carts->count() > 0) {
        //     foreach ($carts as $cart) {
        //         $color = Color::find($cart->options->color);
        //         $size = Size::find($cart->options->size);
        //         $subtotal += $cart->qty * $cart->price;

        //         $output .= '
        //                                     <tr class="product">
        //                                         <td class="product-thumbnail">
        //                                             <a href="demo-jewellery-store-single-product.html"
        //                                                 class="text-dark-gray fw-500 d-block lh-initial">' . shortenText($cart->name, 54) . ' x ' . $cart->qty . '</a>
        //                                             <span class="fs-14" style="padding: 0px 8px; border-radius: 50%; margin: 0 6px; background: ' . $color->code . ';"></span>
        //                                             <span class="fs-14">' . $color->name . ' / ' . $size->name . '</span>
        //                                         </td>
        //                                         <td class="product-price text-dark-gray fw-600" data-title="Price">' . number_format($cart->price, 0, ',', '.') . '₫</td>
        //                                     </tr>
        //                                 ';
        //     }
        // } else {
        //     $output .= '<tr><td colspan="2" class="text-center">Your cart is empty.</td></tr>';
        // }

        // // Apply coupon discount if available
        // if ($coupon) {
        //     if ($coupon['discount_type'] == 'percentage') {
        //         $discount_value = '-' . $coupon['discount_value'] . '%';
        //         $discount = ($coupon['discount_value'] / 100) * $subtotal;
        //     } else if ($coupon['discount_type'] == 'fixed') {
        //         $discount_value = '';
        //         $discount = $coupon['discount_value'];
        //     }

        //     // Ensure discount doesn't exceed subtotal
        //     $discount = min($discount, $subtotal);
        //     $borderCoupon = '';
        // } else {
        //     $borderCoupon = 'border: 1px solid #8b3232;';
        //     $discount_value = 'No Coupon';
        // }

        // // Calculate the shipping fee based on the selected method
        // if ($shippingMethod) {
        //     $shippingRate = ShippingRate::find($shippingMethod);

        //     if ($shippingRate) {
        //         if ($shippingRate->is_freeship === 1) {
        //             $shipping_text = 'Free';
        //         } else {
        //             $shipping_text = '';
        //         }

        //         $shippingFee = $shippingRate->rate;
        //     }
        // }

        // $total = $subtotal - $discount + $shippingFee;

        // $output .= '
        //                         <tr>
        //                             <th class="w-50 fw-600 text-dark-gray alt-font">
        //                                 Subtotal
        //                             </th>
        //                             <td class="text-dark-gray fw-600">' . number_format($subtotal, 0, ',', '.') . '₫</td>
        //                         </tr>
        //                         <tr class="shipping">
        //                             <th class="fw-600 text-dark-gray alt-font">
        //                                 Coupon
        //                                 <span style="' . $borderCoupon . ' padding: 0 4px; margin-left: 4px; border-radius: 4px; font-size: 14px;">' . $discount_value . '</span>
        //                             </th>
        //                             <td class="text-dark-gray fw-600" style="color: #e51a1a;">' . number_format($discount, 0, ',', '.') . '₫</td>
        //                         </tr>
        //                         <tr class="shipping">
        //                             <th class="fw-600 text-dark-gray alt-font">
        //                                 Shipping
        //                                 <span style="border: 1px solid #8b3232; padding: 0 4px; margin-left: 4px; border-radius: 4px; font-size: 14px;">' . $shipping_text . '</span>
        //                             </th>
        //                             <td class="text-dark-gray fw-600" style="color: #e51a1a;">' . number_format($shippingFee, 0, ',', '.') . '₫</td>
        //                         </tr>
        //                         <tr class="total-amount">
        //                             <th class="fw-600 text-dark-gray alt-font">Total</th>
        //                             <td data-title="Total">
        //                                 <h6 class="d-block fw-700 mb-0 text-dark-gray alt-font" style="font-size: 24px;">
        //                                     ' . number_format($total, 0, ',', '.') . '₫
        //                                 </h6>
        //                             </td>
        //                         </tr>
        //                     </tbody>
        //                 </table>

        //                 <div
        //                     class="p-40px lg-p-25px bg-white border-radius-6px box-shadow-large mt-10px mb-30px sm-mb-25px checkout-accordion">
        //                     <div class="w-100" id="accordion-style-05">
        //                         <div class="heading active-accordion">
        //                             <label class="mb-5px">
        //                                 <input class="d-inline w-auto me-5px mb-0 p-0" type="radio"
        //                                     name="payment-option" checked="checked" />
        //                                 <span class="d-inline-block text-dark-gray fw-500">Direct bank transfer</span>
        //                                 <a class="accordion-toggle" data-bs-toggle="collapse"
        //                                     data-bs-parent="#accordion-style-05" href="#style-5-collapse-1"></a>
        //                             </label>
        //                         </div>
        //                         <div id="style-5-collapse-1" class="collapse show" data-bs-parent="#accordion-style-05">
        //                             <div class="p-25px bg-very-light-gray mt-20px mb-20px fs-14 lh-24">
        //                                 Make your payment directly into our bank account. Please
        //                                 use your Order ID as the payment reference. Your order
        //                                 will not be shipped until the funds have cleared in our
        //                                 account.
        //                             </div>
        //                         </div>

        //                         <div class="heading active-accordion">
        //                             <label class="mb-5px">
        //                                 <input class="d-inline w-auto me-5px mb-0 p-0" type="radio"
        //                                     name="payment-option" />
        //                                 <span class="d-inline-block text-dark-gray fw-500">Check payments</span>
        //                                 <a class="accordion-toggle" data-bs-toggle="collapse"
        //                                     data-bs-parent="#accordion-style-05" href="#style-5-collapse-2"></a>
        //                             </label>
        //                         </div>
        //                         <div id="style-5-collapse-2" class="collapse" data-bs-parent="#accordion-style-05">
        //                             <div class="p-25px bg-very-light-gray mt-20px mb-20px fs-14 lh-24">
        //                                 Please send a check to store name, store street, store
        //                                 town, store state / county, store postcode.
        //                             </div>
        //                         </div>

        //                         <div class="heading active-accordion">
        //                             <label class="mb-5px">
        //                                 <input class="d-inline w-auto me-5px mb-0 p-0" type="radio"
        //                                     name="payment-option" />
        //                                 <span class="d-inline-block text-dark-gray fw-500">Cash on delivery</span>
        //                                 <a class="accordion-toggle" data-bs-toggle="collapse"
        //                                     data-bs-parent="#accordion-style-05" href="#style-5-collapse-3"></a>
        //                             </label>
        //                         </div>
        //                         <div id="style-5-collapse-3" class="collapse" data-bs-parent="#accordion-style-05">
        //                             <div class="p-25px bg-very-light-gray mt-20px mb-20px fs-14 lh-24">
        //                                 Pay with cash upon delivery.
        //                             </div>
        //                         </div>

        //                         <div class="heading active-accordion">
        //                             <label class="mb-5px">
        //                                 <input class="d-inline w-auto me-5px mb-0 p-0" type="radio"
        //                                     name="payment-option" />
        //                                 <span class="d-inline-block text-dark-gray fw-500">PayPal
        //                                     <img src="images/paypal-logo.jpg" class="w-120px ms-10px" alt /></span>
        //                                 <a class="accordion-toggle" data-bs-toggle="collapse"
        //                                     data-bs-parent="#accordion-style-05" href="#style-5-collapse-4"></a>
        //                             </label>
        //                         </div>
        //                         <div id="style-5-collapse-4" class="collapse" data-bs-parent="#accordion-style-05">
        //                             <div class="p-25px bg-very-light-gray mt-20px fs-14 lh-24">
        //                                 You can pay with your credit card if you don\'t have a
        //                                 PayPal account.
        //                             </div>
        //                         </div>
        //                     </div>
        //                 </div>

        //                 <p class="fs-14 lh-24">
        //                     Your personal data will be used to process your order, support
        //                     your experience throughout this website, and for other purposes
        //                     described in our
        //                     <a class="text-decoration-line-bottom text-dark-gray fw-500" href="#">privacy
        //                         policy.</a>
        //                 </p>

        //                 <div class="position-relative terms-condition-box text-start d-flex align-items-center">
        //                     <label>
        //                         <input type="checkbox" name="terms_condition" value="1"
        //                             class="check-box align-middle" />
        //                         <span class="box fs-14 lh-28">I have agree to the website
        //                             <a href="#" class="text-decoration-line-bottom text-dark-gray fw-500">terms and
        //                                 conditions.</a></span>
        //                     </label>
        //                 </div>

        //                 <a href="#"
        //                     class="btn btn-dark-gray btn-large btn-switch-text btn-round-edge btn-box-shadow w-100 mt-30px">
        //                     <span>
        //                         <span class="btn-double-text" data-text="Place order">Place order</span>
        //                     </span>
        //                 </a>

        //             </div>
        //         </div>

        //         <div class="modal fade" id="editShippingAddressModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        //             <div class="modal-dialog modal-lg" id="checkout-modal">
        //                 <div class="modal-content" style="padding: 40px; color: #000">
        //                     <div class="modal-header">
        //                         <h5 class="modal-title fs-18" style="text-transform: uppercase;">Địa chỉ giao hàng</h5>
        //                         <button type="button" style="border: none; background: none" class="close fs-24" data-bs-dismiss="modal" aria-label="Close">
        //                             <span aria-hidden="true">&times;</span>
        //                         </button>
        //                     </div>

        //                     <div class="modal-body">
        //                          <div class="container">
        //                             <div class="row g-0 justify-content-center">
        //                                 <form id="update_shipping_address_form">
        //                                     ' . csrf_field() . '
        //                                     ' . method_field('PUT') . '
        //                                     <input type="hidden" name="shipping_address_id" id="shipping_address_id">
        //                                     <div class="row">
        //                                         <div class="col-md-6 mb-20px">
        //                                             <label class="mb-10px">Họ <span class="text-red">*</span></label>
        //                                             <input class="border-radius-4px input-small" name="first_name" id="first_name" type="text" aria-label="first-name"
        //                                                 required />
        //                                         </div>
        //                                         <div class="col-md-6 mb-20px">
        //                                             <label class="mb-10px">Tên <span class="text-red">*</span></label>
        //                                             <input class="border-radius-4px input-small" name="last_name" id="last_name" type="text" aria-label="last-name"
        //                                                 required />
        //                                         </div>
        //                                         <div class="col-12 mb-20px">
        //                                             <label class="mb-10px">Phone <span class="text-red">*</span></label>
        //                                             <input class="border-radius-4px input-small" name="phone" id="phone" type="text" aria-label="phone"
        //                                                 required />
        //                                         </div>

        //                                         <div class="col-12">
        //                                             <label class="mb-10px">Địa chỉ đường phố <span class="text-red">*</span></label>
        //                                             <textarea class="border-radius-4px textarea-small" name="address" id="address" rows="3" cols="3"
        //                                                 placeholder="Địa chỉ đường phố, căn hộ, v.v"></textarea>
        //                                         </div>

        //                                         <div class="col-6 mb-20px">
        //                                             <label class="mb-10px" for="state1">Tỉnh <span class="text-red">*</span></label>
        //                                             <select name="province_id" id="uprovince" class="form-select select-small border-radius-4px">
        //                                                 <option>Chọn tỉnh/thành phố</option>
        //                                             </select>
        //                                         </div>

        //                                         <div class="col-6 mb-20px">
        //                                             <label class="mb-10px" for="state1">Thành phố <span class="text-red">*</span></label>
        //                                             <select name="district_id" id="udistrict" class="form-select select-small border-radius-4px">
        //                                                 <option>Chọn quận/huyện</option>
        //                                             </select>
        //                                         </div>

        //                                         <div class="col-12 mb-20px">
        //                                             <label class="mb-10px">Khu vực <span class="text-red">*</span></label>
        //                                             <select name="ward_id" id="uward" class="form-select select-small border-radius-4px">
        //                                                 <option>Chọn phường/xã</option>
        //                                             </select>
        //                                         </div>

        //                                         <div class="col-12 mb-20px">
        //                                             <div class="position-relative terms-condition-box text-start d-flex align-items-center">
        //                                                 <label>
        //                                                     <input type="checkbox" name="is_default" value="1"
        //                                                         class="check-box align-middle" />
        //                                                     <span class="box">Đặt địa chỉ mặc định</span>
        //                                                     <a class="accordion-toggle" data-bs-toggle="collapse"
        //                                                         data-bs-parent="#accordion1" href="#collapseFour"></a>
        //                                                 </label>
        //                                             </div>
        //                                         </div>

        //                                         <div class="col-4">
        //                                             <button type="submit" class="btn btn-dark-gray btn-large btn-switch-text btn-round-edge btn-box-shadow w-100 mt-30px">
        //                                                 <span>
        //                                                     <span class="btn-double-text" data-text="Cập nhật" id="update_shipping_address_btn">Cập nhật</span>
        //                                                 </span>
        //                                             </button>
        //                                         </div>
        //                                     </div>
        //                                 </form>
        //                             </div>
        //                         </div>
        //                     </div>
        //                 </div>
        //             </div>
        //         </div>
        //     </div>
        // ';

        echo $output;
    }

    public function loadProvince() // :GET
    {
        $results = Province::all();
        return response()->json(['results' => $results]);
    }

    public function loadDistrict() // :GÊT
    {
        $province_id = $this->request->province_id;
        $results = District::where('province_id', $province_id)->get();
        return response()->json(['results' => $results]);
    }

    public function loadWard() // :GET
    {
        $district_id = $this->request->district_id;
        $results = Ward::where('district_id', $district_id)->get();
        return response()->json(['results' => $results]);
    }
}
