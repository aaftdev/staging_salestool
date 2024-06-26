<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public $listeners = ['userEdit' => 'userEdit'];
    public $name, $email, $location, $user_type, $user_id, $password;

    public function render()
    {
        return view('livewire.user.edit');
    }

    public function resetInput()
    {
        $this->name = $this->email = $this->location = $this->user_type = $this->user_id = $this->password =  "";
    }

    public function userEdit($id)
    {
        $this->user_id = $id;
        $user = User::findOrFail($id);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->location = $user->location;
        $this->user_type = $user->user_type;
        $this->password = '';
    }

    public function update()
    {
        $user = User::findOrFail($this->user_id);
        $user->name = $this->name;
        $user->email = $this->email;
        $user->location = $this->location;
        $user->user_type = $this->user_type;
        if (!empty($this->password)) {
            $this->validate([
                "password" => 'required|min:6|max:15',
            ]);

            $user->password = bcrypt($this->password);
        }
        if ($user->save()) {
            $this->resetInput();
            $this->emit('userRefresh');
            session()->flash('success', 'User Updated Successfully !!');
        }
    }
}
