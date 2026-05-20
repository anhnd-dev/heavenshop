<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SocialController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index() // :GET
    {
        if ($this->request->ajax()) {

            $includeTrashed = $this->request->get('include_trashed');

            $query = Frontend::query()->select('id', 'data_key', 'data_value', 'status');

            if ($includeTrashed) {
                $query->onlyTrashed();
            } else {
                $query->whereNull('deleted_at');
            }

            $social_icons = $query->latest()->get();

            $socialIconData = $social_icons->filter(function ($item) {
                return $item->data_key === 'social_icon.element';
            });

            return DataTables::of($socialIconData)
                ->addIndexColumn()
                ->addColumn('action', function ($social_icon) use ($includeTrashed) {
                    if ($includeTrashed) {
                        return '<button type="button" title="Nút khôi phục" id="' . $social_icon->id . '" class="restoreIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm" ><i class="fas fa-trash-restore"></i></button>' .
                            '<button type="button" title="Nút xóa vĩnh viễn" id="' . $social_icon->id . '" class="forceIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm"><i class="fas fa-trash-alt"></i></button>';
                    } else {
                        return '<button type="button" title="Nút cập nhật" id="' . $social_icon->id . '" class="editIcon btn btn-primary shadow btn-xs sharp mr-1 btn-sm" data-toggle="modal" data-target="#editSocialIconModel"><i class="fas fa-pencil-alt"></i></button>' .
                            '<button type="button" title="Nút xóa" id="' . $social_icon->id . '" class="deleteIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm"><i class="fa fa-trash"></i></button>' .
                            '<button type="button" title="Nút trạng thái" id="' . $social_icon->id . '" class="statusIcon btn ' . ($social_icon->status == 1 ? "btn-success" : "btn-dark") . ' shadow btn-xs sharp btn-sm"><i class="fa ' . ($social_icon->status == 1 ? "fa-eye" : "fa-eye-slash") . '"></i></button>';
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        return view('admin.frontend.social_icon.index');
    }

    public function store() // :POST
    {
        DB::beginTransaction();

        try {
            $data = [
                'title' => $this->request->input('title'),
                'social_icon' => $this->createIcon($this->request->input('social_icon')),
                'url' => $this->request->input('url'),
            ];

            Frontend::create([
                'data_key' => 'social_icon.element',
                'data_value' => json_encode($data),
            ]);

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Biểu tượng MXH đã được thêm thành công."]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi thêm biểu tựng MXH']);
        }
    }

    public function edit() // :GET
    {
        $id = $this->request->id;
        $social_icon = Frontend::find($id);

        if ($social_icon) {
            return response()->json(['status' => 200, 'data' => $social_icon]);
        } else {
            return response()->json(['status' => 404, 'message' => 'Không tìm thấy biểu tượng MXH']);
        }
    }

    public function update() // :PUT
    {
        $social_icon_id = $this->request->input('social_icon_id');

        DB::beginTransaction();

        try {
            $social_icon = Frontend::find($social_icon_id);

            if (!$social_icon) {
                return response()->json(['status' => 404, 'message' => 'Không tìm thấy biểu tượng MXH']);
            }

            $data = [
                'title' => $this->request->input('title'),
                'social_icon' => $this->createIcon($this->request->input('social_icon')),
                'url' => $this->request->input('url'),
            ];

            $social_icon->update([
                'data_value' => json_encode($data)
            ]);

            DB::commit();
            return response()->json(['status' => 200, 'message' => 'Đã cập nhật biểu tượng MXH thành công']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi cập nhật biểu tượng MXH']);
        }
    }

    public function delete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            Frontend::findOrFail($id)->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Biểu tượng MXH đã được di chuyển vào thùng rác!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa biểu tượng MXH']);
        }
    }

    public function deleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            $deletedCount = Frontend::whereIn('id', $ids)->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} biểu tượng MXH đã được di chuyển vào thùng rác!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa biểu tượng MXH']);
        }
    }

    public function restore() // :POST
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            Frontend::where('id', $id)->withTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Biểu tượng MXH đã khôi phục thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục biểu tượng MXH']);
        }
    }

    public function restoreAll() // :POST
    {
        DB::beginTransaction();

        try {
            Frontend::onlyTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Khôi phục lại tất cả biểu tượng MXH thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục tất cả biểu tượng MXH']);
        }
    }

    public function forceDelete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $social_icon = Frontend::where('id', $id)->withTrashed()->firstOrFail();
            $social_icon->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Biểu tượng MXH đã xóa khỏi cơ sở dữ liệu!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục biểu tượng MXH']);
        }
    }

    public function forceDeleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            // Xóa các thương hiệu vĩnh viễn
            $deletedCount = Frontend::withTrashed()->whereIn('id', $ids)->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} biểu tượng MXH đã xóa khỏi cơ sở dữ liệu!!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa biểu tượng MXH']);
        }
    }

    public function changeStatus() // :POST
    {
        $id = $this->request->id;
        $newStatus = $this->request->input('new_status');

        DB::beginTransaction();

        try {
            $social_icon = Frontend::findOrFail($id);
            $social_icon->status = $newStatus;
            $social_icon->save();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Đã cập nhật trạng thái biểu tượng MXH thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi cập nhật trạng thái biểu tượng MXH']);
        }
    }

    protected function createIcon($iconClass)
    {
        // Tạo một thẻ <i> chứa biểu tượng từ Font Awesome
        $icon = '<i class="' . $iconClass . '"></i>';
        // Trả về thẻ <i> đã tạo
        return $icon;
    }
}
