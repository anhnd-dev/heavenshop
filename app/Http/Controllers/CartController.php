<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Coupon;
use App\Models\CustomerCart;
use App\Models\Frontend;
use App\Models\Product;
use App\Models\ShippingRate;
use App\Models\Size;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index() // :GET
    {
        return view('pages.cart.index');
    }

    public function loadView() // :GET
    {
        // Kiểm tra người dùng đã đăng nhập hay chưa
        if (Auth::guard('customer')->check()) {
            // Lấy dữ liệu giỏ hàng từ bảng `customer_carts`
            $cartRecord = CustomerCart::where('customer_id ', Auth::guard('customer')->id())->first();

            // Nếu có dữ liệu, chuyển đổi từ JSON sang mảng
            $carts = $cartRecord ? json_decode($cartRecord->cart_data, true) : ['items' => []];
        } else {
            // Nếu người dùng chưa đăng nhập, lấy thông tin từ session
            $carts = session('cart', ['items' => []]);
        }

        $setting = Frontend::where('data_key', 'setting.data')->first();
        $settingData = json_decode($setting->data_value);
        $shippingFreeThreshold = $settingData->shipping_free_threshold;

        $minThresholdCoupon = Coupon::where('is_active', true)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->orderBy('threshold', 'asc')
            ->first();

        $minThreshold = $minThresholdCoupon ? $minThresholdCoupon->threshold : 0;
        $discountType = $minThresholdCoupon ? $minThresholdCoupon->discount_type : '';
        $discountValue = $minThresholdCoupon ? $minThresholdCoupon->discount_value : 0;
        $displayDiscountValue = $discountType === 'percentage' ? $discountValue . '%' : number_format($discountValue, 0, ',', '.') . '₫';

        $freeShippingMethod = ShippingRate::where([
            ['is_freeship', true],
            ['is_active', true]
        ])->first();

        $shippingMethods = ShippingRate::where([
            ['is_active', true],
            ['is_freeship', false],
        ])->get();

        // Tính tổng tiền giỏ hàng
        $subtotal = 0;

        foreach ($carts['items'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $output = '';

        if (isset($carts) && count($carts['items']) > 0) {
            $output .= '
                <div class="row align-items-start">
                    <div class="col-lg-8 pe-50px md-pe-15px md-mb-50px xs-mb-35px">
                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <div class="bg-very-light-gray border-radius-6px p-15px" style="display: flex; justify-content: space-between; align-items: center; border: .5px solid rgba(25, 128, 85, .3); background: #f5fcfb;">
                                    <span style="margin-right: 14px;">
                                        <i class="fa-solid fa-truck-fast"></i>
                                    </span>
                                    <div style="color: #000;">
                                        <p style="display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 4px; font-weight:700;">
                                            <span>Phí vận chuyển</span>
                                            <a href="" style="color: #000;">tặng thêm <i class="fa fa-chevron-right"></i></a>
                                        </p>
                                        <ul id="shipping-info">';
            if ($subtotal >= $shippingFreeThreshold) {
                $output .= '
                                                    <li style="font-size: 14px; line-height: 18px;">
                                                        Bạn đã đủ điều kiện để được giao hàng tiêu chuẩn MIỄN PHÍ!
                                                    </li>
                                                ';
            } else {
                $remainingAmountForFreeShipping = $shippingFreeThreshold - $subtotal;

                $output .= '
                                                    <li style="font-size: 14px; line-height: 18px;">
                                                        Mua thêm <strong style="font-weight:700;">' . number_format($remainingAmountForFreeShipping, 0, ',', '.') . '₫</strong> hoặc nhiều hơn để thưởng <strong>giao hàng tiêu chuẩn</strong> MIỄN PHÍ!
                                                    </li>
                                                ';
            }

            $output .= '</ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="bg-very-light-gray border-radius-6px p-15px" style="display: flex; justify-content: space-between; align-items: center; border: .5px solid rgba(25, 128, 85, .3); background: #f5fcfb;">
                                    <span id="progress-icon" style="margin-right: 14px;">
                                        <i class="fa-solid fa fa-gift"></i>
                                    </span>
                                    <div style="color: #000;">
                                        <p style="display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 4px; font-weight:700;">
                                            <span>Chương trình khuyến mại</span>
                                            <a href="" style="color: #000;">tặng thêm <i class="fa fa-chevron-right"></i></a>
                                        </p>
                                        <ul>
                                            <li id="progress-message" style="font-size: 14px; line-height: 18px;">
                                                Mua thêm <strong id="remaining-amount" style="font-weight:700; color: #c44a01">' . number_format($minThreshold, 0, ',', '.') . '₫</strong> để kiếm phiếu giảm giá <span style="color: #c44a01"">' . $displayDiscountValue . '</span> MIỄN PHÍ!
                                            </li>
                                        </ul>
                                        <div class="progress-container">
                                            <div class="progress-bar" id="progress-bar" style="width: 0%"></div>
                                            <span id="progress-text">0%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="bg-very-light-gray border-radius-6px p-15px" style="display: flex; justify-content: space-between; align-items: center;">
                                    <div class="">
                                        <input type="checkbox" id="select-all" style="width: auto;">
                                        <label for="" class="text-uppercase" style="color: var(--dark-gray);">Tất cả mặt hàng (' . count($carts['items']) . ')</label>
                                    </div>
                                    <button type="button" id="delete-selected" class="hidden fs-20 fw-500"><i class="fa fa-trash-alt"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">';
            foreach ($carts['items'] as $item) {
                $product = Product::find($item['product_id']);
                $color = Color::find($item['color']);
                $size = Size::find($item['size']);

                $output .= '
                                        <div class="bg-very-light-gray border-radius-6px p-15px mb-1">
                                            <div style="display: flex;">
                                                <div style="display: flex;">
                                                    <input type="checkbox" class="select-item" data-row_id="' . $item['rowId'] . '" style="width: auto">
                                                    <a href="' . route('product.details', ['slug' => $product->slug]) . '" style="margin: 0 12px;">
                                                        <div style="position: relative;">
                                                            <img class="cart-product-image" src="' . url('uploads/product/' . $product->image) . '" alt width="120px"/>
                                                            <span style="position: absolute; bottom: 0; left: 0; width: 100%; background: rgba(0, 0, 0, 0.7); color: #fff; text-align: center; font-size: 12px; line-height: 24px;">
                                                                còn lại ' . $product->quantity . '
                                                            </span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="" style="width: 100%; display: flex; justify-content: space-between; flex-direction: column;">
                                                    <div class="">
                                                        <a href="' . route('product.details', ['slug' => $product->slug]) . '"
                                                        class="text-dark-gray fw-500 d-block lh-initial" style="font-size: 14px;">' . shortenText($product->name, 90) . '</a>
                                                        <span class="fs-14" style="padding: 0px 8px; border-radius: 50%; margin: 0 6px; background: ' . $color->code . ';"></span>
                                                        <span class="fs-14">' . $color->name . ' / ' . $size->name . '</span>
                                                    </div>

                                                    <div class="" style="display: flex; justify-content: space-between;">
                                                        <div style="display: flex;">';
                if ($product->discount > 0) {
                    $discountAmount = ($product->price * $product->discount) / 100;

                    $output .= '
                                                                    <span class="price" style="color: #fa6338; margin-right: 6px;">' . number_format($item['price'], 0, ',', '.') . '₫</span>
                                                                    <del style="font-size: 14px;  margin-right: 6px;">' . number_format($product->price, 0, ',', '.') . '₫</del>

                                                                    <div class="" style="display: flex; background: rgb(250 99 56 / 10%); padding: 0 6px;">
                                                                        <span style="vertical-align: super; font-size: 14px; color: #fa6338">-' . number_format($product->discount, 0, ',', '.') . '%</span>
                                                                        <div class="dropdown-icon">
                                                                            <span></span>
                                                                            <span></span>
                                                                            <div class="dropdown-content">
                                                                                <div>
                                                                                    <span style="font-weight: 700;">Giá ưu đãi</span>
                                                                                    <span style="color: #fa6338;">' . number_format($item['price'], 0, ',', '.') . '₫</span>
                                                                                </div>
                                                                                <span></span>
                                                                                <div>
                                                                                    <span>Giá bán lẻ: </span>
                                                                                    <span style="font-weight: 700;">' . number_format($product->price, 0, ',', '.') . '₫</span>
                                                                                </div>
                                                                                <div>
                                                                                    <span style="margin-right: 12px;">Chương trình khuyến mãi: </span>
                                                                                    <span style="color: #fa6338;">-' . number_format($discountAmount, 0, ',', '.') . '₫</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                ';
                } else {
                    $output .= '
                                                                    <span style="color: var(--dark-gray);">' . number_format($item['price'], 0, ',', '.') . '₫</span>
                                                                ';
                }
                $output .= '
                                                        </div>
                                                        <div style="display: flex;">
                                                            <div style="display: flex; height: 30px; line-height: 26px; margin-right: 16px;">
                                                                <input type="hidden" id="quantity-stock" value="' . $product->quantity . '">
                                                                <button type="button" class="minus-cart-quantity" data-row_id="' . $item['rowId'] . '" data-product_id="' . $product->id . '"><i class="fa fa-minus"></i></button>
                                                                <input class="qty-text quantity-input-cart" type="text" id="1" value="' . $item['quantity'] . '"
                                                                    aria-label="qty-text" data-row_id="' . $item['rowId'] . '" data-product_id="' . $product->id . '"/>
                                                                <button type="button" class="plus-cart-quantity" data-row_id="' . $item['rowId'] . '" data-product_id="' . $product->id . '"><i class="fa fa-plus"></i></button>
                                                            </div>
                                                            <div style="display: flex;">
                                                                <button type="button" class="remove-item-cart fs-20 fw-500" data-row_id="' . $item['rowId'] . '"><i class="fa fa-trash-alt"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ';
            }
            $output .= '
                            </div>
                        </div>

                        <div class="row mt-20px">
                            <div class="col-xl-6 col-xxl-6 col-md-6"></div>
                            <div class="col-xl-6 col-xxl-6 col-md-6 text-center text-md-end sm-mt-15px">
                                <button type="button" class="remove-all-cart btn btn-small border-1 btn-round-edge btn-transparent-light-gray text-transform-none me-15px lg-me-5px">Empty
                                    cart</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="bg-very-light-gray border-radius-6px p-50px xl-p-30px lg-p-25px">
                            <span class="fs-26 alt-font fw-600 text-dark-gray mb-5px d-block">Tóm tắt đơn hàng</span>
                            <table class="w-100 total-price-table">
                                <tbody>
                                    <tr>
                                        <th class="w-45 fw-600 text-dark-gray alt-font">
                                            Tổng phụ
                                        </th>
                                        <td class="text-dark-gray fw-600 subtotal-amount">0₫</td>
                                    </tr>
                                    <tr class="discount hidden">
                                        <th class="fw-600 text-dark-gray alt-font">Giảm giá</th>
                                        <td data-title="Discount">
                                            <span class="text-danger">0₫</span>
                                        </td>
                                    </tr>
                                    <tr class="calculate-shipping">
                                        <th colspan="2" class="fw-500">
                                            <a class="d-flex align-items-center calculate-shipping-title accordion-toggle"
                                                data-bs-toggle="collapse" href="#shipping-accordion" aria-expanded="false">
                                                <p class="fw-600 w-100 mb-0 text-dark-gray">
                                                    Phương thức vận chuyển
                                                </p>
                                                <i
                                                    class="feather icon-feather-chevron-down text-dark-gray icon-small align-middle"></i>
                                            </a>
                                            <div id="shipping-accordion" class="address-block collapse">
                                                <div class="mt-15px">
                                                    <select class="form-select select-small mb-15px" id="shipping-method" '
                . ($shippingMethods->isEmpty() ? 'disabled' : '') . '>';

            if ($shippingMethods->isEmpty()) {
                $output .= '<option value="">Không có phương thức vận chuyển khả dụng</option>';
            } else {
                $output .= '<option value="">Chọn phương thức vận chuyển</option>';

                foreach ($shippingMethods as $method) {
                    $output .= '<option value="' . $method->id . '">' . $method->name . ' - ' . number_format($method->rate, 0, ',', '.') . '₫</option>';
                }
            }

            if ($freeShippingMethod) {
                $freeShippingMethodId = $freeShippingMethod->id;
            } else {
                $freeShippingMethodId = '';
            }


            $output .= '</select>

                                                    <span id="free-shipping" data-shipping_method=' . $freeShippingMethodId . ' style="display: none; font-size: 14px; color: #6000ff; align-items: center;"><i class="fa fa-question-circle" style="margin-right: 4px"></i> Miễn phí vận chuyển</span>
                                                </div>
                                            </div>
                                        </th>
                                        <tr class="shipping hidden">
                                            <th class="fw-600 text-dark-gray alt-font">Phí vận chuyển: </th>
                                            <td data-title="shipping">
                                                <span class="text-danger shipping-fee">0₫</span>
                                                <span style="display: block; font-size: 14px; margin-left: calc(100% - 270px);">Thời gian giao hàng: <strong class="shipping-time">Không rõ</strong></span>
                                            </td>
                                        </tr>
                                    </tr>
                                    <tr id="total-amount">
                                        <th class="fw-600 text-dark-gray alt-font pb-0">Tổng cộng</th>
                                        <td class="pb-0" data-title="Total">
                                            <h6 class="d-block fw-700 mb-0 text-dark-gray alt-font total-amount">
                                                0₫
                                            </h6>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <a href="javascript:void(0);"
                                class="btn btn-dark-gray btn-large btn-switch-text btn-round-edge btn-box-shadow w-100 mt-25px" onclick="checkAuthAndProceedToCheckout()">
                                <span>
                                    <input type="hidden" id="subtotalAfter">
                                    <span class="btn-double-text" data-text="Thanh toán ngay">Thanh toán ngay</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="loginModalLabel">Login</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline: none; background: none; border: none; font-size: 30px;">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                 <div class="container">
                                    <div class="row g-0 justify-content-center">
                                        <div class="col-xl-4 col-lg-5 col-md-10 contact-form-style-04 md-mb-50px">
                                            <span class="fs-26 xs-fs-24 alt-font fw-600 text-dark-gray mb-20px d-block">Member login</span>
                                            <form action="' . route('buyer.auth.login') . '" method="post">
                                                ' . csrf_field() . '
                                                <label class="text-dark-gray mb-10px fw-500">Username or email address<span
                                                        class="text-red">*</span></label>
                                                <input class="mb-20px bg-very-light-gray form-control required" type="email" name="email"
                                                    placeholder="Enter your email" />
                                                <label class="text-dark-gray mb-10px fw-500">Password<span class="text-red">*</span></label>
                                                <input class="mb-20px bg-very-light-gray form-control required" type="password" name="password"
                                                    placeholder="Enter your password" />
                                                <div class="position-relative terms-condition-box text-start d-flex align-items-center mb-20px">
                                                    <label>
                                                        <input type="checkbox" name="terms_condition" id="terms_condition" value="1"
                                                            class="terms-condition check-box align-middle required" />
                                                        <span class="box fs-14">Remember me</span>
                                                    </label>
                                                    <a href="#" class="fs-14 text-dark-gray fw-500 text-decoration-line-bottom ms-auto">Forget
                                                        your password?</a>
                                                </div>
                                                <button class="btn btn-medium btn-round-edge btn-dark-gray btn-box-shadow w-100"
                                                    type="submit">
                                                    Login
                                                </button>
                                                <div class="form-results mt-20px d-none"></div>
                                            </form>
                                        </div>

                                        <div class="col-lg-6 col-md-10 offset-xl-2 offset-lg-1 p-6 box-shadow-extra-large border-radius-6px"
                                            <span class="fs-26 xs-fs-24 alt-font fw-600 text-dark-gray mb-20px d-block">Create an account</span>

                                            <form action="' . route('buyer.auth.register') . '" method="post">
                                                ' . csrf_field() . '

                                                <label class="text-dark-gray mb-10px fw-500">Fullname <span class="text-red">*</span></label>
                                                <input class="mb-20px bg-very-light-gray form-control required" name="full_name" type="text"
                                                    placeholder="Enter your fullname" />
                                                <label class="text-dark-gray mb-10px fw-500">Username<span class="text-red">*</span></label>
                                                <input class="mb-20px bg-very-light-gray form-control required" name="user_name" type="text"
                                                    placeholder="Enter your username" />
                                                <label class="text-dark-gray mb-10px fw-500">Email<span class="text-red">*</span></label>
                                                <input class="mb-20px bg-very-light-gray form-control required" type="email" name="email"
                                                    placeholder="Enter your email" />
                                                <label class="text-dark-gray mb-10px fw-500">Phone<span class="text-red">*</span></label>
                                                <input class="mb-20px bg-very-light-gray form-control required" type="text" name="phone"
                                                    placeholder="Enter your phone" />
                                                <label class="text-dark-gray mb-10px fw-500">Address<span class="text-red">*</span></label>
                                                <input class="mb-20px bg-very-light-gray form-control required" type="text" name="address"
                                                    placeholder="Enter your address" />
                                                <label class="text-dark-gray mb-10px fw-500">Password<span class="text-red">*</span></label>
                                                <input class="mb-20px bg-very-light-gray form-control required" type="password" name="password"
                                                    placeholder="Enter your password" />
                                                <span class="fs-13 lh-22 w-90 lg-w-100 md-w-90 sm-w-100 d-block mb-30px">Your personal data will be
                                                    used to support your experience
                                                    throughout this website, to manage access to your account, and
                                                    for other purposes described in our
                                                    <a href="#" class="text-dark-gray text-decoration-line-bottom fw-500">privacy
                                                        policy.</a></span>
                                                <button class="btn btn-medium btn-round-edge btn-dark-gray btn-box-shadow w-100"
                                                    type="submit">
                                                    Register
                                                </button>
                                                <div class="form-results mt-20px d-none"></div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ';
        } else {
            $output .= '
                <div class="row cart-empty">
                    <div class="col-md-12 text-center">
                        <i class="fa fa-shopping-cart"></i>
                        <h2>GIỎ HÀNG CỦA BẠN ĐANG TRỐNG</h2>
                        <h5>đăng nhập để xem giỏ hàng của bạn và bắt đầu mua sắm</h5>
                        <div class="cart-empty__wrap">
                            <a href="' . route('shop') . '" class="btn btn-dark mt-3">ĐĂNG NHẬP / ĐĂNG KÝ</a>
                            <a href="' . route('shop') . '" class="btn btn-light mt-1">MUA NGAY</a>
                        </div>
                    </div>
                </div>
            ';
        }

        echo $output;
    }

    public function updateQuantity() // :GET
    {
        $product = Product::find($this->request->productId);

        if ($product) {
            // Kiểm tra số lượng sản phẩm còn trong kho
            if ($product->quantity >= $this->request->quantity) {

                if (Auth::guard('customer')->check()) {
                    // Người dùng đã đăng nhập -> cập nhật giỏ hàng trong cơ sở dữ liệu
                    $customerCart = CustomerCart::where('customer_id', Auth::guard('customer')->id())->first();

                    if ($customerCart) {
                        $cartData = json_decode($customerCart->cart_data, true) ?? ['items' => []];

                        // Tìm sản phẩm trong giỏ hàng theo rowId
                        foreach ($cartData['items'] as &$cartItem) {
                            if ($cartItem['rowId'] === $this->request->rowId) {
                                // Cập nhật số lượng sản phẩm
                                $cartItem['quantity'] = $this->request->quantity;
                                $customerCart->cart_data = json_encode($cartData);
                                $customerCart->save();

                                return response()->json(['status' => 200, 'message' => 'Số lượng đã được cập nhật']);
                            }
                        }

                        return response()->json(['status' => 404, 'message' => 'Không tìm thấy sản phẩm trong giỏ hàng']);
                    } else {
                        return response()->json(['status' => 404, 'message' => 'Không tìm thấy giỏ hàng']);
                    }
                } else {
                    // Người dùng chưa đăng nhập -> cập nhật giỏ hàng trong session
                    $cart = session()->get('cart', ['items' => []]);

                    // Tìm sản phẩm trong giỏ hàng theo rowId
                    foreach ($cart['items'] as &$cartItem) {
                        if ($cartItem['rowId'] === $this->request->rowId) {
                            // Cập nhật số lượng sản phẩm
                            $cartItem['quantity'] = $this->request->quantity;
                            session()->put('cart', $cart);

                            return response()->json(['status' => 200, 'message' => 'Số lượng đã được cập nhật']);
                        }
                    }

                    return response()->json(['status' => 404, 'message' => 'Không tìm thấy sản phẩm trong giỏ hàng']);
                }
            } else {
                return response()->json(['status' => 500, 'message' => 'Số lượng trong kho hiện chỉ còn ' . $product->quantity . ' sản phẩm']);
            }
        } else {
            return response()->json(['status' => 500, 'message' => 'Không tìm thấy sản phẩm']);
        }
    }

    public function applyCoupon()
    {
        $subtotal = $this->request->input('subtotal');

        // Lấy mã giảm giá tốt nhất dựa trên subtotal
        $bestCoupon = Coupon::where('is_active', true)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->where('threshold', '<=', $subtotal)
            ->orderBy('threshold', 'desc')
            ->first();

        if ($bestCoupon) {
            // Trả về mã giảm giá tốt nhất tìm được
            return response()->json([
                'status' => 200,
                'message' => 'Mã giảm giá đã được áp dụng thành công.',
                'coupon' => $bestCoupon,
            ]);
        } else {
            // Tìm ngưỡng tiếp theo nếu không có mã giảm giá phù hợp
            $nextCoupon = Coupon::where('is_active', true)
                ->where('start_date', '<=', Carbon::now())
                ->where('end_date', '>=', Carbon::now())
                ->where('threshold', '>', $subtotal)
                ->orderBy('threshold', 'asc')
                ->first();

            if ($nextCoupon) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Không có mã giảm giá phù hợp.',
                    'next_threshold' => $nextCoupon->threshold,
                    'remaining_amount' => $nextCoupon->threshold - $subtotal,
                    'discount_type' => $nextCoupon->discount_type,
                    'discount_value' => $nextCoupon->discount_value,
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Không có mã giảm giá phù hợp và không có ngưỡng tiếp theo.',
                ]);
            }
        }
    }

    public function getShippingFee() // :POST
    {
        $method = $this->request->input('method');
        $totalAmount = $this->request->input('totalAmount');

        $shippingRate = ShippingRate::where('id', $method)
            ->where('is_active', true)
            ->first();

        if (!$shippingRate) {
            return response()->json(['error' => 'Phương thức vận chuyển không hợp lệ.'], 400);
        }

        $shippingFee = $shippingRate->rate;
        $shippingDeliveryTime = $shippingRate->delivery_time;

        $shippingThreshold = Frontend::where('data_key', 'setting.data')->first();
        $shippingThresholdData = json_decode($shippingThreshold->data_value, true);

        if ($totalAmount >= $shippingThresholdData['shipping_free_threshold']) {
            $shippingFee = 0;
            $shippingDeliveryTime = '1-2 ngày';
        }

        return response()->json(['fee' => $shippingFee, 'delivery_time' => $shippingDeliveryTime]);
    }

    public function removeItem() // :DELETE
    {
        $rowId = $this->request->rowId;

        Cart::instance('cart')->remove($rowId);

        return response()->json(['status' => 200, 'message' => 'Remove specified products successfully']);
    }

    public function deleteSelectedItem() // :DELETE
    {
        $rowIds = $this->request->rowIds;

        foreach ($rowIds as $rowId) {
            Cart::instance('cart')->remove($rowId);
        }

        return response()->json(['status' => 200, 'message' => 'The items have been deleted successfully']);
    }

    public function removeAllItem() // :DELETE
    {
        Cart::instance('cart')->destroy();
        Session::forget('coupon');
        Session::forget('shipping_method');

        return response()->json(['status' => 200, 'message' => 'Remove all products successfully']);
    }

    public function saveCouponSession() // :POST
    {
        $coupon = $this->request->coupon;

        if ($coupon) {
            Session::put('coupon', $coupon);
        }
    }

    public function forgetCouponSession() // :GET
    {
        Session::forget('coupon');
    }

    public function saveShippingMethodSession() // :POST
    {
        $method = $this->request->method;
        Session::put('shipping_method', $method);
    }
}
