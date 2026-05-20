<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PolicyController extends Controller
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

            $policies = $query->latest()->get();

            $policyData = $policies->filter(function ($item) {
                return $item->data_key === 'policy.element';
            });

            return DataTables::of($policyData)
                ->addIndexColumn()
                ->addColumn('action', function ($policy) use ($includeTrashed) {
                    if ($includeTrashed) {
                        return '<button type="button" title="Nút khôi phục" id="' . $policy->id . '" class="restoreIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm" ><i class="fas fa-trash-restore"></i></button>' .
                            '<button type="button" title="Nút xóa vĩnh viễn" id="' . $policy->id . '" class="forceIcon btn btn-danger shadow btn-xs sharp btn-sm"><i class="fas fa-trash-alt"></i></button>';
                    } else {
                        return '<button type="button" title="Nút cập nhật" id="' . $policy->id . '" class="editIcon btn btn-primary shadow btn-xs sharp mr-1 btn-sm" data-toggle="modal" data-target="#editPolicyModel"><i class="fas fa-pencil-alt"></i></button>' .
                            '<button type="button" title="Nút xóa" id="' . $policy->id . '" class="deleteIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm"><i class="fa fa-trash"></i></button>' .
                            '<button type="button" title="Nút trạng thái" id="' . $policy->id . '" class="statusIcon btn ' . ($policy->status == 1 ? "btn-success" : "btn-dark") . ' shadow btn-xs sharp btn-sm"><i class="fa ' . ($policy->status == 1 ? "fa-eye" : "fa-eye-slash") . '"></i></button>';
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.frontend.policy.index');
    }

    public function store() // :POST
    {
        DB::beginTransaction();

        try {
            $title = $this->request->input('title');
            $slug = $this->request->input('slug');
            $detail = strip_tags($this->request->detail, '<p><span><a><strong><em><ul><ol><li>');

            $data = [
                'title' => $title,
                'slug' => $slug,
                'detail' => $detail,
            ];

            $jsonData = json_encode($data);

            Frontend::create([
                'data_key' => 'policy.element',
                'data_value' => $jsonData,
            ]);

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Policy đã được thêm thành công."]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi thêm policy']);
        }
    }

    public function edit() // :GET
    {
        $id = $this->request->id;
        $policy = Frontend::find($id);

        if ($policy) {
            return response()->json(['status' => 200, 'data' => $policy]);
        } else {
            return response()->json(['status' => 404, 'message' => 'Không tìm thấy policy']);
        }
    }

    public function update() // :PUT
    {
        $policy_id = $this->request->input('policy_id');

        DB::beginTransaction();

        try {
            $policy = Frontend::find($policy_id);
            if (!$policy) {
                return response()->json(['status' => 404, 'message' => 'Không tìm thấy policy']);
            }

            $title = $this->request->input('title');
            $slug = $this->request->input('slug');
            $detail = strip_tags($this->request->detail, '<p><span><a><strong><em><ul><ol><li>');

            $data = [
                'title' => $title,
                'slug' => $slug,
                'detail' => $detail,
            ];

            $jsonData = json_encode($data);

            $policy->update([
                'data_value' => $jsonData,
            ]);

            DB::commit();
            return response()->json(['status' => 200, 'message' => 'Đã cập nhật policy thành công']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi cập nhật policy']);
        }
    }

    public function delete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $policy = Frontend::findOrFail($id);
            $policy->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Policy đã được di chuyển vào thùng rác!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa policy']);
        }
    }

    public function deleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            $deletedCount = Frontend::whereIn('id', $ids)->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} Policy đã được di chuyển vào thùng rác!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa policy']);
        }
    }

    public function restore() // :POST
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            Frontend::where('id', $id)->withTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Policy đã khôi phục thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục policy']);
        }
    }

    public function restoreAll() // :POST
    {
        DB::beginTransaction();

        try {
            Frontend::onlyTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Khôi phục lại tất cả policy thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục tất cả policy']);
        }
    }

    public function forceDelete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $policy = Frontend::where('id', $id)->withTrashed()->firstOrFail();
            $policy->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Policy đã xóa khỏi cơ sở dữ liệu!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục policy']);
        }
    }

    public function forceDeleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            // Xóa các policy vĩnh viễn
            $deletedCount = Frontend::withTrashed()->whereIn('id', $ids)->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} policy đã xóa khỏi cơ sở dữ liệu!!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa policy']);
        }
    }

    public function changeStatus() // :POST
    {
        $id = $this->request->id;
        $newStatus = $this->request->input('new_status');

        DB::beginTransaction();

        try {
            $policy = Frontend::findOrFail($id);
            $policy->status = $newStatus;
            $policy->save();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Đã cập nhật trạng thái policy thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi cập nhật trạng thái policy']);
        }
    }
}
