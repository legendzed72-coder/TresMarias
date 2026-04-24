<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class BreadSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $classic = Category::firstOrCreate(['name' => 'Classic Breads'], ['description' => 'Traditional Filipino pan de sal and everyday breads', 'is_active' => true]);
        $pastries = Category::firstOrCreate(['name' => 'Pastries & Sweet Breads'], ['description' => 'Sweet filled breads and pastries', 'is_active' => true]);
        $cakes = Category::firstOrCreate(['name' => 'Cakes & Rolls'], ['description' => 'Soft cakes, rolls, and loaves', 'is_active' => true]);
        $specialty = Category::firstOrCreate(['name' => 'Specialty Items'], ['description' => 'Premium and seasonal baked goods', 'is_active' => true]);

        $images = [
            '08c28709-c91b-41dd-a68a-064a9bee01a7.jpg',
            '1037f3ed-0b8a-481d-ad30-a10e27730105.jpg',
            '12c7084c-673d-4b92-91ca-21503e3dd07a.jpg',
            '13a79bbc-a320-40ea-80e8-af8162bf1275.jpg',
            '2119c8e6-8f86-4c93-bc62-e05bb9f32d04.jpg',
            '243b6f83-eaa5-45ca-a19d-cd50e0f246af.jpg',
            '2e4fedcd-e491-4fab-91ea-ac920d7e02ac.jpg',
            '32bdd3b7-20f7-44f2-96f4-9d2d2256d415.jpg',
            '364fa352-2cbd-42e1-8f2a-3a8ea9c2633e.jpg',
            '4b8c8066-d1ef-43c3-8d20-09495d9acd0d.jpg',
            '5063a56a-c37d-4668-ae92-8776ff40b9fb.jpg',
            '545b7dcf-4041-4118-82c6-32d48d6841b1.jpg',
            '5aecfd0c-e9bd-4b92-bd26-a58e743314bf.jpg',
            '5fbeb97c-ebd3-484b-a8de-2a280624ffc7.jpg',
            '650104ca-2c94-44df-9d81-d9477465f794.jpg',
            '6512324d-1e4f-4db9-963c-98488c629bf2.jpg',
            '69aad7b3-ee08-4de4-aa7a-dbfe0edac55c.jpg',
            '74e3c677-df36-4c45-9998-239538eddaec.jpg',
            '88151ddd-ab5f-4609-87df-1cb50dc13651.jpg',
            '8caaefd7-a9b2-4dbe-a30f-171ee2775bff.jpg',
            '8f9471c3-fba5-4c15-881c-a83ccb45d885.jpg',
            '98598f99-4fe0-4a21-a437-b758e5c74973.jpg',
            'b10f038d-fe48-4b60-b53b-ca5a136eeabb.jpg',
            'b5c7b2f6-2807-4690-a983-a1946ec0545d.jpg',
            'b79fc6a5-c660-44c5-bf4c-e1283bcbc739.jpg',
            'b80d10c7-c75e-47f1-b865-690a1850f560.jpg',
            'c783095c-782e-420c-ad70-ab92d4fdd32a.jpg',
            'c85945ab-3af3-4082-902f-daa75f181be5.jpg',
            'ca4b3cf2-b691-4b4a-b2db-c98a6150fcb8.jpg',
            'cb5d991b-b49c-4b77-b3ae-79066be1f356.jpg',
            'cb6e581e-f758-4071-a49c-c8af4ed5814f.jpg',
            'cc24b2c8-91b3-4dc9-a231-bb141884e117.jpg',
            'ce2dd6dd-5fcb-44ee-8787-edfb9f40a858.jpg',
            'd386c3ec-5db9-4bbc-b1df-6d9f31613ff0.jpg',
            'd62568a5-5fbb-479b-9e65-8eb955aa6177.jpg',
            'd90a1665-d957-4340-aea9-e0df1629650c.jpg',
            'e268e5f3-ea63-41b2-bba5-139e3533e44b.jpg',
            'e4c87f9e-cb94-4412-b40c-7af39ac3d1e6.jpg',
            'e4f14a34-01de-4b05-b09c-25b5cfd889aa.jpg',
            'e599551d-cee3-4b6d-874b-6b77c924a7bf.jpg',
            'ef5e3e71-ee42-490b-905c-a2c7bf856236.jpg',
            'f0782adb-de1f-4cfe-bcdb-5d1785c26c73.jpg',
        ];

        $breads = [
            // Classic Breads (12)
            ['category' => $classic, 'name' => 'Pan de Sal', 'description' => 'The iconic Filipino breakfast bread roll with a soft, fluffy interior and light breadcrumb coating.', 'price' => 5.00, 'cost_price' => 2.50, 'stock' => 200, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy']],
            ['category' => $classic, 'name' => 'Monay', 'description' => 'Oval-shaped bread with a slight sweetness and dense, chewy texture. A bakery staple.', 'price' => 8.00, 'cost_price' => 3.50, 'stock' => 100, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy', 'eggs']],
            ['category' => $classic, 'name' => 'Pinagong (Turtle Bread)', 'description' => 'Crispy-topped bread shaped like a turtle shell, crunchy outside and soft inside.', 'price' => 8.00, 'cost_price' => 3.00, 'stock' => 80, 'unit' => 'piece', 'allergens' => ['wheat']],
            ['category' => $classic, 'name' => 'Putok (Star Bread)', 'description' => 'Hard-crusted bread with a burst-open top, perfect for dipping in coffee.', 'price' => 7.00, 'cost_price' => 2.80, 'stock' => 120, 'unit' => 'piece', 'allergens' => ['wheat']],
            ['category' => $classic, 'name' => 'Tasty Bread', 'description' => 'Classic white loaf bread sliced for sandwiches and toast. Soft and versatile.', 'price' => 65.00, 'cost_price' => 30.00, 'stock' => 40, 'unit' => 'loaf', 'allergens' => ['wheat', 'dairy', 'soy']],
            ['category' => $classic, 'name' => 'Pandesal de Queso', 'description' => 'Pan de sal stuffed with melted cheese — a savory breakfast favorite.', 'price' => 12.00, 'cost_price' => 5.50, 'stock' => 80, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy']],
            ['category' => $classic, 'name' => 'Baguette', 'description' => 'Crusty French-style bread with a light, airy crumb.', 'price' => 55.00, 'cost_price' => 25.00, 'stock' => 30, 'unit' => 'piece', 'allergens' => ['wheat']],
            ['category' => $classic, 'name' => 'Ciabatta Roll', 'description' => 'Italian-style bread roll with large air pockets, perfect for sandwiches.', 'price' => 35.00, 'cost_price' => 15.00, 'stock' => 40, 'unit' => 'piece', 'allergens' => ['wheat']],
            ['category' => $classic, 'name' => 'Pan de Coco', 'description' => 'Soft bread filled with sweetened shredded coconut. A beloved Filipino merienda.', 'price' => 10.00, 'cost_price' => 4.50, 'stock' => 90, 'unit' => 'piece', 'allergens' => ['wheat', 'coconut']],
            ['category' => $classic, 'name' => 'Kalihim', 'description' => 'Colorful Filipino bread layers with cheese topping, slightly sweet and soft.', 'price' => 10.00, 'cost_price' => 4.00, 'stock' => 60, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy', 'eggs']],
            ['category' => $classic, 'name' => 'Spanish Bread', 'description' => 'Rolled bread with a buttery sugar-breadcrumb filling, crunchy exterior.', 'price' => 10.00, 'cost_price' => 4.00, 'stock' => 100, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy']],
            ['category' => $classic, 'name' => 'Kababayan (Filipino Muffin)', 'description' => 'Hat-shaped golden muffin-bread with a buttery, slightly sweet taste.', 'price' => 10.00, 'cost_price' => 4.00, 'stock' => 80, 'unit' => 'piece', 'allergens' => ['wheat', 'eggs', 'dairy']],

            // Pastries & Sweet Breads (12)
            ['category' => $pastries, 'name' => 'Ensaymada', 'description' => 'Soft, buttery brioche-style bread topped with sugar and grated cheese.', 'price' => 25.00, 'cost_price' => 10.00, 'stock' => 50, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy', 'eggs']],
            ['category' => $pastries, 'name' => 'Ube Cheese Pandesal', 'description' => 'Purple yam-flavored pandesal with a gooey cheese center.', 'price' => 15.00, 'cost_price' => 7.00, 'stock' => 60, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy']],
            ['category' => $pastries, 'name' => 'Hopia Baboy', 'description' => 'Flaky pastry filled with sweet mung bean paste — classic Filipino hopia.', 'price' => 15.00, 'cost_price' => 6.00, 'stock' => 70, 'unit' => 'piece', 'allergens' => ['wheat', 'soy']],
            ['category' => $pastries, 'name' => 'Hopia Ube', 'description' => 'Flaky pastry with a creamy purple yam filling.', 'price' => 15.00, 'cost_price' => 6.50, 'stock' => 70, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy']],
            ['category' => $pastries, 'name' => 'Cheese Roll', 'description' => 'Sweet bread roll generously topped with grated cheese and sugar glaze.', 'price' => 20.00, 'cost_price' => 8.00, 'stock' => 50, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy', 'eggs']],
            ['category' => $pastries, 'name' => 'Cinnamon Roll', 'description' => 'Swirled bread with cinnamon-sugar filling and cream cheese frosting.', 'price' => 45.00, 'cost_price' => 18.00, 'stock' => 30, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy', 'eggs']],
            ['category' => $pastries, 'name' => 'Cream Horn', 'description' => 'Cone-shaped flaky pastry filled with sweet vanilla cream.', 'price' => 25.00, 'cost_price' => 10.00, 'stock' => 40, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy', 'eggs']],
            ['category' => $pastries, 'name' => 'Pan de Regla', 'description' => 'Soft bread filled with a sweet red sugar-and-margarine spread.', 'price' => 10.00, 'cost_price' => 4.50, 'stock' => 80, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy']],
            ['category' => $pastries, 'name' => 'Ube Loaf', 'description' => 'Moist purple yam bread loaf — aromatic and naturally sweet.', 'price' => 85.00, 'cost_price' => 40.00, 'stock' => 20, 'unit' => 'loaf', 'allergens' => ['wheat', 'dairy', 'eggs']],
            ['category' => $pastries, 'name' => 'Banana Bread', 'description' => 'Dense, moist bread made with ripe saba bananas and a hint of cinnamon.', 'price' => 75.00, 'cost_price' => 35.00, 'stock' => 25, 'unit' => 'loaf', 'allergens' => ['wheat', 'eggs', 'dairy']],
            ['category' => $pastries, 'name' => 'Empanada', 'description' => 'Flaky pastry turnover filled with savory meat and vegetables.', 'price' => 25.00, 'cost_price' => 10.00, 'stock' => 50, 'unit' => 'piece', 'allergens' => ['wheat', 'eggs']],
            ['category' => $pastries, 'name' => 'Puto Cheese', 'description' => 'Steamed rice cake topped with cheese — soft, fluffy, and slightly sweet.', 'price' => 12.00, 'cost_price' => 5.00, 'stock' => 60, 'unit' => 'piece', 'allergens' => ['dairy']],

            // Cakes & Rolls (10)
            ['category' => $cakes, 'name' => 'Mamon', 'description' => 'Ultra-light and airy Filipino sponge cake with a buttery finish.', 'price' => 20.00, 'cost_price' => 8.00, 'stock' => 50, 'unit' => 'piece', 'allergens' => ['wheat', 'eggs', 'dairy']],
            ['category' => $cakes, 'name' => 'Brazo de Mercedes', 'description' => 'Meringue roll filled with rich custard cream — a Filipino celebration classic.', 'price' => 250.00, 'cost_price' => 120.00, 'stock' => 10, 'unit' => 'roll', 'allergens' => ['eggs', 'dairy']],
            ['category' => $cakes, 'name' => 'Ube Roll', 'description' => 'Soft sponge cake rolled with creamy purple yam filling.', 'price' => 220.00, 'cost_price' => 100.00, 'stock' => 10, 'unit' => 'roll', 'allergens' => ['wheat', 'eggs', 'dairy']],
            ['category' => $cakes, 'name' => 'Chocolate Cupcake', 'description' => 'Rich, moist chocolate cupcake with buttercream frosting.', 'price' => 35.00, 'cost_price' => 15.00, 'stock' => 40, 'unit' => 'piece', 'allergens' => ['wheat', 'eggs', 'dairy']],
            ['category' => $cakes, 'name' => 'Leche Flan Cake', 'description' => 'Two-layer dessert: moist chiffon cake topped with creamy leche flan.', 'price' => 350.00, 'cost_price' => 160.00, 'stock' => 8, 'unit' => 'whole', 'allergens' => ['wheat', 'eggs', 'dairy'], 'preorder' => true],
            ['category' => $cakes, 'name' => 'Pianono', 'description' => 'Thin rolled cake with butter and sugar — a classic Pinoy snack.', 'price' => 15.00, 'cost_price' => 6.00, 'stock' => 60, 'unit' => 'piece', 'allergens' => ['wheat', 'eggs', 'dairy']],
            ['category' => $cakes, 'name' => 'Toasted Siopao (Baked Bun)', 'description' => 'Baked steamed bun with savory pork asado filling, toasted golden.', 'price' => 30.00, 'cost_price' => 12.00, 'stock' => 40, 'unit' => 'piece', 'allergens' => ['wheat', 'soy']],
            ['category' => $cakes, 'name' => 'Egg Pie', 'description' => 'Creamy custard egg filling in a buttery pie crust — a bakery must-have.', 'price' => 120.00, 'cost_price' => 50.00, 'stock' => 15, 'unit' => 'whole', 'allergens' => ['wheat', 'eggs', 'dairy']],
            ['category' => $cakes, 'name' => 'Buko Pie', 'description' => 'Flaky double-crust pie filled with sweet young coconut strips.', 'price' => 150.00, 'cost_price' => 65.00, 'stock' => 12, 'unit' => 'whole', 'allergens' => ['wheat', 'coconut', 'dairy']],
            ['category' => $cakes, 'name' => 'Cheese Cupcake', 'description' => 'Light vanilla cupcake topped with sweet cheese frosting.', 'price' => 35.00, 'cost_price' => 14.00, 'stock' => 40, 'unit' => 'piece', 'allergens' => ['wheat', 'eggs', 'dairy']],

            // Specialty Items (8)
            ['category' => $specialty, 'name' => 'Pan de Bonete', 'description' => 'Cone-shaped heritage bread with a firm crust and soft center.', 'price' => 12.00, 'cost_price' => 5.00, 'stock' => 40, 'unit' => 'piece', 'allergens' => ['wheat']],
            ['category' => $specialty, 'name' => 'Panettone', 'description' => 'Italian-style festive bread with dried fruits and a tall, dome shape.', 'price' => 280.00, 'cost_price' => 130.00, 'stock' => 10, 'unit' => 'piece', 'allergens' => ['wheat', 'eggs', 'dairy', 'tree nuts'], 'preorder' => true],
            ['category' => $specialty, 'name' => 'Bibingka Bread', 'description' => 'Rice-cake inspired bread with coconut cream and salted egg — holiday special.', 'price' => 30.00, 'cost_price' => 14.00, 'stock' => 30, 'unit' => 'piece', 'allergens' => ['wheat', 'eggs', 'dairy', 'coconut'], 'preorder' => true],
            ['category' => $specialty, 'name' => 'Pork Floss Bun', 'description' => 'Soft bread roll coated with savory pork floss and mayo drizzle.', 'price' => 25.00, 'cost_price' => 10.00, 'stock' => 40, 'unit' => 'piece', 'allergens' => ['wheat', 'eggs', 'soy']],
            ['category' => $specialty, 'name' => 'Matcha Red Bean Bread', 'description' => 'Green tea flavored bread with sweet azuki red bean filling.', 'price' => 30.00, 'cost_price' => 14.00, 'stock' => 30, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy']],
            ['category' => $specialty, 'name' => 'Croissant', 'description' => 'Layered, buttery French pastry — flaky and golden.', 'price' => 45.00, 'cost_price' => 20.00, 'stock' => 25, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy', 'eggs']],
            ['category' => $specialty, 'name' => 'Pain au Chocolat', 'description' => 'Croissant-style pastry wrapped around rich dark chocolate batons.', 'price' => 55.00, 'cost_price' => 25.00, 'stock' => 20, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy', 'eggs', 'soy']],
            ['category' => $specialty, 'name' => 'Whole Wheat Pandesal', 'description' => 'Healthier pandesal made with whole wheat flour, lightly sweetened.', 'price' => 8.00, 'cost_price' => 3.50, 'stock' => 100, 'unit' => 'piece', 'allergens' => ['wheat']],
        ];

        foreach ($breads as $i => $bread) {
            Product::firstOrCreate(
                ['name' => $bread['name']],
                [
                'category_id'            => $bread['category']->id,
                'description'            => $bread['description'],
                'price'                  => $bread['price'],
                'cost_price'             => $bread['cost_price'],
                'stock_quantity'         => $bread['stock'],
                'min_stock_level'        => 10,
                'unit_type'              => $bread['unit'],
                'is_active'              => true,
                'available_for_preorder' => $bread['preorder'] ?? false,
                'preorder_hours_needed'  => 24,
                'image_url'              => '/images/' . $images[$i],
                'allergens'              => $bread['allergens'],
            ]);
        }

        // Additional products (safe to re-run — skips existing)
        $additionalBreads = [
            // Classic Breads
            ['category_id' => $classic->id, 'name' => 'Pan de Leche', 'description' => 'Soft milk bread with a tender, pillowy crumb and subtle sweetness.', 'price' => 10.00, 'cost_price' => 4.50, 'stock' => 90, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy']],
            ['category_id' => $classic->id, 'name' => 'Pan de Siosa', 'description' => 'Flattened Filipino bread with a crispy bottom and soft center, lightly dusted with flour.', 'price' => 8.00, 'cost_price' => 3.00, 'stock' => 70, 'unit' => 'piece', 'allergens' => ['wheat']],
            ['category_id' => $classic->id, 'name' => 'Pandesal Malunggay', 'description' => 'Nutritious pandesal infused with malunggay (moringa) leaves for added vitamins.', 'price' => 8.00, 'cost_price' => 3.50, 'stock' => 80, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy']],
            ['category_id' => $classic->id, 'name' => 'Pugon Bread', 'description' => 'Traditional brick-oven baked bread with a smoky aroma and rustic crust.', 'price' => 7.00, 'cost_price' => 2.80, 'stock' => 100, 'unit' => 'piece', 'allergens' => ['wheat']],

            // Pastries & Sweet Breads
            ['category_id' => $pastries->id, 'name' => 'Asado Siopao', 'description' => 'Fluffy steamed bun filled with sweet and savory pork asado.', 'price' => 30.00, 'cost_price' => 13.00, 'stock' => 45, 'unit' => 'piece', 'allergens' => ['wheat', 'soy']],
            ['category_id' => $pastries->id, 'name' => 'Bola-Bola Siopao', 'description' => 'Steamed bun stuffed with seasoned pork meatball, egg, and Chinese sausage.', 'price' => 35.00, 'cost_price' => 15.00, 'stock' => 40, 'unit' => 'piece', 'allergens' => ['wheat', 'eggs', 'soy']],
            ['category_id' => $pastries->id, 'name' => 'Pandan Loaf', 'description' => 'Fragrant pandan-flavored bread loaf with a natural green hue and soft texture.', 'price' => 80.00, 'cost_price' => 38.00, 'stock' => 20, 'unit' => 'loaf', 'allergens' => ['wheat', 'dairy', 'eggs']],
            ['category_id' => $pastries->id, 'name' => 'Ube Ensaymada', 'description' => 'Purple yam-flavored ensaymada topped with butter, sugar, and grated cheese.', 'price' => 30.00, 'cost_price' => 13.00, 'stock' => 40, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy', 'eggs']],
            ['category_id' => $pastries->id, 'name' => 'Chicken Empanada', 'description' => 'Golden flaky turnover filled with savory chicken, potatoes, and carrots.', 'price' => 28.00, 'cost_price' => 12.00, 'stock' => 45, 'unit' => 'piece', 'allergens' => ['wheat', 'eggs']],

            // Cakes & Rolls
            ['category_id' => $cakes->id, 'name' => 'Mocha Roll', 'description' => 'Coffee-flavored sponge cake rolled with mocha buttercream filling.', 'price' => 230.00, 'cost_price' => 105.00, 'stock' => 10, 'unit' => 'roll', 'allergens' => ['wheat', 'eggs', 'dairy']],
            ['category_id' => $cakes->id, 'name' => 'Mango Cake Slice', 'description' => 'Light chiffon layered with fresh Philippine mangoes and whipped cream.', 'price' => 85.00, 'cost_price' => 40.00, 'stock' => 15, 'unit' => 'piece', 'allergens' => ['wheat', 'eggs', 'dairy'], 'preorder' => true],
            ['category_id' => $cakes->id, 'name' => 'Red Velvet Cupcake', 'description' => 'Moist red velvet cake topped with cream cheese frosting.', 'price' => 40.00, 'cost_price' => 17.00, 'stock' => 35, 'unit' => 'piece', 'allergens' => ['wheat', 'eggs', 'dairy']],
            ['category_id' => $cakes->id, 'name' => 'Sans Rival', 'description' => 'Layered cashew meringue with French buttercream — a Filipino celebration staple.', 'price' => 380.00, 'cost_price' => 180.00, 'stock' => 6, 'unit' => 'whole', 'allergens' => ['eggs', 'dairy', 'tree nuts'], 'preorder' => true],
            ['category_id' => $cakes->id, 'name' => 'Cassava Cake', 'description' => 'Dense, chewy cake made from grated cassava, coconut milk, and condensed milk.', 'price' => 130.00, 'cost_price' => 55.00, 'stock' => 12, 'unit' => 'whole', 'allergens' => ['dairy', 'coconut', 'eggs']],

            // Specialty Items
            ['category_id' => $specialty->id, 'name' => 'Korean Cream Cheese Garlic Bread', 'description' => 'Viral garlic bread filled with sweet cream cheese and dipped in garlic butter.', 'price' => 55.00, 'cost_price' => 24.00, 'stock' => 30, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy', 'eggs']],
            ['category_id' => $specialty->id, 'name' => 'Crinkles', 'description' => 'Fudgy chocolate cookies with a crackled powdered sugar coating.', 'price' => 15.00, 'cost_price' => 6.00, 'stock' => 60, 'unit' => 'piece', 'allergens' => ['wheat', 'eggs', 'dairy']],
            ['category_id' => $specialty->id, 'name' => 'Cheese Bread Stick', 'description' => 'Crunchy breadstick loaded with melted cheese and herbs.', 'price' => 20.00, 'cost_price' => 8.00, 'stock' => 50, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy']],
            ['category_id' => $specialty->id, 'name' => 'Ube Pandesal', 'description' => 'Vibrant purple yam pandesal — soft, aromatic, and naturally sweet.', 'price' => 12.00, 'cost_price' => 5.50, 'stock' => 70, 'unit' => 'piece', 'allergens' => ['wheat', 'dairy']],
            ['category_id' => $specialty->id, 'name' => 'Banana Muffin', 'description' => 'Moist banana muffin with walnut bits and a golden crumb top.', 'price' => 25.00, 'cost_price' => 10.00, 'stock' => 40, 'unit' => 'piece', 'allergens' => ['wheat', 'eggs', 'dairy', 'tree nuts']],
            ['category_id' => $specialty->id, 'name' => 'Brioche Loaf', 'description' => 'Rich, buttery French bread with an incredibly soft and fluffy texture.', 'price' => 120.00, 'cost_price' => 55.00, 'stock' => 15, 'unit' => 'loaf', 'allergens' => ['wheat', 'dairy', 'eggs']],
        ];

        // Reuse existing images for new products
        $imgIndex = 0;
        foreach ($additionalBreads as $bread) {
            Product::firstOrCreate(
                ['name' => $bread['name']],
                [
                    'category_id'            => $bread['category_id'],
                    'description'            => $bread['description'],
                    'price'                  => $bread['price'],
                    'cost_price'             => $bread['cost_price'],
                    'stock_quantity'         => $bread['stock'],
                    'min_stock_level'        => 10,
                    'unit_type'              => $bread['unit'],
                    'is_active'              => true,
                    'available_for_preorder' => $bread['preorder'] ?? false,
                    'preorder_hours_needed'  => 24,
                    'image_url'              => '/images/' . $images[$imgIndex % count($images)],
                    'allergens'              => $bread['allergens'],
                ]
            );
            $imgIndex++;
        }
    }
}
