<?php

namespace Database\Seeders;

use App\Models\CourierCompanies\CourierCompany;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aramex = CourierCompany::updateOrCreate([
            'name' => 'Aramex'
        ], [
            'name' => 'Aramex',
            'status' => 'active',
            'params' => [
                ['key' => 'ProductGroup'],
                ['key' => 'ProductType'],
                ['key' => 'endpoint'],
                ['key' => 'AccountNumber'],
                ['key' => 'UserName'],
                ['key' => 'Password'],
                ['key' => 'AccountPin'],
                ['key' => 'AccountEntity'],
                ['key' => 'AccountCountryCode'],
                ['key' => 'Version'],
            ]
        ]);
    }
}
