<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'code', 'documents', 'term'];

    public function sale()
    {
        return $this->hasMany(Sale::Class);
    }
}
