<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SliderController extends Controller
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

            $sliders = $query->latest()->get();

            $sliderData = $sliders->filter(function ($item) {
                return $item->data_key === 'slider.element';
            });

            return DataTables::of($sliderData)
                ->addIndexColumn()
                ->addColumn('action', function ($slider) use ($includeTrashed) {
                    if ($includeTrashed) {
                        return '<button type="button" title="'. __('admin.action.button_restore') .'" id="' . $slider->id . '" class="restoreIcon btn btn-danger shadow btn-xs sharp mr-1 me-1 btn-sm" ><i class="fas fa-trash-restore"></i></button>' .
                            '<button type="button" title="'. __('admin.action.button_delete_permanently') .'" id="' . $slider->id . '" class="forceIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm"><i class="fas fa-trash-alt"></i></button>';
                    } else {
                        return '<button type="button" title="'. __('admin.action.button_update') .'" id="' . $slider->id . '" class="editIcon btn btn-primary shadow btn-xs sharp mr-1 mb-1 btn-sm" data-toggle="modal" data-target="#editSliderModel"><i class="fas fa-pencil-alt"></i></button>' .
                            '<button type="button" title="'. __('admin.action.button_delete') .'" id="' . $slider->id . '" class="deleteIcon btn btn-danger shadow btn-xs sharp mr-1 mb-1 btn-sm"><i class="fa fa-trash"></i></button>' .
                            '<button type="button" title="'. __('admin.action.button_status') .'" id="' . $slider->id . '" class="statusIcon btn '.($slider->status == 1 ? "btn-success": "btn-dark").' shadow btn-xs sharp btn-sm"><i class="fa '.($slider->status == 1 ? "fa-eye": "fa-eye-slash").'"></i></button>';
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        return view('admin.frontend.slider.index');
    }

    public function store() // :POST
    {
        DB::beginTransaction();

        try {
            if ($this->request->hasFile('image_url')) {
                $image_new = $this->uploadImage($this->request->file('image_url'));
            }

            $data = [
                'title' => $this->request->input('title'),
                'description' => $this->request->input('description'),
                'link_url' => $this->request->input('link_url'),
                'image_url' => $image_new
            ];

            $jsonData = json_encode($data);

            Frontend::create([
                'data_key' => 'slider.element',
                'data_value' => $jsonData,
            ]);

            DB::commit();
            return response()->json(['status' => 200, 'message' => __('admin.notify.slider.added')]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => __('admin.notify.slider.err_added')]);
        }
    }

    public function edit() // :GET
    {
        $id = $this->request->id;
        $slider = Frontend::find($id);

        if ($slider) {
            return response()->json(['status' => 200, 'data' => $slider]);
        } else {
            return response()->json(['status' => 404, 'message' => __('admin.notify.slider.not_found')]);
        }
    }

    public function update() // :PUT
    {
        $slider_id = $this->request->input('slider_id');

        DB::beginTransaction();

        try {
            $slider = Frontend::find($slider_id);

            $sliderData = json_decode($slider->data_value, true);

            $data = [
                'title' => $this->request->input('title'),
                'description' => $this->request->input('description'),
                'link_url' => $this->request->input('link_url'),
            ];

            if ($this->request->hasFile('image_url')) {
                $image_new = $this->uploadImage($this->request->file('image_url'));
                $data['image_url'] = $image_new;

                if (isset($sliderData['image_url'])) {
                    $this->deleteImage($sliderData['image_url']);
                }
            } else {
                $data['image_url'] = $sliderData['image_url'];
            }

            $jsonData = json_encode($data);

            $slider->update([
                'data_value' => $jsonData,
            ]);

            DB::commit();
            return response()->json(['status' => 200, 'message' => __('admin.notify.slider.updated')]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => __('admin.notify.slider.err_updated')]);
        }
    }

    public function delete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $slider = Frontend::findOrFail($id);
            $slider->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => __('admin.notify.slider.deleted')]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => __('admin.notify.slider.err_deleted')]);
        }
    }

    public function deleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            $deletedCount = Frontend::whereIn('id', $ids)->delete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => $deletedCount . ' ' . __('admin.notify.slider.deleted_all')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => __('admin.notify.slider.err_deleted_all')]);
        }
    }

    public function restore() // :POST
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            Frontend::where('id', $id)->withTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => __('admin.notify.slider.restore')]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => __('admin.notify.slider.err_restore')]);
        }
    }

    public function restoreAll() // :POST
    {
        DB::beginTransaction();

        try {
            Frontend::onlyTrashed()->restore();
            DB::commit();
            return response()->json(['status' => 200, 'message' => __('admin.notify.slider.restore_all')]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => __('admin.notify.slider.err_restore_all')]);
        }
    }

    public function forceDelete() // :DELETE
    {
        $id = $this->request->id;

        DB::beginTransaction();

        try {
            $slider = Frontend::where('id', $id)->withTrashed()->firstOrFail();

            $sliderData = json_decode($slider->data_value, true);

            if (isset($sliderData['image_url'])) {
                $this->deleteImage($sliderData['image_url']);
            }

            $slider->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => __('admin.notify.slider.force_delete')]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => __('admin.notify.slider.err_force_delete')]);
        }
    }

    public function forceDeleteAll() // :DELETE
    {
        $ids = $this->request->ids;

        DB::beginTransaction();

        try {
            // Lấy danh sách các thương hiệu cần xóa
            $sliders = Frontend::withTrashed()->whereIn('id', $ids)->get();

            foreach ($sliders as $slider) {
                $sliderData = json_decode($slider->data_value, true);

                if (isset($sliderData['image_url'])) {
                    $this->deleteImage($sliderData['image_url']);
                }
            }

            // Xóa các thương hiệu vĩnh viễn
            $deletedCount = Frontend::withTrashed()->whereIn('id', $ids)->forceDelete();

            DB::commit();
            return response()->json(['status' => 200, 'message' => $deletedCount . ' ' . __('admin.notify.slider.force_delete_all')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => __('admin.notify.slider.err_force_delete_all')]);
        }
    }

    public function changeStatus() // :POST
    {
        $id = $this->request->id;
        $newStatus = $this->request->input('new_status');

        DB::beginTransaction();

        try {
            $slider = Frontend::findOrFail($id);
            $slider->status = $newStatus;
            $slider->save();
            DB::commit();
            return response()->json(['status' => 200, 'message' => __('admin.notify.slider.update_status')]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => __('admin.notify.slider.err_update_status')]);
        }
    }

    protected function uploadImage($file)
    {
        $image_new = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/slider/'), $image_new);
        return $image_new;
    }

    protected function deleteImage($image_url)
    {
        $filePath = public_path('uploads/slider/' . $image_url);

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
