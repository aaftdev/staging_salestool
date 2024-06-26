<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;
    protected $fillable = [
        "VchNo",
        "VchDate",
        "LedgerName",
        "State",
        "EnrollmentNo",
        "State",
        "BatchStartDate",
        "Batch",
        "FeeHead",
        "Amount",
        "PaymentMode",
        "ReferenceNo",
        "ReferenceDate",
        "BankName",
        "term",
        "entry_type",
        "created_at",
        "updated_at",
    ];
}
