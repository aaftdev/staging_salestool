<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $id = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17'];
        $name = ['Diploma in Animation & VFX', 'Diploma in Art of Makeup', 'Diploma in Fashion Design', 'Diploma in Interior Design', 'Diploma in Jewelry Design', 'Diploma in Journalism & Mass Communication', 'Diploma in Music Production', 'Diploma in Nutrition and Dietetics', 'Diploma in Photography', 'Certification In Acting', 'Certification In Photography Foundation', 'Certification In Fashion Photography', 'Diploma in Luxury Brand Management', 'Certification In Smartphone Photography', 'Certification in Product Photography', 'Digital Advertising', 'Certification in Wildlife Photography'];
        $code = ['aaftonline-dia-05', 'aaftonline-dam-09', 'aaftonline-dfd-02', 'aaftonline-did-07', 'aaftonline-djd-08', 'aaftonline-djmc-03', 'aaftonline-dmp-06', 'aaftonline-dnd-04', 'aaftonline-dip-01', 'aaftonline-acting-01', 'aaftonline-PF-01', 'aaftonline-FP-01', 'dip-luxury-brand-management', 'aaftonline-spp-01', 'aaftonline-Product-01', 'aaftonline-01', 'aaftonline-wp-01'];
        $documents = ['{"About Us":"pdf\/D5pQWFHP6FO2lf9rfuyr1mrYO6p0cuDB5RrPe80n.pdf","EarlySalary_No Cost EMI":"pdf\/SkLju4AaiP7jlVmXbapeKijLrP1DqREq7IhWM3Ze.pdf","Program Brochure - Diploma in Animation and VFX":"pdf\/U9KYpm8CoxbVMxgjXsoTbKi6sDsgDoICILip7PtQ.pdf"}', '{"About Us":"pdf\/u4ZnzylgA8iNxhi6JPJHEYkpKZCHY7DxCIMKtBjT.pdf","Diploma in Art of Make Up":"pdf\/hGEJ16KTAvuJEKKnijGhH0Yf0feWRpTNuWmfum81.pdf","EarlySalary_No Cost EMI":"pdf\/GOn3emBf5OZK4c6fq5X8TaT7UWzffSP6aTo1VEJr.pdf"}', '{"About Us":"pdf\/VMig7b2RjnJ4QCX6UkgbZzgTPeZQCuuHYHkNEpD7.pdf","EarlySalary_No Cost EMI":"pdf\/0utRMSXm2tNbLnM2C7GF473SdXAdeFzg6Lq4RF4U.pdf","Program Brochure - Diploma in Fashion Design":"pdf\/eL2g8n3Zy1ys507hdoLuatJvCcwPVjdAiuCW6Gip.pdf","Transition Handbook":"pdf\/d9u3LYVVnoFd1umD7zSL9oKQRT2ifTEMAjxodqkL.pdf"}', '{"About Us":"pdf\/hDGD6A9qBMunnMRuOExV0unj0vri1ITnI21vgcTG.pdf","Diploma in Interior Design":"pdf\/A8VpnzmKH57Ew0r9XlIigcKIHnmX9BTxUQwlALjC.pdf","EarlySalary_No Cost EMI":"pdf\/lxjjZ3KCTWtYaNbzbjziW9XaCaOL0IEMAZI7QY1p.pdf"}', '{"About Us":"pdf\/bBMYsvfhvJrMRMLNDSVR59jh6WHdtehV0leOBpZK.pdf","EarlySalary_No Cost EMI":"pdf\/jB9IZGxwc3i3mj6FnaqEIdQ1zsssXW40mBHYbP3K.pdf","Program Brochure_Diploma in Jewelry Design":"pdf\/4BEMAJmeUIQ9S415EDbTQVj2FipJi4ZkffQoHfUb.pdf"}', '{"About Us":"pdf\/a5CBfwZK1bSc9g4b8PSdCBpqQflOeeX3zFrOGJpq.pdf","EarlySalary_No Cost EMI":"pdf\/AslW40RG9yddNMVgVe0O5vjRMidBklVHAFPQiGcN.pdf","Program Brochure - Diploma in Journalism _ Mass Communication":"pdf\/ALKzrihIESTUKqpszvNcGJ5JuIif0TO2ZDm29Pa2.pdf","Transition Handbook":"pdf\/H4lxg4WQw1RoaEfnq1XFajh5B562rNMnI0jREZ83.pdf"}', '{"About Us":"pdf\/lKVBt64kDI6gmo5fhfiyt6JqwA3CmK4jDPBnVNql.pdf","EarlySalary_No Cost EMI":"pdf\/jMNOhYA5d77turrQnGirSz0rpnTafcoIw01tD8fM.pdf","Program Brochure_Diploma in Music Production":"pdf\/wizPtH3cmMrielKoeyr1CuernJO6BDsoyXFei4B7.pdf"}', '{"About Us":"pdf\/PMipsdbb3Fi37gK3CJpzPCPTZLAtL7SUQJ1h4602.pdf","EarlySalary_No Cost EMI":"pdf\/t8AQrBHikdk4Xctf1pvlbfsUF2GFKg0fcTzSvU14.pdf","Program Brochure - Diploma in Nutrition and Dietetics":"pdf\/DJ2FObMSgkCVbPYIhflhWCbdJr1gNhqnbrUvfL6o.pdf"}', '[]', '[]', '[]', '[]', '{"About Us":"pdf\/LdR5w22mpiDuAc6N11L0GYDL6nHpYfriW6hD7C4t.pdf","Program Brochure - Luxury Brand Management":"pdf\/kt6igZmkHHNb3GelzWkGgMAUqSS6GmXTjXmWY9zZ.pdf"}', '[]', '[]', '[]', '[]'];
        $created_at = ['2022-04-08T04:40:34.000000Z', '2022-04-08T04:40:35.000000Z', '2022-04-08T04:40:35.000000Z', '2022-04-08T04:40:35.000000Z', '2022-04-08T04:40:35.000000Z', '2022-04-08T04:40:35.000000Z', '2022-04-08T04:40:35.000000Z', '2022-04-08T04:40:35.000000Z', '2022-04-08T04:40:35.000000Z', '2022-04-08T04:40:35.000000Z', '2022-04-08T04:40:35.000000Z', '2022-04-13T10:47:09.000000Z', '2022-04-20T06:23:39.000000Z', '2022-04-20T10:36:52.000000Z', '2022-04-23T06:45:25.000000Z', '2022-04-26T11:17:59.000000Z', '2022-04-26T13:41:55.000000Z'];
        $updated_at = ['2022-04-20T05:41:06.000000Z', '2022-04-20T05:43:40.000000Z', '2022-04-20T05:45:21.000000Z', '2022-04-20T05:46:38.000000Z', '2022-04-20T05:47:43.000000Z', '2022-04-20T05:49:16.000000Z', '2022-04-14T11:44:53.000000Z', '2022-04-20T05:51:25.000000Z', '2022-04-08T04:43:52.000000Z', '2022-04-08T04:43:52.000000Z', '2022-04-08T04:43:52.000000Z', '2022-04-23T06:44:46.000000Z', '2022-04-20T06:23:39.000000Z', '2022-04-20T10:36:52.000000Z', '2022-04-23T06:45:25.000000Z', '2022-04-26T11:17:59.000000Z', '2022-04-26T13:43:06.000000Z'];

        for ($key = 0; $key < count($id); $key++) {
            Program::create([
                "id" => $id[$key],
                "name" => $name[$key],
                "code" => $code[$key],
                "documents" => $documents[$key],
                "created_at" => $created_at[$key],
                "updated_at" => $updated_at[$key],
            ]);
        }
    }
}
