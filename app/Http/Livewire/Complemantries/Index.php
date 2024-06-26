<?php

namespace App\Http\Livewire\Complemantries;

use App\Models\Complemantry;
use Livewire\Component;

class Index extends Component
{
    public $listeners = ['complemantryRefresh' => '$refresh'];
    public $complemantries;

    public function mount()
    {
        $this->complemantries = Complemantry::latest()->get();
    }
    public function render()
    {

        return view('livewire.complemantries.index');
    }
    public function edit($id)
    {
        $this->emit('complemantryEdit', $id);
    }

    public function delete($id)
    {
        $complemantry = Complemantry::find($id);

        if ($complemantry->delete()) {
            $this->complemantries = Complemantry::all();
            session()->flash('success', 'Complemantry Program Deleted Successfully !!');
        }
    }
}
