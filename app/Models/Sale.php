<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Sale extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['id', 'name', 'email', 'counsellor', 'contact', 'program_id', 'batch_id', 'discount', 'amount', 'final_amount', 'payment_term', 'state', 'address', 'status', 'payment_type', 'txnid', 'paid_term', 'created_at', 'updated_at', 'mail_status', 'offer_status', 'location', 'user_id', 'payments', 'short_link', 'complementary_id', 'enrollment_no', 'otd', 'type', 'created_at', 'enrollment_date'];

    public function program($id)
    {
        $program =  Program::findOrFail($id);
        if (!empty($program)) {
            return $program;
        } else {
            return new Program();
        }
    }

    public function batch($id)
    {
        $batch =  Batch::findOrFail($id);
        if (!empty($batch)) {
            return $batch;
        } else {
            return new Batch();
        }
    }

    public function fee_structure($id)
    {
        if (FeeStructure::find($id)) {
            return FeeStructure::find($id);
        } else {
            return new FeeStructure();
        }
    }

    public function complemantry($id)
    {
        if ($complemantry = Complemantry::find($id)) {
            return $complemantry;
        } else {
            return new Complemantry();
        }
    }
}
