<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataStatus = ['Menunggu Persetujuan', 'Disetujui'];

        foreach ($dataStatus as $data) {
            Status::updateOrInsert(['name' => $data]);
        }
    }
}
