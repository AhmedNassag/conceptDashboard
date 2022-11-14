<?php

namespace Database\Seeders;

use App\Models\SubAccount;
use Illuminate\Database\Seeder;

class SubAccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // start add assets

        // 1.Fixed Assets
        SubAccount::create([
            "name" => 'الاصول الثابتة',
            'debit'=>1,
            'main_account_id'=>1,
        ]);

        // 1.2.Fixed Assets
        SubAccount::create([
            "name" => 'اثاث',
            'debit'=>1,
            'main_account_id'=>1,
            'sub_account_id'=>1
        ]);

        // 1.3.Fixed Assets
        SubAccount::create([
            "name" => 'اجهزة كمبيوتر',
            'debit'=>1,
            'main_account_id'=>1,
            'sub_account_id'=>1
        ]);

        // 1.4.Fixed Assets
        SubAccount::create([
            "name" => 'وسائل نقل ومواصلات',
            'debit'=>1,
            'main_account_id'=>1,
            'sub_account_id'=>1
        ]);

        // 5.Current Assets
        SubAccount::create([
            "name" => 'اصول متداولة',
            'debit'=>1,
            'main_account_id'=>1,
        ]);

        // 5.6.Banks
        SubAccount::create([
            "name" => 'البنوك',
            'debit'=>1,
            'main_account_id'=>1,
            'sub_account_id'=>5
        ]);
        // 5.6.7.Banks
        SubAccount::create([
            "name" => 'البنك العربى',
            'debit'=>1,
            'main_account_id'=>1,
            'sub_account_id'=>6
        ]);

        // 5.8.cash
        SubAccount::create([
            "name" => 'النقدية',
            'debit'=>1,
            'main_account_id'=>1,
            'sub_account_id'=>5
        ]);

        // 5.8.9.safe
        SubAccount::create([
            "name" => 'خزنة',
            'debit'=>1,
            'main_account_id'=>1,
            'sub_account_id'=>8
        ]);

        // 5.8.10.Vodafone cash
        SubAccount::create([
            "name" => 'فودافون كاش',
            'debit'=>1,
            'main_account_id'=>1,
            'sub_account_id'=>8
        ]);
        // 5.11.Checks under collection
        SubAccount::create([
            "name" => 'شيكات تحت التحصيل',
            'debit'=>1,
            'main_account_id'=>1,
            'sub_account_id'=>5
        ]);

        // 5.12.Client
        SubAccount::create([
            "name" => 'عملاء',
            'debit'=>1,
            'main_account_id'=>1,
            'sub_account_id'=>5
        ]);

        // 5.13.Debit Balances
        SubAccount::create([
            "name" => 'ارصدة مدينة',
            'debit'=>1,
            'main_account_id'=>1,
            'sub_account_id'=>5
        ]);

        // 5.14.accrued revenue
        SubAccount::create([
            "name" => 'ايرادات مستحقة',
            'debit'=>1,
            'main_account_id'=>1,
            'sub_account_id'=>5
        ]);

        // 5.15.Expenses paid in advance
        SubAccount::create([
            "name" => 'مصروفات مدفوعة مقدما',
            'debit'=>1,
            'main_account_id'=>1,
            'sub_account_id'=>5
        ]);

        // 16.Non-Long Term Loans
        SubAccount::create([
            "name" => 'قروض للغير طويلة الاجل',
            'debit'=>1,
            'main_account_id'=>1,
        ]);
        // end assets
