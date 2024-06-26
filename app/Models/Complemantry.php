<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complemantry extends Model
{
    use HasFactory;
    // protected $table = ['complemantries'];
    protected $fillable = ['name', 'amount', 'status'];
}
