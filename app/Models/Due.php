<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Due extends Model
{
    use HasFactory;
    protected $fillable = ['VchNo', 'VchDate', 'DueDate', 'LedgerName', 'EnrollmentNo', 'State', 'Batch', 'FeeHead', 'Amount', 'created_at', 'Course', 'MRP', 'Scholarship'];
}
