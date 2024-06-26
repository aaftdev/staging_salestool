<?php

namespace Database\Seeders;

use App\Models\Batch;
use Illuminate\Database\Seeder;

class BatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $id = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'];
        $program_id = ['9', '9', '9', '3', '3', '3', '3', '6', '6', '8', '8', '8', '8', '8', '1', '7', '7', '7', '4', '4', '4', '5', '10', '11', '12', '7', '14', '8', '4', '1', '17'];
        $name = ['Diploma in Photography - Aug 2021', 'Diploma in Photography - Nov 2021', 'Diploma in Photography - Feb 2022', 'Diploma in Fashion Design - Aug 2021', 'Diploma in Fashion Design - Nov 2021', 'Diploma in Fashion Design - Jan 2022', 'Diploma in Fashion Design - Mar 2022', 'Diploma in Journalism & Mass Communication - Sept 2021', 'Diploma in Journalism & Mass Communication - Jan 2022', 'Diploma in Nutrition and Dietetics - Nov 2021', 'Diploma in Nutrition and Dietetics - Dec 2021', 'Diploma in Nutrition and Dietetics - Jan 2022', 'Diploma in Nutrition and Dietetics - Feb 2022', 'Diploma in Nutrition and Dietetics - Mar 2022', 'Diploma in Animation & VFX - Feb 2022', 'Diploma in Music Production - Dec 2021', 'Diploma in Music Production - Jan 2022', 'Diploma in Music Production - Feb 2022', 'Diploma in Interior Design - Jan 2022', 'Diploma in Interior Design - Feb 2022', 'Diploma in Interior Design - Mar 2022', 'Diploma in Jewelry Design - Mar 2022', 'Self Paced - Acting', 'Self Paced - Photography Foundation', 'Self Paced - Fashion Photography', 'Diploma in Music Production - April 2022', 'Self Paced - Smartphone Photography', 'Diploma in Nutrition and Dietetics - April 2022', 'Diploma in Interior Design - May 2022', 'Diploma in Animation and VFX - May 2022', 'Self Paced - Wildlife Photography'];
        $commence_date = ['2021-08-20 00:00:00', '2021-11-20 00:00:00', '2022-02-19 00:00:00', '2021-08-31 00:00:00', '2021-11-27 00:00:00', '2022-01-22 00:00:00', '2022-03-26 00:00:00', '2021-09-27 00:00:00', '2022-01-15 00:00:00', '2021-11-13 00:00:00', '2021-12-11 00:00:00', '2022-01-29 00:00:00', '2022-02-26 00:00:00', '2022-03-26 00:00:00', '2022-02-19 00:00:00', '2021-12-12 00:00:00', '2022-01-15 00:00:00', '2022-02-26 00:00:00', '2022-01-08 00:00:00', '2022-02-12 00:00:00', '2022-03-19 00:00:00', '2022-03-26 00:00:00', '2022-04-05 00:00:00', '2022-04-06 00:00:00', '2022-04-13 00:00:00', '2022-04-02 00:00:00', '2022-04-20 00:00:00', '2022-04-30 00:00:00', '2022-05-07 00:00:00', '2022-05-14 00:00:00', '2022-04-26 00:00:00'];
        $created_at = ['2022-04-08T05:01:31.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:33.000000Z', '2022-04-08T05:01:33.000000Z', '2022-04-08T05:01:33.000000Z', '2022-04-08T05:01:33.000000Z', '2022-04-08T05:01:33.000000Z', '2022-04-08T05:01:33.000000Z', '2022-04-08T05:01:33.000000Z', '2022-04-08T05:01:33.000000Z', '2022-04-08T05:01:33.000000Z', '2022-04-13T10:48:54.000000Z', '2022-04-14T09:40:21.000000Z', '2022-04-20T10:37:39.000000Z', '2022-04-21T10:06:47.000000Z', '2022-04-22T05:54:02.000000Z', '2022-04-26T11:09:21.000000Z', '2022-04-26T13:51:13.000000Z'];
        $updated_at = ['2022-04-08T05:01:31.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:32.000000Z', '2022-04-08T05:01:33.000000Z', '2022-04-08T05:01:33.000000Z', '2022-04-08T05:01:33.000000Z', '2022-04-08T05:01:33.000000Z', '2022-04-08T05:01:33.000000Z', '2022-04-08T05:01:33.000000Z', '2022-04-08T05:01:33.000000Z', '2022-04-08T05:01:33.000000Z', '2022-04-08T05:01:33.000000Z', '2022-04-13T10:48:54.000000Z', '2022-04-14T09:40:21.000000Z', '2022-04-20T10:37:39.000000Z', '2022-04-21T10:06:47.000000Z', '2022-04-22T05:54:02.000000Z', '2022-04-26T11:09:32.000000Z', '2022-04-26T13:51:13.000000Z'];

        foreach ($id as $key => $value) {
            Batch::create([
                "id" => $value,
                "program_id" => $program_id[$key],
                "name" => $name[$key],
                "commence_date" => $commence_date[$key],
                "created_at" => $created_at[$key],
                "updated_at" => $updated_at[$key],
            ]);
        }
    }
}
