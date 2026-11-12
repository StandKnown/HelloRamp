<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminReviewQueueController extends Controller
{
    public function index(): View
    {
        $photos = Photo::query()
            ->with(['vehicle.dealership', 'uploader'])
            ->where('status', Photo::STATUS_READY)
            ->where('reviewed', false)
            ->orderBy('created_at')
            ->paginate(20);

        return view('admin.review-queue.index', compact('photos'));
    }

    public function approve(Request $request, Photo $photo): RedirectResponse
    {
        $photo->update([
            'status' => Photo::STATUS_APPROVED,
            'reviewed' => true,
            'reviewed_by' => $request->user()->id,
        ]);

        return back()->with('status', 'Photo approved.');
    }

    public function reject(Request $request, Photo $photo): RedirectResponse
    {
        $request->validate(['notes' => ['nullable', 'string', 'max:2000']]);

        $photo->update([
            'status' => Photo::STATUS_PENDING,
            'reviewed' => true,
            'reviewed_by' => $request->user()->id,
            'notes' => $request->input('notes'),
            'revision' => $photo->revision + 1,
        ]);

        \App\Jobs\ProcessPhotoJob::dispatch($photo);

        return back()->with('status', 'Photo rejected — re-enqueued for processing.');
    }
}
