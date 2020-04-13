<?php

use App\Vat;
use Illuminate\Database\Seeder;

class VatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vat::create(["value" => 0, "is_default" => true]);
        Vat::create(["value" => .05]);
        Vat::create(["value" => .07]);
        Vat::create(["value" => .10]);
        Vat::create(["value" => .20]);
    }
}
