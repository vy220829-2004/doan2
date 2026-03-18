<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Seed sản phẩm theo đúng danh mục mong muốn.
     * - Nếu sản phẩm đã tồn tại theo tên: cập nhật category_id + trạng thái.
     * - Nếu chưa có: tạo mới với slug ổn định (tự tránh trùng).
     */
    public function run(): void
    {
        $categoryIdsBySlug = Category::query()->pluck('id', 'slug');

        $veg = [
            'Khoai tây',
            'Cà chua',
            'Rau muống',
            'Xà lách',
            'Dưa leo',
            'Cà rốt',
            'Bắp cải',
            'Hành lá',
            'Gừng',
            'Khoai lang',
            'Củ xung',
            'Mướp đắng',
        ];

        $fruit = [
            'Chuối',
            'Táo xanh',
            'Nho',
            'Cam sành',
            'Bưởi da xanh',
            'Dưa hấu',
        ];

        $meat = [
            'Thịt heo ba rọi',
            'Thịt bò',
            // "Cá hồi phi lê" được seed thuộc danh mục "Cá"; phần filter "Thịt" vẫn hiển thị theo mapping.
            'Tôm sú',
            'Gà ta',
        ];

        $fish = [
            'Cá hồi phi lê',
        ];

        $definitions = [];

        foreach ($veg as $name) {
            $definitions[] = ['name' => $name, 'category_slug' => 'rau-cu'];
        }
        foreach ($fruit as $name) {
            $definitions[] = ['name' => $name, 'category_slug' => 'trai-cay'];
        }
        foreach ($meat as $name) {
            $definitions[] = ['name' => $name, 'category_slug' => 'thit'];
        }
        foreach ($fish as $name) {
            $definitions[] = ['name' => $name, 'category_slug' => 'ca'];
        }

        foreach ($definitions as $def) {
            $categoryId = $categoryIdsBySlug[$def['category_slug']] ?? null;
            if (!$categoryId) {
                // Nếu danh mục chưa tồn tại (trường hợp migrate/seed lẻ), tạo nhanh theo slug.
                $category = Category::query()->firstOrCreate(
                    ['slug' => $def['category_slug']],
                    ['name' => Str::title(str_replace('-', ' ', $def['category_slug'])), 'description' => null, 'image' => null]
                );
                $categoryId = $category->id;
                $categoryIdsBySlug[$def['category_slug']] = $categoryId;
            }

            $updatedCount = Product::query()
                ->where('name', $def['name'])
                ->update([
                    'category_id' => $categoryId,
                    'status' => 'in_stock',
                ]);

            if ($updatedCount > 0) {
                continue;
            }

            $baseSlug = Str::slug($def['name']);
            $slug = $baseSlug;
            $i = 2;
            while (Product::query()->where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $i;
                $i++;
            }

            Product::query()->create([
                'name' => $def['name'],
                'slug' => $slug,
                'category_id' => $categoryId,
                'description' => null,
                'price' => 100000,
                'stock' => 100,
                'status' => 'in_stock',
                'unit' => 'kg',
            ]);
        }
    }
}
