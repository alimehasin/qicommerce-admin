<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'MacBook Pro 16" M3 Pro',
                'description' => 'Latest Apple MacBook Pro with M3 Pro chip, 16GB RAM, 512GB SSD, and 16-inch Liquid Retina XDR display.',
                'price' => 3_748_500,
                'stock' => 15,
                'sku' => 'MBP-16-M3-001',
                'status' => 'active',
                'image_path' => 'products/mac-preview.jpg',
                'images' => [
                    'products/mac-0.jpg',
                    'products/mac-1.jpg',
                    'products/mac-2.jpg',
                ]
            ],
            [
                'name' => 'iPhone 15 Pro Max',
                'description' => 'Apple iPhone 15 Pro Max with A17 Pro chip, 256GB storage, and titanium design.',
                'price' => 1_800_000,
                'stock' => 25,
                'sku' => 'IPH-15-PM-001',
                'status' => 'active',
                'image_path' => 'products/iphone-preview.jpg',
                'images' => [
                    'products/iphone-0.jpg',
                    'products/iphone-1.jpg',
                    'products/iphone-2.jpg',
                ]
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'description' => 'Premium noise-cancelling wireless headphones with exceptional sound quality and comfort.',
                'price' => 599_000,
                'stock' => 30,
                'sku' => 'SNY-WH-001',
                'status' => 'active',
                'image_path' => 'products/sony-preview.jpg',
                'images' => [
                    'products/sony-0.jpg',
                    'products/sony-1.jpg',
                    'products/sony-2.jpg',
                ]
            ],
            [
                'name' => 'Samsung 65" QLED 4K TV',
                'description' => '65-inch Samsung QLED 4K Smart TV with Quantum HDR and built-in Alexa.',
                'price' => 2_248_500,
                'stock' => 10,
                'sku' => 'SMS-QLED-001',
                'status' => 'active',
                'image_path' => 'products/tv-preview.jpg',
                'images' => [
                    'products/tv-0.jpg',
                    'products/tv-1.jpg',
                    'products/tv-2.jpg',
                ]
            ],
            [
                'name' => 'Nintendo Switch OLED',
                'description' => 'Nintendo Switch OLED model with 7-inch OLED screen, enhanced audio, and 64GB internal storage.',
                'price' => 523_500,
                'stock' => 20,
                'sku' => 'NIN-SW-001',
                'status' => 'active',
                'image_path' => 'products/nintendo-preview.jpg',
                'images' => [
                    'products/nintendo-0.jpg',
                    'products/nintendo-1.jpg',
                    'products/nintendo-2.jpg',
                ]
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'description' => 'Flagship Android smartphone with S Pen, 200MP camera system, and Snapdragon 8 Gen 3 processor.',
                'price' => 1_798_500,
                'stock' => 15,
                'sku' => 'SMS-S24-001',
                'status' => 'active',
                'image_path' => 'products/galaxy-preview.jpg',
                'images' => [
                    'products/galaxy-0.jpg',
                    'products/galaxy-1.jpg',
                    'products/galaxy-2.jpg',
                ]
            ],
            [
                'name' => 'iPad Pro 12.9" M2',
                'description' => '12.9-inch iPad Pro with M2 chip, 256GB storage, and Liquid Retina XDR display.',
                'price' => 1_648_500,
                'stock' => 18,
                'sku' => 'IPD-PRO-001',
                'status' => 'active',
                'image_path' => 'products/tab-preview.jpg',
                'images' => [
                    'products/tab-0.jpg',
                    'products/tab-1.jpg',
                    'products/tab-2.jpg',
                ]
            ],
            [
                'name' => 'Bose QuietComfort Earbuds II',
                'description' => 'True wireless earbuds with active noise cancellation and premium sound quality.',
                'price' => 449_000,
                'stock' => 25,
                'sku' => 'BOS-QC-001',
                'status' => 'active',
                'image_path' => 'products/bose-preview.jpg',
                'images' => [
                    'products/bose-0.jpg',
                    'products/bose-1.jpg',
                    'products/bose-2.jpg',
                ]
            ],
            [
                'name' => 'Canon EOS R6 Mark II',
                'description' => 'Full-frame mirrorless camera with 24.2MP sensor and 4K video recording.',
                'price' => 3_748_500,
                'stock' => 8,
                'sku' => 'CAN-R6-001',
                'status' => 'active',
                'image_path' => 'products/cam-preview.jpg',
                'images' => [
                    'products/cam-0.jpg',
                    'products/cam-1.jpg',
                    'products/cam-2.jpg',
                ]
            ],
            [
                'name' => 'Samsung Galaxy Watch 6 Pro',
                'description' => 'Premium smartwatch with Wear OS, advanced health monitoring, and rotating bezel.',
                'price' => 673_500,
                'stock' => 20,
                'sku' => 'SGW-6P-001',
                'status' => 'active',
                'image_path' => 'products/watch-preview.jpg',
                'images' => [
                    'products/watch-0.jpg',
                    'products/watch-1.jpg',
                    'products/watch-2.jpg',
                ]
            ]
        ];

        foreach ($products as $productData) {
            // Extract images before creating product
            $images = $productData['images'] ?? [];
            unset($productData['images']);
            
            // Create the product
            $product = Product::create($productData);
            
            // Create the product images
            foreach ($images as $imagePath) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath
                ]);
            }
        }
    }
}
