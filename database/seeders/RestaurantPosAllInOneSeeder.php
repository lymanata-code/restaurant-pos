<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantPosAllInOneSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->call([
                RestaurantPosCoreSeeder::class,
                RestaurantPosUserSeeder::class,
                RestaurantPosMenuSeeder::class,
                RestaurantPosInventorySeeder::class,
                RestaurantPosFinanceSeeder::class,
                RestaurantPosOrderSeeder::class,
                RestaurantPosReportingSeeder::class,
            ]);
        });
    }
}
