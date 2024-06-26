<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'name', 'program_id', 'commence_date', 'code'];

    public function program($id)
    {
        return Program::find($id) ? Program::find($id) : new Program();
    }

    public function sale()
    {
        return $this->hasMany(Sale::Class);
    }
}
