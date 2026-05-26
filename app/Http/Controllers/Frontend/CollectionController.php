<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CollectionService;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function __construct(
        protected CollectionService $collectionService
    ) {}

    public function show(Request $request, string $path)
    {
        $data = $this->collectionService
            ->getCollectionData($request, $path);

        // =========================================
        // AJAX
        // =========================================

        if ($request->ajax()) {

            return response()->json([

                'products' => view(
                    'frontend.collection.partials.products',
                    $data
                )->render(),

                'filters' => view(
                    'frontend.collection.partials.active-filters',
                    $data
                )->render(),

                'total' => $data['products']->total()

            ]);
        }

        return view(
            'frontend.collection.show',
            $data
        );
    }
}
