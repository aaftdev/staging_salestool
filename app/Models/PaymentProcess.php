<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentProcess extends Model
{
    use HasFactory;
    protected $fillable = [
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
        'txnid'
    ];

    public function sale($id){
        return Sale::find($id);
    }
}
