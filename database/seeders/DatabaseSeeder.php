<?php

namespace Database\Seeders;

use App\Models\Income;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionSeeder::class);
        $this->call(CreateAdminSeeder::class);
        $this->call(CoponSeeder::class);
        $this->call(JobSeeder::class);
        $this->call(CreditCapacitySeeder::class);
        $this->call(ProvinceSeeder::class);
        $this->call(AreaSeeder::class);
        $this->call(ProductStatusSeeder::class);
        $this->call(SellingMethodSeeder::class);
        $this->call(MainAccountTableSeeder::class);
        $this->call(SubAccountTableSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(SuggestionSeeder::class);
        $this->call(TreasuryTableSeeder::class);
        $this->call(IncomeTableSeeder::class);
        $this->call(ExpenseTableSeeder::class);
        $this->call(StoreTableSeeder::class);
        $this->call(OrderStatusTableSeerer::class);
        $this->call(SellerCategorySeeder::class);
        $this->call(problemSellerCategorySeeder::class);
        $this->call(followSellerCategorySeeder::class);

        //        $this->call(SupplierTableSeeder::class);
    }
}
