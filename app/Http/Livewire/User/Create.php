<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use Livewire\Component;

class Create extends Component
{
    public $name, $email, $location, $user_type, $password;

    public function render()
    {
        return view('livewire.user.create');
    }

    public function resetInput()
    {
        $this->name = $this->email = $this->location = $this->user_type = $this->password = "";
    }

    public function save()
    {
        $this->validate([
            "name" => 'required',
            "email" => 'required|unique:users',
            "location" => 'required',
            "user_type" => 'required',
            "password" => 'required',
        ]);
        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->location = $this->location;
        $user->user_type = $this->user_type;
        $user->password = bcrypt($this->password);
        if ($user->save()) {
            $this->resetInput();
            $this->emit('userRefresh');
            session()->flash('success', 'User Added Successfully !!');
        }
    }
}
