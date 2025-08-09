<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Business;
use Illuminate\Support\Facades\Auth;

class BusinessIndex extends Component
{
    public $user;

    public $businesses;
    public $showEditModal = false;
    public $editingBusiness;

    public function mount()
    {
        $this->loadBusinesses();
    }

    public function loadBusinesses()
    {
        $this->businesses = Auth::user()->businesses()->get();
    }

    public function edit(int $id)
    {
        $business = Business::findOrFail($id);
        $this->editingBusiness = $business->toArray(); // convert to array
        $this->showEditModal = true;
    }
    public function update()
    {
        $this->validate([
            'editingBusiness.name' => 'required|string|max:255',
            'editingBusiness.address' => 'nullable|string|max:500',
            'editingBusiness.phone' => 'nullable|string|max:20',
            'editingBusiness.email' => 'nullable|email|max:255',
        ]);

        $business = Auth::user()->businesses()->find($this->editingBusiness['id']);
        if (!$business) {
            session()->flash('error', 'Business not found.');
            return;
        }

        $business->update($this->editingBusiness);

        $this->showEditModal = false;
        $this->loadBusinesses();

        session()->flash('message', 'Business updated successfully.');
    }
    public function render()
    {
        return view('livewire.business-index', [
            'businesses' => $this->businesses
        ]);
    }
}
