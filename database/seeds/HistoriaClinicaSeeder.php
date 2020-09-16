<?php

use App\HistoriaClinica;
use Illuminate\Database\Seeder;

class HistoriaClinicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(HistoriaClinica::class,500)->create();
    }
}
