<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrolled extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'AdmissionNo',
        'Name',
        'LedgerName',
        'AdmissionDate',
        'CourseName',
        'BatchName',
        'Session',
        'Status',
        'Location',
        'Email',
        'Mobile',
        'Address',
        'State',
        'created_at',
        "importedflag",
    ];
}
