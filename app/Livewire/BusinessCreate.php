<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class BusinessCreate extends Component
{
    use WithFileUploads;
    public $name;
    public $phone;
    public $address;
    public $email;
    public $logo;
    public $user_id;


    public function rules()
    {
        return [
            'name' => 'required|min:2|max:255|string',
            'phone' => 'required|min:8|string|unique:businesses,phone',
            'address' => 'required|min:2|max:255|string',
            'email' => 'required|email|max:255|string',
            'logo' => 'nullable|image|max:2048',
        ];
    }

    public function submit()
    {
        $this->validate();
        $logo_path =  null;
        if ($this->logo) {
            $logo_path = $this->logo->store('logos', 'public');
        }

        $business = Business::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'logo_path' => $logo_path,
        ]);
        session()->flash('success', 'Business ' . $business->name . ' was created successfully!!');
        return redirect()->route('business_index', Auth::user());
    }

    public function render()
    {
        return view('livewire.business-create');
    }
}
