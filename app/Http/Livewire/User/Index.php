<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public $listeners = ['userRefresh' => '$refresh'];
    public $user, $users;

    public function mount()
    {
        $this->users = User::latest()->get();
    }

    public function render()
    {
        return view('livewire.user.index');
    }

    public function edit($id)
    {
        $this->emit('userEdit', $id);
    }

    public function delete($id)
    {
        $this->user = User::findorFail($id);
    }

    public function userDelete()
    {
        $this->user = '';
    }

    public function userPermanentDelete()
    {
        if ($this->user->delete) {
            session()->falsh('success', 'User Deleted Successfully !!!');
            $this->user = "";
            $this->users = User::latest()->get();
        }
    }
}
