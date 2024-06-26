<?php

namespace App\Http\Livewire\Complemantries;

use App\Models\Complemantry;
use Livewire\Component;

class Create extends Component
{
    public $name, $amount, $status;

    public function render()
    {
        return view('livewire.complemantries.create');
    }
    public function resetInput()
    {
        $this->name = $this->amount = "";
    }

    public function save()
    {
        $this->validate([
            "name" => 'required|unique:batches',
            "amount" => 'required|numeric',
            "status" => 'required',
        ]);

        Complemantry::create([
            "name" => $this->name,
            "amount" => $this->amount,
            "status" => $this->status,
        ]);

        $this->resetInput();
        $this->emit('complemantryRefresh');
        session()->flash('success', 'Complemantry Program Saved Successfully !!');
    }
}
