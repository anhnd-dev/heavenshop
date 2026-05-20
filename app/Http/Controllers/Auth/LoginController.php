<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerCart;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getAuth() // :GET
    {
        return view('auth.index');
    }

    public function postAuthLogin() // :POST
    {
        try {
            $credentials = [
                'email'    => $this->request->email,
                'password' => $this->request->password,
            ];

            $remember = $this->request->filled('terms_condition');

            if (Auth::guard('customer')->attempt($credentials, $remember)) {
                // Kiểm tra giỏ hàng hiện có trong phiên
                if (session()->has('cart')) {
                    $sessionCart = session()->get('cart');
                    $customerCart = CustomerCart::firstOrCreate(['customer_id' => Auth::guard('customer')->id()]);
                    $dbCartData = json_decode($customerCart->cart_data, true) ?? ['items' => []];

                    // Hợp nhất giỏ hàng từ session với giỏ hàng từ cơ sở dữ liệu
                    foreach ($sessionCart['items'] as $sessionItem) {
                        $found = false;
                        foreach ($dbCartData['items'] as &$dbItem) {
                            if ($dbItem['rowId'] == $sessionItem['rowId']) {
                                // Cộng dồn số lượng sản phẩm nếu đã tồn tại trong CSDL
                                $dbItem['quantity'] += $sessionItem['quantity'];
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            // Nếu sản phẩm không tồn tại trong CSDL, thêm mới từ session
                            $dbCartData['items'][] = $sessionItem;
                        }
                    }

                    // Lưu giỏ hàng hợp nhất vào CSDL
                    $customerCart->cart_data = json_encode($dbCartData);
                    $customerCart->save();

                    // Clear session cart
                    session()->forget('cart');
                }

                toastr()->success('Đăng nhập thành công');
                return redirect()->back();
            } else {
                toastr()->error('Email & mật khẩu nhập không đúng');
                return redirect()->route('buyer.auth');
            }
        } catch (\Exception $ex) {
            return redirect()->route('buyer.auth');
        }
    }

    public function postAuthRegister() // :POST
    {
        try {
            DB::beginTransaction();

            $data = $this->request->all();
            // Mã hóa mật khẩu trước khi lưu vào cơ sở dữ liệu
            $data['password'] = Hash::make($data['password']);

            Customer::create($data);

            DB::commit();
            toastr()->success('Đăng ký thành công', 'Success');
            return redirect()->back();
        } catch (\Exception $ex) {
            DB::rollBack();
            toastr()->error('Có lỗi xảy ra trong quá trình đăng ký', 'Error', $ex);
            return redirect()->route('buyer.auth');
        }
    }
}
