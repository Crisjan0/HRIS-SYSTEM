<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Announcement;
use App\Models\User;
use App\Notifications\NewAnnouncementNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status', 'all');
        $category = $request->query('category', 'all');
        $mine = $request->boolean('mine');
        $categories = ['General', 'Meeting', 'Memo', 'Training', 'Workshop', 'Office Orders', 'Advisory'];

        $announcements = Announcement::with('author.employee')
            ->when($search, function ($query, $search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%")
                        ->orWhere('tags', 'like', "%{$search}%");
                });
            })
            ->when($status === 'published', fn ($query) => $query->where('is_published', true))
            ->when($status === 'draft', fn ($query) => $query->where('is_published', false))
            ->when($category !== 'all', fn ($query) => $query->where('tags', 'like', "%{$category}%"))
            ->when($mine, fn ($query) => $query->where('author_id', auth()->id()))
            ->latest()
            ->paginate(10)
            ->appends($request->only(['search', 'status', 'category', 'mine']));

        return view('announcements.index', compact('announcements', 'search', 'status', 'category', 'categories', 'mine'));
    }

    /**
     * Display a public listing of the published announcements.
     */
    public function userIndex(): View
    {
        $announcements = Announcement::with('author.employee')
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
            'attachment' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment_path'] = $request->file('attachment')->store('announcement-attachments', 'public');
        }
        unset($validated['attachment']);

        $announcement = new Announcement($validated);
        $announcement->author_id = auth()->id();
        $announcement->is_published = true;
        $announcement->published_at = Carbon::now();

        $announcement->save();

        Notification::send(User::all(), new NewAnnouncementNotification($announcement));

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
            'attachment' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $was_published = $announcement->is_published;

        if ($request->hasFile('attachment')) {
            if ($announcement->attachment_path) {
                Storage::disk('public')->delete($announcement->attachment_path);
            }

            $validated['attachment_path'] = $request->file('attachment')->store('announcement-attachments', 'public');
        }
        unset($validated['attachment']);

        $announcement->fill($validated);
        $announcement->is_published = true;

        if ($announcement->is_published && !$was_published) {
            $announcement->published_at = Carbon::now();
        } elseif (!$announcement->published_at) {
            $announcement->published_at = Carbon::now();
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
        if ($announcement->attachment_path) {
            Storage::disk('public')->delete($announcement->attachment_path);
        }

        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}
