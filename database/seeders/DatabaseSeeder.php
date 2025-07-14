<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{


    public function run()
    {
        DB::table('categories')->truncate();
        // Admin kullanıcı oluştur
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['password' => Hash::make('secret')]
        );

        // Kategoriler
        $categories = [
            ['name' => 'Telefonlar', 'slug' => 'telefonlar'],
            ['name' => 'Bilgisayarlar', 'slug' => 'bilgisayarlar'],
            ['name' => 'Tabletler', 'slug' => 'tabletler'],
            ['name' => 'Aksesuarlar', 'slug' => 'aksesuarlar'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Ürünler
        $products = [
            [
                'name' => 'iPhone 14 Pro',
                'price' => 62999.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/iphone-akilli-telefon-ekran-elektronik-cihaz-11216260/',
                'category_id' => 1,
                'description' => 'Apple iPhone 14 Pro 128GB',
                'stock' => 50
            ],
            [
                'name' => 'Samsung Galaxy S23',
                'price' => 39999.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/kahverengi-ahsap-tablke-ustunde-siyah-samsung-android-akilli-telefon-214487/',
                'category_id' => 1,
                'description' => 'Samsung Galaxy S23 256GB',
                'stock' => 30
            ],
            [
                'name' => 'Nintendo Switch',
                'price' => 9999.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/isik-parlak-acik-hafif-6654174/',
                'category_id' => 4,
                'description' => 'Nintendo Switch Oyun Konsolu',
                'stock' => 25
            ],
            [
                'name' => 'MacBook Air M2',
                'price' => 44999.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/dizustu-bilgisayar-model-tam-boy-maket-metin-alani-8534039/',
                'category_id' => 2,
                'description' => 'Apple MacBook Air M2 512GB',
                'stock' => 20
            ],
            [
                'name' => 'Dell XPS 13',
                'price' => 38999.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/dizustu-bilgisayar-bag-baglanti-bilgisayar-6053287/',
                'category_id' => 2,
                'description' => 'Dell XPS 13 Intel i7 16GB RAM',
                'stock' => 15
            ],
            [
                'name' => 'iPad Pro 12.9"',
                'price' => 34999.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/apple-elma-kamera-ipad-13570174/',
                'category_id' => 3,
                'description' => 'iPad Pro 12.9" M2 256GB',
                'stock' => 18
            ],
            [
                'name' => 'Samsung Galaxy Tab S9',
                'price' => 22999.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/el-ipad-resim-fotograf-9866878/',
                'category_id' => 3,
                'description' => 'Galaxy Tab S9 Ultra AMOLED',
                'stock' => 22
            ],
            [
                'name' => 'AirPods Pro 2. Nesil',
                'price' => 6499.00,
                'img' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/MQD83?wid=532&hei=582&fmt=jpeg&qlt=95&.v=1660803972361',
                'category_id' => 4,
                'description' => 'Apple AirPods Pro 2. Nesil ANC',
                'stock' => 100
            ],
            [
                'name' => 'Logitech MX Master 3',
                'price' => 2999.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/isik-parlak-acik-hafif-7006951/',
                'category_id' => 4,
                'description' => 'Kablosuz Mouse - Logitech MX Master 3',
                'stock' => 40
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'price' => 7999.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/lenssiz-bir-kamera-govdesi-7789079/',
                'category_id' => 4,
                'description' => 'Sony Gürültü Engelleyici Bluetooth Kulaklık',
                'stock' => 35
            ],
            [
                'name' => 'iPhone 13',
                'price' => 42999.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/apple-aygitlarinin-sevgisini-kucaklamak-i-phone-ile-bir-yolculuk-16642986/',
                'category_id' => 1,
                'description' => 'Apple iPhone 13 128GB',
                'stock' => 40
            ],
            [
                'name' => 'Asus ROG Strix G15',
                'price' => 52999.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/yari-acilmis-dizustu-bilgisayar-masada-acik-2047905/',
                'category_id' => 2,
                'description' => 'Asus ROG Strix G15 Gaming Laptop',
                'stock' => 10
            ],
            [
                'name' => 'Lenovo ThinkPad X1 Carbon',
                'price' => 41999.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/ahsap-masada-dizustu-bilgisayar-kullanan-kisinin-ustten-gorunumu-32284386/',
                'category_id' => 2,
                'description' => 'Lenovo ThinkPad X1 Carbon i7',
                'stock' => 13
            ],
            [
                'name' => 'Apple Pencil 2',
                'price' => 2499.00,
                'img' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/MU8F2?wid=445&hei=445&fmt=jpeg&qlt=95&.v=1540596424185',
                'category_id' => 4,
                'description' => 'iPad için Apple Pencil (2. Nesil)',
                'stock' => 60
            ],
            [
                'name' => 'Samsung Galaxy Watch 6',
                'price' => 6499.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/islak-damlaciklar-apple-watch-akilli-saat-13598511/',
                'category_id' => 4,
                'description' => 'Samsung Akıllı Saat - Watch 6',
                'stock' => 45
            ],
            [
                'name' => 'iPad Air 5',
                'price' => 28999.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/apple-elma-kamera-ipad-13570174/',
                'category_id' => 3,
                'description' => 'Apple iPad Air 5. Nesil 256GB',
                'stock' => 27
            ],
            [
                'name' => 'HP Spectre x360',
                'price' => 38999.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/adam-kisi-eller-akilli-telefon-4084293/',
                'category_id' => 2,
                'description' => 'HP Spectre x360 Dönüştürülebilir Laptop',
                'stock' => 14
            ],
            [
                'name' => 'Xiaomi Pad 6',
                'price' => 11999.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/kadin-sanat-yatak-boyama-20554843/',
                'category_id' => 3,
                'description' => 'Xiaomi Pad 6 128GB',
                'stock' => 19
            ],
            [
                'name' => 'JBL Flip 6 Bluetooth Hoparlör',
                'price' => 2499.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/siyah-kablosuz-kulaklik-tutan-bir-kisi-9371408/',
                'category_id' => 4,
                'description' => 'Taşınabilir Bluetooth Hoparlör',
                'stock' => 50
            ],
            [
                'name' => 'OnePlus 11 5G',
                'price' => 29999.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/akilli-telefon-mor-teknoloji-cihaz-20074768/',
                'category_id' => 1,
                'description' => 'OnePlus 11 5G Snapdragon 8 Gen 2',
                'stock' => 33
            ],
            [
                'name' => 'Realme GT 3',
                'price' => 19999.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/dokulu-yuzey-uzerinde-zarif-cep-telefonu-31467202/',
                'category_id' => 1,
                'description' => 'Realme GT 3 240W Hızlı Şarj',
                'stock' => 37
            ],
            [
                'name' => 'Razer BlackShark V2',
                'price' => 1799.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/tilt-shift-dugmeler-butonlar-aydinlatma-8176505/',
                'category_id' => 4,
                'description' => 'Razer Oyuncu Kulaklığı - BlackShark V2',
                'stock' => 48
            ],
            [
                'name' => 'Google Pixel 7',
                'price' => 25999.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/akilli-telefon-teknoloji-rock-kaya-18831090/',
                'category_id' => 1,
                'description' => 'Google Pixel 7 128GB',
                'stock' => 40
            ],
            [
                'name' => 'Logitech G502 Hero',
                'price' => 1299.00,
                'img' => 'https://www.pexels.com/tr-tr/fotograf/karanlik-koyu-dugmeler-butonlar-13870515/',
                'category_id' => 4,
                'description' => 'Logitech G502 Hero Kablosuz Oyuncu Mouse',
                'stock' => 60
            ]

        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }
}
