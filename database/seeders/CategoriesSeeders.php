<?php

namespace Database\Seeders;

use App\Models\Category\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'books',
                'code' => 'books101',
                'slug' => 'books',
                'description' => 'this is a category of books'
            ],
            [

            ],
        ];

        Category::query()->insert($data);
    }
}
