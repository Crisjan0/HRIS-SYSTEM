<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LocatorSlip;

class HrLocatorSlipView extends Component
{
    public $locatorSlips;
    public $status;

    public function mount($status = null)
    {
        $this->status = $status;
        $query = LocatorSlip::with('employee');

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $this->locatorSlips = $query->get();
    }

    public function render()
    {
        return view('livewire.hr-locator-slip-view');
    }

    public function approve($id)
    {
        $slip = LocatorSlip::find($id);
        $slip->status = 'approved';
        $slip->save();
        $this->mount($this->status);
    }

    public function reject($id)
    {
        $slip = LocatorSlip::find($id);
        $slip->status = 'rejected';
        $slip->save();
        $this->mount($this->status);
    }
}
