<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use App\Models\Dealership;
use App\Models\Photo;
use App\Models\Plan;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'users' => User::count(),
                'dealerships' => Dealership::count(),
                'vehicles' => Vehicle::count(),
                'photos' => Photo::count(),
                'photos_in_queue' => Photo::whereIn('status', [Photo::STATUS_READY])->where('reviewed', false)->count(),
                'case_studies' => CaseStudy::count(),
                'plans' => Plan::count(),
            ],
        ]);
    }
}
