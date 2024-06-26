<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    use HasFactory;

    protected $fillable = ["id","name","program_id","total_fee","fees","created_at","updated_at"];

    public function program($id){
        if(Program::find($id)){
            return Program::find($id)->name;
        }
    }
}
