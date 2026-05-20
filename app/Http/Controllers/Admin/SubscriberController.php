<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index() // :GET
    {
        // if ($this->request->ajax()) {
        //     $includeTrashed = $this->request->get('include_trashed');

        //     $query = Brand::query()->select('id', 'name', 'slug', 'image');

        //     if ($includeTrashed) {
        //         $query->onlyTrashed();
        //     } else {
        //         $query->whereNull('deleted_at');
        //     }

        //     $brands = $query->latest()->get();

        //     return DataTables::of($brands)
        //         ->addIndexColumn()
        //         ->addColumn('action', function ($brand) use ($includeTrashed) {
        //             if ($includeTrashed) {
        //                 return '<button type="button" title="Nút khôi phục" id="' . $brand->id . '" class="restoreIcon btn btn-danger shadow btn-xs sharp mr-1 btn-sm" ><i class="fas fa-trash-restore"></i></button>' .
        //                     '<button type="button" title="Nút xóa vĩnh viễn" id="' . $brand->id . '" class="forceIcon btn btn-danger shadow btn-xs sharp btn-sm"><i class="fas fa-trash-alt"></i></button>';
        //             } else {
        //                 return '<button type="button" title="Nút cập nhật" id="' . $brand->id . '" class="editIcon btn btn-primary shadow btn-xs sharp mr-1 btn-sm" data-toggle="modal" data-target="#editBrandModel"><i class="fas fa-pencil-alt"></i></button>' .
        //                     '<button type="button" title="Nút xóa" id="' . $brand->id . '" class="deleteIcon btn btn-danger shadow btn-xs sharp btn-sm"><i class="fa fa-trash"></i></button>';
        //             }
        //         })
        //         ->rawColumns(['action'])
        //         ->make(true);
        // }

        // return view('admin.pages.brand.index');
    }
}
