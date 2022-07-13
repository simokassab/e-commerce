<?php

namespace Database\Seeders;

use App\Models\Label\Label;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Label::query()->truncate();

        Label::query()->insert([
            [
            'title' => json_encode(['en' => 'New','ar' => 'جديد']),
            'entity' => "category",
            'color' =>  "black",
            'key' =>  "new",
        ],

        [
            'title' => json_encode(['en' => '50%','ar' => '50%']),
            'entity' => "product",
            'color' =>  "Blue",
            'key' =>  "sale",
        ],

        [
            'title' => json_encode(['en' => 'Hot','ar' => 'ناري']),
            'entity' => "brands",
            'color' =>  "Red",
            'key' =>  "hot",
        ],
    ]);
    }
}
