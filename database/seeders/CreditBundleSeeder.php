<?php

namespace Database\Seeders;

use App\Models\CreditBundle;
use Illuminate\Database\Seeder;

class CreditBundleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $creditBundles = [
            ['name' => 'Mayfair', 'number_of_credits' => 4000, 'cost' => 1600, 'active' => true],
            ['name' => 'Regent', 'number_of_credits' => 1750, 'cost' => 800, 'active' => true],
            ['name' => 'Trafalgar', 'number_of_credits' => 750, 'cost' => 400, 'active' => true],
            ['name' => 'Bow', 'number_of_credits' => 250, 'cost' => 170, 'active' => true],
            ['name' => 'Pentonville', 'number_of_credits' => 100, 'cost' => 75, 'active' => true],
        ];

        foreach ($creditBundles as $creditBundle) {
            CreditBundle::create($creditBundle);
        }
    }
}