////////////////////////////////////////////////////////////////////////////////////////////////
        // start add Liabilities

        // 17.capital
        SubAccount::create([
            "name" => 'رأس المال',
            'debit'=>0,
            'main_account_id'=>2,
        ]);

        // 18.current obligations
        SubAccount::create([
            "name" => 'التزامات متداولة',
            'debit'=>0,
            'main_account_id'=>2,
        ]);

        // 18.19.Suppliers
        SubAccount::create([
            "name" => 'موردين',
            'debit'=>0,
            'main_account_id'=>2,
            'sub_account_id'=>18
        ]);

        // 18.20.Other debit accounts
        SubAccount::create([
            "name" => 'حسابات دائنه اخرى',
            'debit'=>0,
            'main_account_id'=>2,
            'sub_account_id'=>18
        ]);

        // 18.21.Prepaid revenue
        SubAccount::create([
            "name" => 'ايرادات مدفوعه مقدما',
            'debit'=>0,
            'main_account_id'=>2,
            'sub_account_id'=>18
        ]);

        // 18.22.accrued expenses
        SubAccount::create([
            "name" => 'مصروفات مستحقة',
            'debit'=>0,
            'main_account_id'=>2,
            'sub_account_id'=>18
        ]);

        // 23.long-term commitments
        SubAccount::create([
            "name" => 'التزامات طويلة الاجل',
            'debit'=>0,
            'main_account_id'=>2,
        ]);

        // 24.profits stage
        SubAccount::create([
            "name" => 'ارباح مرحلة',
            'debit'=>0,
            'main_account_id'=>2,
        ]);

        // 25.profit of the year
        SubAccount::create([
            "name" => 'ارباح العام',
            'debit'=>0,
            'main_account_id'=>2,
        ]);

        // 26.companies current
        SubAccount::create([
            "name" => 'جارى الشركات',
            'debit'=>0,
            'main_account_id'=>2,
        ]);

        // 27.depreciation complex
        SubAccount::create([
            "name" => 'مجمع الاهلاك',
            'debit'=>0,
            'main_account_id'=>2,
        ]);

        // 28.allotments
        SubAccount::create([
            "name" => 'المخصصات',
            'debit'=>0,
            'main_account_id'=>2,
        ]);

        // 29.reserves
        SubAccount::create([
            "name" => 'الاحتياطيات',
            'debit'=>0,
            'main_account_id'=>2,
        ]);

        // end Liabilities
////////////////////////////////////////////////////////////////////////////////////////////////
        // start add Expenses

        // 30.rent
        SubAccount::create([
            "name" => 'ايجار',
            'debit'=>0,
            'main_account_id'=>3,
        ]);

        // 31.wages
        SubAccount::create([
            "name" => 'اجور',
            'debit'=>0,
            'main_account_id'=>3,
        ]);

        // 32.Electricity
        SubAccount::create([
            "name" => 'كهرباء',
            'debit'=>0,
            'main_account_id'=>3,
        ]);

        // 33.Site reservation
        SubAccount::create([
            "name" => 'حجز موقع',
            'debit'=>0,
            'main_account_id'=>3,
        ]);

        // 34.phone and net
        SubAccount::create([
            "name" => 'تليفون ونت',
            'debit'=>0,
            'main_account_id'=>3,
        ]);

        // 35.applied maintenance
        SubAccount::create([
            "name" => 'صيانة تطبيقية',
            'debit'=>0,
            'main_account_id'=>3,
        ]);

        // 36.transfers
        SubAccount::create([
            "name" => 'انتقالات',
            'debit'=>0,
            'main_account_id'=>3,
        ]);

        // 37.Hospitality and petty cash
        SubAccount::create([
            "name" => 'ضيافة ونثريات',
            'debit'=>0,
            'main_account_id'=>3,
        ]);

        // 38.depreciation
        SubAccount::create([
            "name" => 'الاهلاك',
            'debit'=>0,
            'main_account_id'=>3,
        ]);

        // 39.taxes
        SubAccount::create([
            "name" => 'الضرائب',
            'debit'=>0,
            'main_account_id'=>3,
        ]);

        // end Expenses
////////////////////////////////////////////////////////////////////////////////////////////////
        // start add Income

        // 40.Advertisement
        SubAccount::create([
            "name" => 'اعلانات',
            'debit'=>0,
            'main_account_id'=>4,
        ]);

        // end Income
    }
}
