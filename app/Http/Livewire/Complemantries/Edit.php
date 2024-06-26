<?php

namespace App\Http\Livewire\Complemantries;

use App\Models\Complemantry;
use Livewire\Component;

class Edit extends Component
{
    public $listeners = ['complemantryEdit' => 'complemantryEdit'];
    public $name, $amount, $status, $complemantry_id;


    public function render()
    {
        return view('livewire.complemantries.edit');
    }

    public function complemantryEdit($id)
    {
        $complemantry = Complemantry::findOrFail($id);
        $this->name = $complemantry->name;
        $this->amount = $complemantry->amount;
        $this->status = $complemantry->status;
        $this->complemantry_id = $id;
    }

    public function resetInput()
    {
        $this->name = $this->amount = "";
    }

    public function update()
    {
        $this->validate([
            "name" => 'required|unique:complemantries,id,' . $this->complemantry_id,
            "amount" => 'required',
            "status" => 'required'
        ]);

        $complemantry = Complemantry::find($this->complemantry_id);
        $complemantry->name = $this->name;
        $complemantry->amount = $this->amount;
        $complemantry->status = $this->status;

        if ($complemantry->save()) {
            $this->resetInput();
            $this->emit('complemantryRefresh');
            session()->flash('success', 'Complemantry Prgoram Updated Successfully !!');
        }
    }
}
