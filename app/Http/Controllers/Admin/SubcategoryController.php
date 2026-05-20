<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubcategoryController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function list() // :GET
    {
        $includeTrashed = $this->request->get('include_trashed');
        $categoryId = $this->request->id;

        $query = Subcategory::query()->where('category_id', $categoryId);

        if ($includeTrashed) {
            $query->onlyTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        $subcategories = $query->latest()->get();

        $output = '';

        if ($subcategories->count() > 0) {
            $output .= '
            <div class="table-responsive">
                <div class="table-content">
                    <div class="project-table">
                        <form>
                            ' . csrf_field() . '

                            <table class="display table dataTable table-striped table-bordered no-footer table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check custom-checkbox">
                                                <input type="checkbox" class="form-check-input" id="checkAllSubCategory"
                                                    required="">
                                                <label class="form-check-label" for="checkAllSubCategory"></label>
                                            </div>
                                        </th>
                                        <th>Tên danh mục con</th>
                                        <th>Slug</th>
                                        <th>Ảnh</th>
                                        <th>Danh mục cha</th>
                                        <th>Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody>';
            foreach ($subcategories as $subcategory) {
                $output .= '
                                            <tr>
                                                <td>
                                                    <div class="form-check custom-checkbox">
                                                        <input type="checkbox" class="form-check-input checkbox_subcategory_ids" name="subcategory_ids" required="" value="' . $subcategory->id . '">
                                                        <label class="form-check-label"></label>
                                                    </div>
                                                </td>
                                                <td>' . $subcategory->name . '</td>
                                                <td>' . $subcategory->slug . '</td>
                                                <td>
                                                    <img src="' . url('uploads/subcategory/' . $subcategory->image) . '" class="img-thumbnail img-custom" width="100">
                                                </td>
                                                <td>' . $subcategory->category->name . '</td>
                                                <td>';
                if ($includeTrashed) {
                    $output .= '<button type="button" title="Nút khôi phục" id="' . $subcategory->id . '" class="restoreSubIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm" ><i class="fas fa-trash-restore"></i></button>';
                    $output .= '<button type="button" title="Nút xóa vĩnh viễn" id="' . $subcategory->id . '" class="forceSubIcon btn btn-danger shadow btn-xs sharp btn-sm"><i class="fas fa-trash-alt"></i></button>';
                } else {
                    $output .= '<button type="button" title="Nút cập nhật" id="' . $subcategory->id . '" class="editSubIcon btn btn-primary shadow btn-xs sharp mr-1 btn-sm" data-toggle="modal" data-target="#editSubCategoryModel"><i class="fas fa-pencil-alt"></i></button>';
                    $output .= '<button type="button" title="Nút xóa" id="' . $subcategory->id . '" class="deleteSubIcon btn btn-danger shadow btn-xs sharp btn-sm"><i class="fa fa-trash"></i></button>';
                }
                $output .= '
                                                </td>
                                            </tr>';
            }
            $output .= '
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
            ';
        } else {
            $output .= '<div style="text-align: center;">Không có chứa danh sách bản ghi</div>';
        }

        echo $output;
    }

    public function subEdit() // :GET
    {
        $id = $this->request->id;
        $subcategory = Subcategory::find($id);
        $categories = Category::whereNull('deleted_at')->latest()->get();

        return response()->json(['subcategory' => $subcategory, 'categories' => $categories]);
    }

    public function update() // :PUT
    {
        $subcategory_id = $this->request->input('subcategory_id');

        DB::beginTransaction();

        try {
            $subcategory = Subcategory::find($subcategory_id);
            if (!$subcategory) {
                return response()->json(['status' => 404, 'message' => 'Danh mục con không tồn tại']);
            }

            $data = $this->request->only(['name', 'slug', 'category_id']);

            if ($this->request->hasFile('image')) {
                $image_new = $this->uploadImage($this->request->file('image'));
                $data['image'] = $image_new;

                if (isset($subcategory->image)) {
                    $this->deleteImage($subcategory->image);
                }
            } else {
                $data['image'] = $subcategory->image;
            }

            $subcategory->update($data);

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Đã cập nhật danh mục con {$data['name']} thành công"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi cập nhật danh mục con']);
        }
    }

    public function delete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $subcategory = Subcategory::findOrFail($id);
            $subcategory->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Danh mục con {$subcategory->name} đã được di chuyển vào thùng rác!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa danh mục con']);
        }
    }

    public function deleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            $deletedCount = Subcategory::whereIn('id', $ids)->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} danh mục con đã được di chuyển vào thùng rác!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa danh mục con']);
        }
    }

    public function restore() // :POST
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            Subcategory::where('id', $id)->withTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Danh mục con đã khôi phục thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục danh mục con']);
        }
    }

    public function restoreAll() // :POST
    {
        DB::beginTransaction();

        try {
            Subcategory::onlyTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => "Khôi phục lại tất cả danh mục con thành công!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục tất cả danh mục con']);
        }
    }

    public function forceDelete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $subcategory = Subcategory::where('id', $id)->withTrashed()->firstOrFail();

            if ($subcategory->image) {
                $this->deleteImage($subcategory->image);
            }

            $subcategory->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Danh mục con đã xóa khỏi cơ sở dữ liệu!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi khôi phục danh mục con']);
        }
    }

    public function forceDeleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            // Lấy danh sách các danh mục cần xóa
            $subcategories = Subcategory::withTrashed()->whereIn('id', $ids)->get();

            foreach ($subcategories as $subcategory) {
                if ($subcategory->image) {
                    $this->deleteImage($subcategory->image);
                }
            }

            // Xóa các danh mục vĩnh viễn
            $deletedCount = Subcategory::withTrashed()->whereIn('id', $ids)->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => "{$deletedCount} danh mục con đã xóa khỏi cơ sở dữ liệu!!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Đã xảy ra lỗi khi xóa danh mục con']);
        }
    }

    protected function uploadImage($file)
    {
        $image_new = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/subcategory/'), $image_new);
        return $image_new;
    }

    protected function deleteImage($image)
    {
        $filePath = public_path('uploads/subcategory/' . $image);

        if (file_exists($filePath) && is_readable($filePath)) {
            if (unlink($filePath)) {
                usleep(50000);
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}
