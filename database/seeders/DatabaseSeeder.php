<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\ModifierGroup;
use App\Models\ModifierOption;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedUsers();
        $this->seedTables();
        $this->seedMenusAndModifiers();
    }

    private function seedUsers(): void
    {
        $staff = [
            [
                'name' => 'Admin RestoHub',
                'email' => 'admin@restohub.id',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'kasir@restohub.id',
                'password' => Hash::make('password'),
                'role' => 'cashier',
            ],
            [
                'name' => 'Chef Raka',
                'email' => 'dapur@restohub.id',
                'password' => Hash::make('password'),
                'role' => 'kitchen',
            ],
        ];

        foreach ($staff as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                $account
            );
        }
    }

    private function seedTables(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $num = str_pad((string) $i, 2, '0', STR_PAD_LEFT);
            $floor = $i <= 8 ? 'Lt 1' : 'Lt 2';
            $capacity = $i <= 8 ? 4 : 6;

            Table::updateOrCreate(
                ['table_number' => $num],
                [
                    'floor' => $floor,
                    'capacity' => $capacity,
                    'status' => 'available',
                    'qr_token' => 'qr-' . $num . '-' . substr(md5('restohub-table-' . $num), 0, 12),
                ]
            );
        }
    }

    private function seedMenusAndModifiers(): void
    {
        // 1. Categories
        $makanan = Category::firstOrCreate(['slug' => 'makanan'], ['name' => 'Makanan']);
        $minuman = Category::firstOrCreate(['slug' => 'minuman'], ['name' => 'Minuman']);
        $cemilan = Category::firstOrCreate(['slug' => 'cemilan'], ['name' => 'Cemilan']);

        // 2. Modifier Groups & Options
        $spiceGroup = ModifierGroup::firstOrCreate(
            ['name' => 'Pilih Tingkat Pedas'],
            ['is_required' => true, 'max_selection' => 1]
        );
        foreach (['Lv 1 - Ringan', 'Lv 2 - Sedang', 'Lv 3 - Pedas', 'Lv 4 - Ekstra'] as $level) {
            ModifierOption::firstOrCreate(
                ['modifier_group_id' => $spiceGroup->id, 'name' => $level],
                ['extra_price' => 0, 'is_available' => true]
            );
        }

        $toppingGroup = ModifierGroup::firstOrCreate(
            ['name' => 'Pilihan Tambahan'],
            ['is_required' => false, 'max_selection' => 3]
        );
        $toppings = [
            ['name' => '+ Telur Ceplok', 'extra_price' => 4000],
            ['name' => '+ Pangsit Ekstra', 'extra_price' => 3000],
            ['name' => '+ Keju Parut', 'extra_price' => 4000],
        ];
        foreach ($toppings as $topping) {
            ModifierOption::firstOrCreate(
                ['modifier_group_id' => $toppingGroup->id, 'name' => $topping['name']],
                ['extra_price' => $topping['extra_price'], 'is_available' => true]
            );
        }

        $sugarGroup = ModifierGroup::firstOrCreate(
            ['name' => 'Tingkat Gula'],
            ['is_required' => true, 'max_selection' => 1]
        );
        foreach (['Gula Normal', 'Gula 50%', 'Tanpa Gula'] as $sugar) {
            ModifierOption::firstOrCreate(
                ['modifier_group_id' => $sugarGroup->id, 'name' => $sugar],
                ['extra_price' => 0, 'is_available' => true]
            );
        }

        $iceGroup = ModifierGroup::firstOrCreate(
            ['name' => 'Tingkat Es'],
            ['is_required' => true, 'max_selection' => 1]
        );
        foreach (['Es Sedang', 'Less Ice', 'Tanpa Es'] as $ice) {
            ModifierOption::firstOrCreate(
                ['modifier_group_id' => $iceGroup->id, 'name' => $ice],
                ['extra_price' => 0, 'is_available' => true]
            );
        }

        // 3. Menus
        $foodMenus = [
            [
                'name' => 'Mie Spesial Level',
                'description' => 'Mie kenyal pedas dengan taburan ayam cincang dan pangsit krispi',
                'base_price' => 15000,
                'badge' => 'Pedas',
                'is_available' => true,
            ],
            [
                'name' => 'Nasi Goreng Kampung',
                'description' => 'Nasi goreng bumbu gurih dengan telur ceplok dan acar',
                'base_price' => 18000,
                'badge' => 'Favorit',
                'is_available' => true,
            ],
            [
                'name' => 'Mie Ayam Jamur',
                'description' => 'Mie kenyal porsi reguler dengan tumisan ayam jamur gurih',
                'base_price' => 16000,
                'badge' => null,
                'is_available' => true,
            ],
            [
                'name' => 'Kwetiau Goreng Seafood',
                'description' => 'Kwetiau goreng wangi wok dengan udang, cumi, dan sayuran segar',
                'base_price' => 22000,
                'badge' => 'Best Seller',
                'is_available' => true,
            ],
        ];

        foreach ($foodMenus as $item) {
            $menu = Menu::updateOrCreate(
                ['name' => $item['name']],
                array_merge($item, ['category_id' => $makanan->id])
            );
            $menu->modifierGroups()->sync([$spiceGroup->id, $toppingGroup->id]);
        }

        $drinkMenus = [
            [
                'name' => 'Es Teh Manis Jumbo',
                'description' => 'Teh melati segar manis dingin ukuran besar',
                'base_price' => 6000,
                'badge' => null,
                'is_available' => true,
            ],
            [
                'name' => 'Es Jeruk Peras',
                'description' => 'Jeruk peras asli segar dengan es batu',
                'base_price' => 8000,
                'badge' => 'Segar',
                'is_available' => true,
            ],
            [
                'name' => 'Es Lemon Tea',
                'description' => 'Perpaduan teh hitam pilihan dengan perasan lemon segar',
                'base_price' => 9000,
                'badge' => null,
                'is_available' => true,
            ],
        ];

        foreach ($drinkMenus as $item) {
            $menu = Menu::updateOrCreate(
                ['name' => $item['name']],
                array_merge($item, ['category_id' => $minuman->id])
            );
            $menu->modifierGroups()->sync([$sugarGroup->id, $iceGroup->id]);
        }

        $snackMenus = [
            [
                'name' => 'Pangsit Goreng Renyah',
                'description' => 'Pangsit isi ayam goreng renyah isi 4 pcs',
                'base_price' => 12000,
                'badge' => null,
                'is_available' => true,
            ],
            [
                'name' => 'Siomay Ayam Kukus',
                'description' => 'Siomay ayam lembut dengan saus sambal kacang',
                'base_price' => 13000,
                'badge' => null,
                'is_available' => true,
            ],
            [
                'name' => 'Dimsum Udang Spesial',
                'description' => 'Dimsum udang kukus hangat (contoh menu stok habis)',
                'base_price' => 15000,
                'badge' => 'Habis',
                'is_available' => false,
            ],
        ];

        foreach ($snackMenus as $item) {
            Menu::updateOrCreate(
                ['name' => $item['name']],
                array_merge($item, ['category_id' => $cemilan->id])
            );
        }
    }
}
