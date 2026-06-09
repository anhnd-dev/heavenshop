<?php

namespace App\Http\Controllers\Admin;

use App\DataTransferObjects\StatisticFilter;
use App\Http\Controllers\Controller;
use App\Services\Admin\Statistics\DashboardStatisticService;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function __construct(
        protected DashboardStatisticService $dashboardService
    ) {}

    public function index(Request $request)
    {
        $filter = new StatisticFilter(
            fromDate: $request->from_date,
            toDate: $request->to_date
        );

        $data = $this->dashboardService
            ->dashboard($filter);

        return view(
            'admin.statistics.index',
            $data
        );
    }
}
