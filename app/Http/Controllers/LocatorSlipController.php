<?php

namespace App\Http\Controllers;

use App\Models\LocatorSlip;
use App\Models\User;
use App\Notifications\LocatorSlipNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocatorSlipController extends Controller
{
    public function index()
    {
        $locatorSlips = LocatorSlip::where('employee_id', Auth::user()->employee->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('locator-slip.index', compact('locatorSlips'));
    }

    public function create()
    {
        return view('locator-slip.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_covered' => 'required|date',
            'destination' => 'required|string|max:255',
            'purpose' => 'required|string',
            'type' => 'required|string|max:255',
            'time_from' => 'required',
            'time_to' => 'required',
        ]);

        $locatorSlip = LocatorSlip::create([
            'employee_id' => Auth::user()->employee->id,
            'date_covered' => $request->date_covered,
            'destination' => $request->destination,
            'purpose' => $request->purpose,
            'type' => $request->type,
            'time_from' => $request->time_from,
            'time_to' => $request->time_to,
            'status' => 'pending',
        ]);

        // Notify Chief
        $chiefs = User::whereHas('employee', function ($query) {
            $query->where('account_role', 'CHIEF');
        })->get();

        foreach ($chiefs as $chief) {
            $chief->notify(new LocatorSlipNotification($locatorSlip, 'New Locator Slip', Auth::user()->employee->firstname . ' ' . Auth::user()->employee->lastname . ' submitted a new locator slip.'));
        }

        return redirect()->route('locator-slips.index')->with('message', 'Locator slip submitted successfully.');
    }

    public function manageIndex(Request $request)
    {
        $allLocatorSlips = LocatorSlip::with('employee')
            ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingLocatorSlips = $this->getPendingLocatorSlips();

        $tab = $request->query('tab', 'pending');
        if (! in_array($tab, ['all', 'pending'])) {
            $tab = 'pending';
        }

        return view('locator-slip.hr.manage', compact('allLocatorSlips', 'pendingLocatorSlips', 'tab'));
    }

    public function allIndex()
    {
        return redirect()->route('hr.locator-slips.index', ['tab' => 'all']);
    }

    public function pendingIndex()
    {
        return redirect()->route('hr.locator-slips.index', ['tab' => 'pending']);
    }

    private function getPendingLocatorSlips()
    {
        $user = Auth::user();
        $query = LocatorSlip::with('employee')->orderBy('created_at', 'desc');

        if (strtolower($user->role) === 'chief') {
            $query->where('status', 'pending');
        } elseif (in_array(strtolower($user->role), ['regional director', 'regionaldirector'])) {
            $query->where('status', 'approved by chief');
        } else {
            $query->where(function ($q) {
                $q->where('status', 'pending')
                    ->orWhere('status', 'approved by chief');
            });
        }

        return $query->get();
    }

    public function approve(LocatorSlip $locatorSlip)
    {
        $user = Auth::user();

        if (strtolower($user->role) === 'chief' && $locatorSlip->status === 'pending') {
            $locatorSlip->status = 'approved by chief';
            $locatorSlip->approved_by_chief_id = $user->id;
            $locatorSlip->approved_by_chief_name = $user->name;
            $locatorSlip->chief_approval_date = now();
            $locatorSlip->save();

            // Notify Regional Director
            $directors = User::whereHas('employee', function ($query) {
                $query->whereIn('account_role', ['REGIONALDIRECTOR', 'REGIONAL DIRECTOR', 'regionaldirector', 'regional director']);
            })->get();

            foreach ($directors as $director) {
                $director->notify(new LocatorSlipNotification($locatorSlip, 'Pending Locator Slip', 'A locator slip from ' . $locatorSlip->employee->firstname . ' ' . $locatorSlip->employee->lastname . ' has been approved by the Chief and awaits your final approval.'));
            }

            return redirect()->back()->with('message', 'Locator slip approved. Pending Regional Director approval.');
        }

        if (in_array(strtolower($user->role), ['regional director', 'regionaldirector']) && $locatorSlip->status === 'approved by chief') {
            $locatorSlip->status = 'approved';
            $locatorSlip->approved_by_regional_director_id = $user->id;
            $locatorSlip->approved_by_regional_director_name = $user->name;
            $locatorSlip->regional_director_approval_date = now();
            $locatorSlip->save();

            // Notify Employee
            $locatorSlip->employee->user->notify(new LocatorSlipNotification($locatorSlip, 'Locator Slip Approved', 'Your locator slip for ' . $locatorSlip->date_covered . ' has been fully approved.'));

            return redirect()->back()->with('message', 'Locator slip has been fully approved.');
        }

        return redirect()->back()->with('error', 'You are not authorized to approve this locator slip or it is not in a valid state for approval.');
    }

    public function reject(LocatorSlip $locatorSlip)
    {
        $locatorSlip->status = 'rejected';
        $locatorSlip->save();

        // Notify Employee
        $locatorSlip->employee->user->notify(new LocatorSlipNotification($locatorSlip, 'Locator Slip Rejected', 'Your locator slip for ' . $locatorSlip->date_covered . ' has been rejected.'));

        return redirect()->back()->with('message', 'Locator slip rejected.');
    }

    public function hrShow(LocatorSlip $locatorSlip)
    {
        // View for HR/Director side
        if (!in_array(strtolower(Auth::user()->role), ['admin', 'hrstaff', 'director', 'chief', 'regionaldirector', 'regional director'])) {
            abort(403);
        }

        return view('locator-slip.hr.show', compact('locatorSlip'));
    }

    public function show(LocatorSlip $locatorSlip)
    {
        // Ensure the user is authorized to see this slip
        if (Auth::user()->employee->id !== $locatorSlip->employee_id && !in_array(strtolower(Auth::user()->role), ['admin', 'hrstaff', 'director', 'chief', 'regionaldirector', 'regional director'])) {
            abort(403);
        }

        return view('locator-slip.show', compact('locatorSlip'));
    }

    public function edit(LocatorSlip $locatorSlip)
    {
        // Ensure the user is authorized to edit this slip
        if (Auth::user()->employee->id !== $locatorSlip->employee_id) {
            abort(403);
        }

        return view('locator-slip.edit', compact('locatorSlip'));
    }

    public function update(Request $request, LocatorSlip $locatorSlip)
    {
        // Ensure the user is authorized to update this slip
        if (Auth::user()->employee->id !== $locatorSlip->employee_id) {
            abort(403);
        }

        $request->validate([
            'date_covered' => 'required|date',
            'purpose' => 'required|string',
            'time_from' => 'required',
            'time_to' => 'required',
        ]);

        $locatorSlip->update($request->all());

        return redirect()->route('locator-slips.show', $locatorSlip)->with('message', 'Locator slip updated successfully.');
    }
}
