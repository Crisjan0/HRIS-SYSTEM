<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Announcement;
use App\Models\User;
use App\Notifications\NewAnnouncementNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $announcements = Announcement::with('author')
            ->latest()
            ->paginate(10);

        return view('announcements.index', compact('announcements'));
    }

    /**
     * Display a public listing of the published announcements.
     */
    public function userIndex(): View
    {
        $announcements = Announcement::with('author')
            ->published()
            ->latest()
            ->paginate(12);

        return view('announcements.view', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('announcements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
            'tags' => 'nullable|string|max:255',
        ]);

        $announcement = new Announcement($validated);
        $announcement->author_id = auth()->id();
        
        if ($request->has('is_published') && $request->is_published) {
            $announcement->published_at = Carbon::now();
        }

        $announcement->save();

        if ($announcement->is_published) {
            Notification::send(User::all(), new NewAnnouncementNotification($announcement));
        }

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement): View
    {
        if (!$announcement->is_published && !in_array(auth()->user()->role, ['admin', 'hrstaff'])) {
            abort(403, 'Unauthorized access to draft announcements.');
        }

        return view('announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement): View
    {
        return view('announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
            'tags' => 'nullable|string|max:255',
        ]);

        $was_published = $announcement->is_published;
        
        $announcement->fill($validated);
        $announcement->is_published = $request->has('is_published');

        if ($announcement->is_published && !$was_published) {
            $announcement->published_at = Carbon::now();
        } elseif (!$announcement->is_published) {
            $announcement->published_at = null;
        }

        $announcement->save();

        if ($announcement->is_published && !$was_published) {
            Notification::send(User::all(), new NewAnnouncementNotification($announcement));
        }

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}
