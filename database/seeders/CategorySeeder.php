<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Rau củ', 'slug' => 'rau-cu', 'description' => 'Các loại rau củ tươi ngon', 'image' => 'uploads/users/categories/rau-cu.png'],
            ['name' => 'Trái cây', 'slug' => 'trai-cay', 'description' => 'Trái cây sạch, tươi ngon', 'image' => 'uploads/users/categories/trai-cay.png'],
            ['name' => 'Thịt', 'slug' => 'thit', 'description' => 'Các loại thịt tươi ngon, đảm bảo chất lượng', 'image' => 'uploads/users/categories/thit.png'],
            ['name' => 'Cá', 'slug' => 'ca', 'description' => 'Hải sản và các loại cá tươi sống', 'image' => 'uploads/users/categories/ca.png'],
            ['name' => 'Thực phẩm khác', 'slug' => 'thuc-pham-khac', 'description' => 'Các loại thực phẩm bổ sung khác', 'image' => 'uploads/users/categories/thuc-pham-khac.png'],

        ];

        $now = now();

        $rows = array_map(
            fn (array $category) => array_merge($category, ['created_at' => $now, 'updated_at' => $now]),
            $categories
        );

        DB::table('categories')->upsert(
            $rows,
            ['slug'],
            ['name', 'description', 'image', 'updated_at']
        );
    }
}
