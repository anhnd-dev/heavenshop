<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\CustomerCart;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Size;
use App\Models\Subcategory;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index() // :GET
    {
        // ?? - Null Coalescing Operator
        $page = $this->request->query("page") ?? 1;
        $psize = $this->request->query("psize") ?? 2;
        $order = $this->request->query("order") ?? -1;
        $q_brands = $this->request->query("brands");
        $q_subcategories = $this->request->query("subcategories");
        $q_colors = $this->request->query("colors");
        $q_sizes = $this->request->query("sizes");

        // Filter Order
        $orderMappings = [
            1 => ['column' => 'created_at', 'order' => 'DESC'],
            2 => ['column' => 'created_at', 'order' => 'ASC'],
            3 => ['column' => 'price', 'order' => 'ASC'],
            4 => ['column' => 'price', 'order' => 'DESC'],
        ];

        $o_column = $orderMappings[$order]['column'] ?? 'id';
        $o_order = $orderMappings[$order]['order'] ?? 'DESC';

        $query = Product::query();

        // Filter Categories
        if ($q_subcategories) {
            $subcategories = explode(',', $q_subcategories);
            $query->whereIn('subcategory_id', $subcategories);
        }

        // Filter Brand
        if ($q_brands) {
            $brands = explode(',', $q_brands);
            $query->whereIn('brand_id', $brands);
        }

        // Filter Colors
        if ($q_colors) {
            $colors = explode(',', $q_colors);
            $query->whereHas('colors', function ($query) use ($colors) {
                $query->whereIn('color_id', $colors);
            });
        }

        // Filter Sizes
        if ($q_sizes) {
            $sizes = explode(',', $q_sizes);
            $query->whereHas('sizes', function ($query) use ($sizes) {
                $query->whereIn('size_id', $sizes);
            });
        }

        // Fetch Products with Pagination and Sorting
        $products = $query->orderBy($o_column, $o_order)->paginate($psize);

        // Fetch Related Data
        $categories = Category::where('type', 'product')->orderBy("name", "ASC")->get();
        $brands = Brand::orderBy("name", "ASC")->get();
        $colors = Color::withCount('products')->get();
        $sizes = Size::withCount('products')->get();

        // Determine Active Categories
        $activeCategoryIds = [];
        if ($q_subcategories) {
            foreach ($subcategories as $subcategoryId) {
                $subcategory = Subcategory::find($subcategoryId);
                if ($subcategory) {
                    $activeCategoryIds[] = $subcategory->category_id;
                }
            }
        }

        return view('pages.shop', compact('products', 'page', 'psize', 'order', 'categories', 'colors', 'sizes', 'brands', 'q_brands', 'q_subcategories', 'activeCategoryIds', 'q_colors', 'q_sizes'));
    }

    public function productDetails($slug) // :GET
    {
        $product = Product::with('brand', 'subcategory')->where('slug', $slug)->first();
        $galleries = Gallery::where('product_id', $product->id)->get();
        $rproducts = Product::where('slug', '!=', $slug)->inRandomOrder('id')->get()->take(4);

        return view('pages.product.detail', compact('product', 'galleries', 'rproducts'));
    }

    public function quickToView() // :GET
    {
        $product_id = $this->request->id;
        $product = Product::with('brand', 'subcategory', 'colors', 'sizes')->where('id', $product_id)->first();
        $galleries = Gallery::where('product_id', $product->id)->get();

        return response()->json([
            'product' => $product,
            'galleries' => $galleries
        ]);
    }

    public function addToCart() // :POST
    {
        DB::beginTransaction();

        try {
            // Lấy thông tin sản phẩm dựa trên product_id
            $product = Product::find($this->request->product_id);

            if (!$product) {
                return response()->json(['status' => 404, 'message' => 'Sản phẩm không tồn tại']);
            }

            // Kiểm tra số lượng sản phẩm có đủ hay không
            if ($product->quantity >= $this->request->quantity) {
                // Tính toán giá sau khi giảm giá
                $discountAmount = ($product->price * $product->discount) / 100;
                $discountedPrice = $product->price - $discountAmount;

                // Sinh ra rowId từ các thông tin sản phẩm
                $rowId = sha1($product->id . $this->request->size . $this->request->color . $this->request->quantity);

                // Xây dựng thông tin sản phẩm thêm vào giỏ hàng
                $item = [
                    'rowId' => $rowId,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $this->request->quantity,
                    'price' => $discountedPrice,
                    'size' => $this->request->size,
                    'color' => $this->request->color
                ];

                if (Auth::guard('customer')->check()) {
                    // Người dùng đã đăng nhập -> lưu vào bảng user_carts
                    $customerCart = CustomerCart::firstOrCreate(['user_id' => auth()->id()]);
                    $cartData = json_decode($customerCart->cart_data, true) ?? ['items' => []];

                    // Kiểm tra sản phẩm đã tồn tại trong giỏ hàng chưa
                    $found = false;
                    foreach ($cartData['items'] as &$cartItem) {
                        if ($cartItem['rowId'] == $rowId) {
                            // Cộng dồn số lượng sản phẩm nếu đã tồn tại
                            $cartItem['quantity'] += $item['quantity'];
                            $found = true;
                            break;
                        }
                    }

                    if (!$found) {
                        // Thêm sản phẩm mới vào giỏ hàng
                        $cartData['items'][] = $item;
                    }

                    // Lưu lại giỏ hàng vào cơ sở dữ liệu
                    $customerCart->cart_data = json_encode($cartData);
                    $customerCart->save();
                } else {
                    // Người dùng chưa đăng nhập -> lưu vào session
                    $cart = session()->get('cart', ['items' => []]);

                    // Kiểm tra sản phẩm đã tồn tại trong giỏ hàng chưa
                    $found = false;
                    foreach ($cart['items'] as &$cartItem) {
                        if ($cartItem['rowId'] == $rowId) {
                            // Cộng dồn số lượng sản phẩm nếu đã tồn tại
                            $cartItem['quantity'] += $item['quantity'];
                            $found = true;
                            break;
                        }
                    }

                    if (!$found) {
                        // Thêm sản phẩm mới vào giỏ hàng
                        $cart['items'][] = $item;
                    }

                    // Lưu giỏ hàng vào session
                    session()->put('cart', $cart);
                }

                DB::commit();

                return response()->json(['status' => 200, 'message' => 'Sản phẩm đã được thêm vào giỏ hàng']);
            } else {
                return response()->json(['status' => 302, 'message' => 'Số lượng trong kho không đủ']);
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi']);
        }
    }
}
