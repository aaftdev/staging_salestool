<?php

namespace App\Http\Livewire\FeeStructure;

use App\Models\FeeStructure;
use App\Models\Program;
use Livewire\Component;

class Edit extends Component
{
    public $listeners = ['feeEdit' => 'feeEdit'];
    public $program_id;
    public $name;
    public $total_fee = 0;
    public $fees = [];
    public $i = 0;
    public $fee_id;

    public function render()
    {
        $programs = Program::all();
        return view('livewire.fee-structure.edit', compact('programs'));
    }

    public function feeEdit($id)
    {
        $fee = FeeStructure::findOrFail($id);
        $this->name = $fee->name;
        $this->code = $fee->code;
        $this->program_id = $fee->program_id;
        $this->total_fee = $fee->total_fee;
        $this->fee_id = $id;
        $this->fees = json_decode($fee->fees);
    }

    public function createName()
    {
        $this->name = Program::find($this->program_id)->name;
    }

    public function resetInput()
    {
        $this->program_id = "";
        $this->fees = [];
        $this->i = 0;
        $this->total_fee = 0;
        $this->name = "";
    }

    public function add($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->fees, $i);
    }

    public function remove($i)
    {
        unset($this->fees[$i]);
    }

    public function calculateAmount($i)
    {
        if (array_key_exists("percent", $this->fees[$i])) {
            if (is_numeric($this->fees[$i]["percent"])) {
                $amount = $this->total_fee * $this->fees[$i]['percent'];
                $amount = $amount / 100;
                $this->fees[$i]['amount'] = round($amount, 1);
            }
        }
    }
    public function calculatePercent($i)
    {
        if (array_key_exists("amount", $this->fees[$i])) {
            if (is_numeric($this->fees[$i]["amount"])) {
                $percent = $this->fees[$i]['amount'] / $this->total_fee;
                $percent = $percent * 100;
                $this->fees[$i]['percent'] = round($percent, 4);
            }
        }
    }

    public function save()
    {
        $this->validate([
            "name" => 'required',
            "program_id" => 'required',
            "total_fee" => 'required',
            "fees" => "required",
            "fees.*" => "required",
            "fees.*.*" => "required",
        ]);

        $fee = FeeStructure::find($this->fee_id);
        $fee->name = $this->name;
        $fee->program_id = $this->program_id;
        $fee->total_fee = $this->total_fee;

        if (!empty($this->fees)) {
            $fee->fees = json_encode($this->fees);

            $calculatedFee = 0;
            foreach ($this->fees as $key => $value) {
                $calculatedFee += round($this->fees[$key]['amount'], 1);
            }

            if (round($calculatedFee, 1) == round($this->total_fee, 1)) {
                if ($fee->update()) {
                    $this->resetInput();
                    $this->emit('feeRefresh');
                    session()->flash('success', 'Fee Strcutre Updated Successfully !!');
                }
            } else {
                session()->flash('danger', 'Kindly check your fee calculation !!');
            }
        } else {
            session()->flash('danger', 'Kindly provide Fee !!');
        }
    }
}
