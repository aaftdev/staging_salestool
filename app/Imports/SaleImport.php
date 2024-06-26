<?php

namespace App\Imports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\ToModel;

class SaleImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Sale([
            'id' => $row[0],
            'name' => $row[1],
            'email' => $row[2],
            'counsellor' => $row[3],
            'contact' => $row[4],
            'program_id' => $row[5],
            'batch_id' => $row[6],
            'discount' => $row[7],
            'amount' => $row[8],
            'final_amount' => $row[9],
            'payment_term' => $row[10],
            'state' => $row[11],
            'address' => $row[12],
            'status' => !empty($row[13]) ? $row[13] : 0,
            'payment_type' => !empty($row[14]) ? $row[14] : 0,
            'txnid' => $row[15],
            'paid_term' => $row[16],
            'created_at' => $row[17],
            'updated_at' => $row[18],
            'mail_status' => $row[19],
            'offer_status' => $row[20],
            'location' => $row[21],
            'user_id' => $row[22],
            'payments' => $row[23],
            'short_link' => $row[24],
            // 'complementary_id' => $row[25],
            // 'enrollment_no' => $row[26],
            // 'otd' => $row[27],
        ]);
    }
}
