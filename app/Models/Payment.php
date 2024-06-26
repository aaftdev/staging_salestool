<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\map;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'email',
        'contact',
        'address',
        'state',
        'program_name',
        'batch_name',
        'sale_id',
        'other',
        'paid',
        'fees',
        'other',
        'txnid',
        "mode",
        "user_id",
        "reference_date",
        "created_at",
    ];

    public function sale($id)
    {
        return Sale::find($id) ? Sale::find($id) : new Sale();
    }
}
