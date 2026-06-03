<?php

namespace App\Services\Frontend;

use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Customer;

class AccountService
{
    use ImageUploadTrait;

    /**
     * Upload Avatar
     */
    public function updateAvatar(
        Request $request
    ): array {

        $request->validate([
            'avatar' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ]
        ]);

        $customer = Auth::guard('customer')
            ->user();

        $avatar = $this->uploadFile(
            $request->file('avatar'),
            'account'
        );

        if ($customer->avatar) {

            $this->deleteFile(
                $customer->avatar,
                'account'
            );
        }

        /** @var Customer $customer */
        $customer->update([
            'avatar' => $avatar
        ]);

        return [
            'success' => true,

            'message' => 'Cập nhật ảnh đại diện thành công',

            'avatar_url' => asset(
                'uploads/account/' . $avatar
            ),
        ];
    }

    /**
     * Update Profile
     */
    public function updateProfile(
        Request $request
    ): array {

        $customer = Auth::guard('customer')
            ->user();

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'phone' => [
                'required',
                'string',
                'max:20',
                'unique:customers,phone,' . $customer->id,
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:customers,email,' . $customer->id,
            ],

            'gender' => [
                'nullable',
                'in:male,female',
            ],
        ]);

        /** @var Customer $customer */
        $customer->update([
            'name'   => $request->name,
            'phone'  => $request->phone,
            'email'  => $request->email,
            'gender' => $request->gender,
        ]);

        return [
            'success' => true,

            'message' => 'Cập nhật thông tin thành công',
        ];
    }

    /**
     * Update Password
     */
    public function updatePassword(
        Request $request
    ): array {

        $request->validate([
            'current_password' => [
                'required',
            ],

            'password' => [
                'required',
                'min:6',
                'confirmed',
            ],
        ]);

        $customer = Auth::guard('customer')
            ->user();

        if (
            !Hash::check(
                $request->current_password,
                $customer->password
            )
        ) {

            abort(
                response()->json([
                    'message' =>
                    'Mật khẩu hiện tại không chính xác'
                ], 422)
            );
        }

        /** @var Customer $customer */
        $customer->update([
            'password' => Hash::make(
                $request->password
            )
        ]);

        return [
            'success' => true,

            'message' => 'Đổi mật khẩu thành công',
        ];
    }
}
