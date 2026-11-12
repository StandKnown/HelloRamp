<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('marketing.contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'dealership' => ['nullable', 'string', 'max:160'],
            'message' => ['required', 'string', 'min:10', 'max:4000'],
        ]);

        // TODO: persist to leads table and notify sales. Out of scope for this scaffold.
        logger()->info('Contact lead', $request->only('name', 'email', 'dealership', 'message'));

        return redirect()->route('contact.show')->with('status', 'Thanks — we\'ll be in touch shortly.');
    }
}
