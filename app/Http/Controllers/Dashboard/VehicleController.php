<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $vehicles = Vehicle::query()
            ->when($user->dealership_id, fn ($q) => $q->where('dealership_id', $user->dealership_id))
            ->with('photos')
            ->latest()
            ->paginate(15);

        return view('dashboard.vehicles.index', compact('vehicles'));
    }

    public function create(): View
    {
        return view('dashboard.vehicles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['dealership_id'] = $request->user()->dealership_id;
        $data['created_by'] = $request->user()->id;

        $vehicle = Vehicle::create($data);

        return redirect()->route('vehicles.show', $vehicle)->with('status', 'Vehicle created.');
    }

    public function show(Request $request, Vehicle $vehicle): View
    {
        $this->authorizeOwnership($request, $vehicle);

        return view('dashboard.vehicles.show', [
            'vehicle' => $vehicle->load(['photos' => fn ($q) => $q->latest()]),
        ]);
    }

    public function edit(Request $request, Vehicle $vehicle): View
    {
        $this->authorizeOwnership($request, $vehicle);

        return view('dashboard.vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $this->authorizeOwnership($request, $vehicle);
        $vehicle->update($this->validated($request));

        return redirect()->route('vehicles.show', $vehicle)->with('status', 'Vehicle updated.');
    }

    public function destroy(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $this->authorizeOwnership($request, $vehicle);
        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('status', 'Vehicle deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'vin' => ['nullable', 'string', 'max:32'],
            'stock_number' => ['nullable', 'string', 'max:32'],
            'year' => ['nullable', 'integer', 'between:1950,2100'],
            'make' => ['nullable', 'string', 'max:64'],
            'model' => ['nullable', 'string', 'max:64'],
            'trim' => ['nullable', 'string', 'max:64'],
            'exterior_color' => ['nullable', 'string', 'max:64'],
            'status' => ['nullable', 'in:draft,processing,ready,live,archived'],
        ]);
    }

    private function authorizeOwnership(Request $request, Vehicle $vehicle): void
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return;
        }

        if ($user->dealership_id !== $vehicle->dealership_id) {
            abort(403);
        }
    }
}
