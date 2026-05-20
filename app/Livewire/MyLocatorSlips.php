<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\LocatorSlip;

class MyLocatorSlips extends Component
{
    public $locatorSlips;

    public $date_covered;
    public $purpose;
    public $time_from;
    public $time_to;

    public function mount()
    {
        $this->locatorSlips = LocatorSlip::where('employee_id', Auth::user()->employee->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function submit()
    {
        $this->validate([
            'date_covered' => 'required|date',
            'purpose' => 'required|string',
            'time_from' => 'required',
            'time_to' => 'required',
        ]);

        LocatorSlip::create([
            'employee_id' => Auth::user()->employee->id,
            'date_covered' => $this->date_covered,
            'purpose' => $this->purpose,
            'time_from' => $this->time_from,
            'time_to' => $this->time_to,
            'status' => 'pending',
        ]);

        session()->flash('message', 'Locator slip submitted successfully.');

        $this->reset(['date_covered', 'purpose', 'time_from', 'time_to']);
        $this->mount(); // Refresh the list
    }

    public function render()
    {
        return view('livewire.my-locator-slips');
    }
}
