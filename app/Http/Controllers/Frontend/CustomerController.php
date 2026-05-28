<?php

namespace App\Http\Controllers\Frontend;

use App\Models\CustomerAddress;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function show(Request $request)
    {
        $address = CustomerAddress::where(
            'customer_id',
            Auth::guard('customer')->id()
        )->findOrFail($request->id);

        return response()->json([
            'id'          => $address->id,
            'full_name'   => $address->full_name,
            'phone'       => $address->phone,
            'email'       => Auth::guard('customer')->user()->email,
            'address'     => $address->address,
            'province_id' => $address->province_id,
            'district_id' => $address->district_id,
            'ward_id'     => $address->ward_id,
        ]);
    }
}
