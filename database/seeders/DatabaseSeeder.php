<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ── Admin User ──────────────────────────────────────────────
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@luxeshop.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'phone'    => '+92-300-0000000',
        ]);

        // ── Demo Customer ────────────────────────────────────────────
        User::create([
            'name'     => 'John Doe',
            'email'    => 'customer@luxeshop.com',
            'password' => Hash::make('password'),
            'role'     => 'customer',
            'phone'    => '+92-301-1234567',
            'address'  => '123 Main Street',
            'city'     => 'Karachi',
        ]);

        // ── Categories ───────────────────────────────────────────────
        $electronics = Category::create(['name' => 'Electronics',   'slug' => 'electronics',   'sort_order' => 1]);
        $fashion     = Category::create(['name' => 'Fashion',        'slug' => 'fashion',        'sort_order' => 2]);
        $home        = Category::create(['name' => 'Home & Garden',  'slug' => 'home-garden',    'sort_order' => 3]);
        $sports      = Category::create(['name' => 'Sports',         'slug' => 'sports',         'sort_order' => 4]);
        $books        = Category::create(['name' => 'Books',          'slug' => 'books',          'sort_order' => 5]);
        $beauty       = Category::create(['name' => 'Beauty',         'slug' => 'beauty',         'sort_order' => 6]);

        // ── Sub-categories ───────────────────────────────────────────
        Category::create(['name' => 'Smartphones',  'slug' => 'smartphones',  'parent_id' => $electronics->id]);
        Category::create(['name' => 'Laptops',      'slug' => 'laptops',      'parent_id' => $electronics->id]);
        Category::create(['name' => 'Accessories',  'slug' => 'accessories',  'parent_id' => $electronics->id]);
        Category::create(['name' => 'Men\'s Wear',  'slug' => 'mens-wear',    'parent_id' => $fashion->id]);
        Category::create(['name' => 'Women\'s Wear','slug' => 'womens-wear',  'parent_id' => $fashion->id]);

        // ── Products ─────────────────────────────────────────────────
        $products = [
            [
                'name'              => 'iPhone 15 Pro Max',
                'slug'              => 'iphone-15-pro-max',
                'short_description' => 'The most powerful iPhone ever with A17 Pro chip.',
                'description'       => 'Experience the pinnacle of smartphone technology with the iPhone 15 Pro Max. Featuring a titanium design, 48MP camera system, and the breakthrough A17 Pro chip.',
                'price'             => 499999,
                'sale_price'        => 459999,
                'sku'               => 'APL-IP15PM-001',
                'stock'             => 50,
                'category_id'       => $electronics->id,
                'brand'             => 'Apple',
                'images'            => ['products/Apple-iphone-15-Pro.jpg'],
                'rating'            => 4.8,
                'reviews_count'     => 245,
                'is_featured'       => true,
            ],
            [
                'name'              => 'Samsung Galaxy S24 Ultra',
                'slug'              => 'samsung-galaxy-s24-ultra',
                'short_description' => 'Ultimate Android flagship with 200MP camera.',
                'description'       => 'The Galaxy S24 Ultra redefines what a smartphone can do with its 200MP camera, built-in S Pen, and Snapdragon 8 Gen 3 chip.',
                'price'             => 429999,
                'sale_price'        => null,
                'sku'               => 'SAM-GS24U-001',
                'stock'             => 35,
                'category_id'       => $electronics->id,
                'brand'             => 'Samsung',
                'images'            => ['products/Samsung Galaxy S24 Ultra.jpg'],
                'rating'            => 4.7,
                'reviews_count'     => 189,
                'is_featured'       => true,
            ],
            [
                'name'              => 'Sony WH-1000XM5',
                'slug'              => 'sony-wh-1000xm5',
                'short_description' => 'Industry-leading noise cancelling headphones.',
                'description'       => 'The WH-1000XM5 headphones offer industry-leading noise cancellation with exceptional sound quality and 30-hour battery life.',
                'price'             => 89999,
                'sale_price'        => 74999,
                'sku'               => 'SNY-WH1KXM5-001',
                'stock'             => 80,
                'category_id'       => $electronics->id,
                'brand'             => 'Sony',
                'images'            => ['products/Sony WH-1000XM5.jpg'],
                'rating'            => 4.9,
                'reviews_count'     => 523,
                'is_featured'       => true,
            ],
            [
                'name'              => 'MacBook Pro 14" M3',
                'slug'              => 'macbook-pro-14-m3',
                'short_description' => 'Apple MacBook Pro with M3 chip, 14-inch Liquid Retina XDR.',
                'description'       => 'The MacBook Pro M3 delivers exceptional performance for professionals. With up to 22 hours battery life and the stunning Liquid Retina XDR display.',
                'price'             => 699999,
                'sale_price'        => null,
                'sku'               => 'APL-MBP14M3-001',
                'stock'             => 20,
                'category_id'       => $electronics->id,
                'brand'             => 'Apple',
                'images'            => ['products/MacBook Pro 14 M3.jpg'],
                'rating'            => 4.9,
                'reviews_count'     => 312,
                'is_featured'       => true,
            ],
            [
                'name'              => 'Nike Air Max 270',
                'slug'              => 'nike-air-max-270',
                'short_description' => 'Iconic Nike Air Max with 270° Air unit.',
                'description'       => 'The Nike Air Max 270 features Nike\'s largest heel Air unit yet for a supremely cushioned ride.',
                'price'             => 22999,
                'sale_price'        => 17999,
                'sku'               => 'NKE-AM270-001',
                'stock'             => 150,
                'category_id'       => $fashion->id,
                'brand'             => 'Nike',
                'images'            => ['products/Nike Air Max 270.jpg'],
                'rating'            => 4.6,
                'reviews_count'     => 432,
                'is_featured'       => true,
            ],
            [
                'name'              => 'Adidas Ultra Boost 23',
                'slug'              => 'adidas-ultra-boost-23',
                'short_description' => 'Premium running shoe with Boost midsole technology.',
                'description'       => 'Experience endless energy with Adidas Ultra Boost 23. Features BOOST midsole technology and a Primeknit+ upper.',
                'price'             => 26999,
                'sale_price'        => null,
                'sku'               => 'ADI-UB23-001',
                'stock'             => 75,
                'category_id'       => $fashion->id,
                'brand'             => 'Adidas',
                'images'            => ['products/Adidas Ultra Boost 23.jpg'],
                'rating'            => 4.7,
                'reviews_count'     => 287,
                'is_featured'       => false,
            ],
            [
                'name'              => 'Dyson V15 Detect',
                'slug'              => 'dyson-v15-detect',
                'short_description' => 'Most powerful Dyson cordless vacuum with laser dust detection.',
                'description'       => 'The Dyson V15 Detect automatically adapts suction to reveal invisible dust and accurately reports what it removes.',
                'price'             => 129999,
                'sale_price'        => 109999,
                'sku'               => 'DYS-V15D-001',
                'stock'             => 30,
                'category_id'       => $home->id,
                'brand'             => 'Dyson',
                'images'            => ['products/Dyson V15 Detect.jpg'],
                'rating'            => 4.8,
                'reviews_count'     => 198,
                'is_featured'       => true,
            ],
            [
                'name'              => 'LEGO Technic Bugatti',
                'slug'              => 'lego-technic-bugatti',
                'short_description' => 'Build the iconic Bugatti Chiron in LEGO Technic.',
                'description'       => 'A masterpiece of design and engineering — the LEGO Technic Bugatti Chiron features 3,599 pieces and authentic details.',
                'price'             => 34999,
                'sale_price'        => 29999,
                'sku'               => 'LGO-TCH-BGR-001',
                'stock'             => 45,
                'category_id'       => $home->id,
                'brand'             => 'LEGO',
                'images'            => ['products/LEGO Technic Bugatti.jpg'],
                'rating'            => 4.9,
                'reviews_count'     => 876,
                'is_featured'       => false,
            ],
        ];

        foreach ($products as $data) {
            Product::create($data);
        }

        // ── Coupons ──────────────────────────────────────────────────
        Coupon::create([
            'code'             => 'WELCOME10',
            'type'             => 'percentage',
            'value'            => 10,
            'minimum_order'    => 1000,
            'maximum_discount' => 5000,
            'usage_limit'      => 1000,
            'expires_at'       => now()->addYear(),
        ]);

        Coupon::create([
            'code'          => 'SAVE500',
            'type'          => 'fixed',
            'value'         => 500,
            'minimum_order' => 5000,
            'usage_limit'   => null,
            'expires_at'    => now()->addMonths(6),
        ]);

        Coupon::create([
            'code'             => 'FLASH20',
            'type'             => 'percentage',
            'value'            => 20,
            'minimum_order'    => 10000,
            'maximum_discount' => 15000,
            'usage_limit'      => 100,
            'expires_at'       => now()->addMonth(),
        ]);
    }
}
