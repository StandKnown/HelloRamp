<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessPhotoJob;
use App\Models\Photo;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function store(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $this->authorizeOwnership($request, $vehicle);

        $request->validate([
            'angle' => ['nullable', 'string', 'max:64'],
            'photos.*' => ['required', 'image', 'max:15360'],
            'photos' => ['required', 'array', 'min:1'],
        ]);

        foreach ($request->file('photos') as $file) {
            $path = $file->store('originals', 'public');

            $photo = Photo::create([
                'vehicle_id' => $vehicle->id,
                'uploaded_by' => $request->user()->id,
                'angle' => $request->string('angle')->toString() ?: null,
                'original_path' => $path,
                'status' => Photo::STATUS_PENDING,
            ]);

            ProcessPhotoJob::dispatch($photo);
        }

        return redirect()->route('vehicles.show', $vehicle)
            ->with('status', 'Photos uploaded — processing has started.');
    }

    public function revisit(Request $request, Photo $photo): RedirectResponse
    {
        $this->authorizePhoto($request, $photo);

        $photo->update([
            'status' => Photo::STATUS_PENDING,
            'revision' => $photo->revision + 1,
            'reviewed' => false,
            'reviewed_by' => null,
        ]);

        ProcessPhotoJob::dispatch($photo);

        return back()->with('status', 'Revisit queued — new revision will be processed shortly.');
    }

    public function destroy(Request $request, Photo $photo): RedirectResponse
    {
        $this->authorizePhoto($request, $photo);
        $vehicle = $photo->vehicle;

        foreach ([$photo->original_path, $photo->enhanced_path, $photo->thumbnail_path] as $p) {
            if ($p) {
                Storage::disk('public')->delete($p);
            }
        }

        $photo->delete();

        return redirect()->route('vehicles.show', $vehicle)->with('status', 'Photo deleted.');
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

    private function authorizePhoto(Request $request, Photo $photo): void
    {
        $this->authorizeOwnership($request, $photo->vehicle);
    }
}
