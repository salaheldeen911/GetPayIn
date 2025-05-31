<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index()
    {
        try {
            $stats = $this->dashboardService->getStats(Auth::user());

            return view('dashboard', [
                'stats' => $stats,
            ]);
        } catch (\Exception $e) {
            $this->errorLog($e, 'Failed to load dashboard statistics');

            return view('dashboard', [
                'stats' => $this->dashboardService->getDefaultStats(),
                'error' => 'Failed to load statistics. '.$e->getMessage(),
            ]);
        }
    }
}
