<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
class CategoryProductSeeder extends Seeder

{
   
    public function run(): void
    {
        $categories = Category::all();
        $products = Product::all();

        $productsPerCategory = 3;

        
        foreach ($categories as $category) {
            // Sélectionner un ensemble aléatoire de produits
            $randomProducts = $products->random($productsPerCategory);

            // Attacher ces produits à la catégorie actuelle
            $category->products()->attach($randomProducts);
        }
    }
}
