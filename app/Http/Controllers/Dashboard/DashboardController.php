<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $dealershipId = $user->dealership_id;

        $vehicleQuery = Vehicle::query()->when($dealershipId, fn ($q) => $q->where('dealership_id', $dealershipId));

        return view('dashboard.index', [
            'vehicleCount' => (clone $vehicleQuery)->count(),
            'liveVehicleCount' => (clone $vehicleQuery)->where('status', 'live')->count(),
            'processingCount' => Photo::query()
                ->whereHas('vehicle', fn ($q) => $q->when($dealershipId, fn ($q2) => $q2->where('dealership_id', $dealershipId)))
                ->whereIn('status', [Photo::STATUS_PENDING, Photo::STATUS_PROCESSING])
                ->count(),
            'readyCount' => Photo::query()
                ->whereHas('vehicle', fn ($q) => $q->when($dealershipId, fn ($q2) => $q2->where('dealership_id', $dealershipId)))
                ->whereIn('status', [Photo::STATUS_READY, Photo::STATUS_APPROVED])
                ->count(),
            'recentVehicles' => $vehicleQuery->latest()->take(5)->get(),
        ]);
    }
}
