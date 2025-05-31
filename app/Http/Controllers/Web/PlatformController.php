<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlatformResource;
use App\Models\Platform;
use App\Services\PlatformService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlatformController extends Controller
{
    protected $platformService;

    public function __construct(PlatformService $platformService)
    {
        $this->platformService = $platformService;
    }

    public function index()
    {
        $platforms = $this->platformService->getPlatformsWithStatus(Auth::user());

        return view('platforms.index', ['platforms' => PlatformResource::collection($platforms)]);
    }

    public function toggleActive(Request $request, Platform $platform)
    {
        try {
            $isActive = $this->platformService->toggleActive(Auth::user(), $platform);
            $status = $isActive ? 'activated' : 'deactivated';

            return back()->with('status', "Platform {$status} successfully.");
        } catch (\Exception $e) {
            $this->errorLog($e, 'Failed to toggle platform status');

            return back()->with('error', $e->getMessage());
        }
    }

    public function connect(Request $request, Platform $platform)
    {
        try {
            $this->platformService->connect(Auth::user(), $platform);

            return back()->with('status', 'Platform connected successfully.');
        } catch (\Exception $e) {
            $this->errorLog($e, 'Failed to connect platform');

            return back()->with('error', $e->getMessage());
        }
    }

    public function disconnect(Platform $platform)
    {
        try {
            $this->platformService->disconnect(Auth::user(), $platform);

            return back()->with('status', 'Platform disconnected successfully.');
        } catch (\Exception $e) {
            $this->errorLog($e, 'Failed to disconnect platform');

            return back()->with('error', $e->getMessage());
        }
    }
}
