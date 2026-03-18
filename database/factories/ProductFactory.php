<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->randomElement([
            'Cà chua', 'Rau muống', 'Khoai tây', 'Táo xanh',
            'Xà lách', 'Dưa leo', 'Cà rốt', 'Bắp cải',
            'Nho', 'Chuối', 'Bưởi da xanh', 'Cam sành', 'Dưa hấu',
            'Hành lá', 'Gừng', 'Khoai lang', 'Củ xung', 'Mướp đắng',
            'Thịt heo ba rọi', 'Thịt bò', 'Cá hồi phi lê', 'Tôm sú', 'Gà ta',
        ]);

        $categorySlug = $this->resolveCategorySlugByProductName($name);

        $categoryId = Category::query()->where('slug', $categorySlug)->value('id');
        if ($categoryId === null) {
            $categoryId = Category::query()->create([
                'name' => ucfirst(str_replace('-', ' ', $categorySlug)),
                'slug' => $categorySlug,
                'description' => $this->faker->sentence(10),
            ])->id;
        }

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1, 1000),
            'category_id' => $categoryId,
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->randomFloat(2, 10000, 500000),
            'stock' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->randomElement(['in_stock', 'out_of_stock']),
            'unit' => $this->faker->randomElement(['kg', 'g', 'hộp', 'túi']),
        ];  
    }

    private function resolveCategorySlugByProductName(string $name): string
    {
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
            'Tôm sú',
            'Gà ta',
        ];

        if (in_array($name, $veg, true)) {
            return 'rau-cu';
        }

        if (in_array($name, $fruit, true)) {
            return 'trai-cay';
        }

        if ($name === 'Cá hồi phi lê') {
            return 'ca';
        }

        if (in_array($name, $meat, true)) {
            return 'thit';
        }

        // Fallback: nếu phát sinh tên mới thì cho vào "Thực phẩm khác".
        return 'thuc-pham-khac';
    }
}
