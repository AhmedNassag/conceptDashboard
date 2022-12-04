<?php

namespace Database\Seeders;

use App\Models\Notify;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['name' => 'supplier read','role' => '','category' => 'supplier'],
            ['name' => 'supplier create','role' => '','category' => 'supplier'],
            ['name' => 'supplier edit','role' => '','category' => 'supplier'],
            ['name' => 'supplier delete','role' => '','category' => 'supplier'],

            // start product Management
            ['name' => 'category read','role' => 'productManagement','category' => 'category'],
            ['name' => 'category create','role' => 'productManagement','category' => 'category'],
            ['name' => 'category edit','role' => 'productManagement','category' => 'category'],
            ['name' => 'category delete','role' => 'productManagement','category' => 'category'],

            ['name' => 'subCategory read','role' => 'productManagement','category' => 'sub Category'],
            ['name' => 'subCategory create','role' => 'productManagement','category' => 'sub Category'],
            ['name' => 'subCategory edit','role' => 'productManagement','category' => 'sub Category'],
            ['name' => 'subCategory delete','role' => 'productManagement','category' => 'sub Category'],

            ['name' => 'company read','role' => 'productManagement','category' => 'Company'],
            ['name' => 'company create','role' => 'productManagement','category' => 'Company'],
            ['name' => 'company edit','role' => 'productManagement','category' => 'Company'],
            ['name' => 'company delete','role' => 'productManagement','category' => 'Company'],

            ['name' => 'measureUnit read','role' => 'productManagement','category' => 'MeasureUnit'],
            ['name' => 'measureUnit create','role' => 'productManagement','category' => 'MeasureUnit'],
            ['name' => 'measureUnit edit','role' => 'productManagement','category' => 'MeasureUnit'],
            ['name' => 'measureUnit delete','role' => 'productManagement','category' => 'MeasureUnit'],
            // end product Management

            // start product Management
            ['name' => 'sellingMethod read','role' => 'productManagement','category' => 'selling Method'],
            ['name' => 'sellingMethod create','role' => 'productManagement','category' => 'selling Method'],
            ['name' => 'sellingMethod edit','role' => 'productManagement','category' => 'selling Method'],
            ['name' => 'sellingMethod delete','role' => 'productManagement','category' => 'selling Method'],

            ['name' => 'product read','role' => 'productManagement','category' => 'Product'],
            ['name' => 'product create','role' => 'productManagement','category' => 'Product'],
            ['name' => 'product edit','role' => 'productManagement','category' => 'Product'],
            ['name' => 'product delete','role' => 'productManagement','category' => 'Product'],
            // end product Management

            // start Area Management
            ['name' => 'provinces read','role' => 'areaManagement','category' => 'Province'],
            ['name' => 'provinces create','role' => 'areaManagement','category' => 'Province'],
            ['name' => 'provinces edit','role' => 'areaManagement','category' => 'Province'],
            ['name' => 'provinces delete','role' => 'areaManagement','category' => 'Province'],

            ['name' => 'areas read','role' => 'areaManagement','category' => 'Areas'],
            ['name' => 'areas create','role' => 'areaManagement','category' => 'Areas'],
            ['name' => 'areas edit','role' => 'areaManagement','category' => 'Areas'],
            ['name' => 'areas delete','role' => 'areaManagement','category' => 'Areas'],
            // end Area Management


            ['name' => 'discount read','role' => '','category' => 'Discount'],
            ['name' => 'discount create','role' => '','category' => 'Discount'],
            ['name' => 'discount edit','role' => '','category' => 'Discount'],
            ['name' => 'discount delete','role' => '','category' => 'Discount'],

            ['name' => 'tax read','role' => '','category' => 'Tax'],
            ['name' => 'tax create','role' => '','category' => 'Tax'],
            ['name' => 'tax edit','role' => '','category' => 'Tax'],
            ['name' => 'tax delete','role' => '','category' => 'Tax'],

            // start Store Management
            ['name' => 'store read','role' => 'StoreManagement','category' => 'Store Management'],
            ['name' => 'store create','role' => 'StoreManagement','category' => 'Store Management'],
            ['name' => 'store edit','role' => 'StoreManagement','category' => 'Store Management'],
            ['name' => 'store delete','role' => 'StoreManagement','category' => 'Store Management'],

            ['name' => 'examinationRecords read','role' => 'StoreManagement','category' => 'Store Management'],
            ['name' => 'examinationRecords create','role' => 'StoreManagement','category' => 'Store Management'],
            ['name' => 'examinationRecords edit','role' => 'StoreManagement','category' => 'Store Management'],

            ['name' => 'ProductsPricing read','role' => 'StoreManagement','category' => 'Store Management'],
            ['name' => 'ProductsPricing edit','role' => 'StoreManagement','category' => 'Store Management'],
            // end Store Management

            // start role-employee
            ['name' => 'PurchaseInvoice read','role' => 'buy','category' => 'Purchase Invoice'],
            ['name' => 'PurchaseInvoice create','role' => 'buy','category' => 'Purchase Invoice'],
            ['name' => 'PurchaseInvoice edit','role' => 'buy','category' => 'Purchase Invoice'],
            ['name' => 'PurchaseInvoice delete','role' => 'buy','category' => 'Purchase Invoice'],

            ['name' => 'PurchaseReturn read','role' => 'buy','category' => 'Purchase Return'],
            ['name' => 'PurchaseReturn create','role' => 'buy','category' => 'Purchase Return'],
            ['name' => 'PurchaseReturn delete','role' => 'buy','category' => 'Purchase Return'],

            ['name' => 'EarnedDiscount read','role' => 'buy','category' => 'Earned Discount'],
            ['name' => 'EarnedDiscount create','role' => 'buy','category' => 'Earned Discount'],
            ['name' => 'EarnedDiscount edit','role' => 'buy','category' => 'Earned Discount'],
            ['name' => 'EarnedDiscount delete','role' => 'buy','category' => 'Earned Discount'],
            // end role-employee

            // start role-employee
            ['name' => 'department read','role' => 'role-employee','category' => 'Department'],
            ['name' => 'department create','role' => 'role-employee','category' => 'Department'],
            ['name' => 'department edit','role' => 'role-employee','category' => 'Department'],
            ['name' => 'department delete','role' => 'role-employee','category' => 'Department'],

            ['name' => 'job read','role' => 'role-employee','category' => 'jobs'],
            ['name' => 'job create','role' => 'role-employee','category' => 'jobs'],
            ['name' => 'job edit','role' => 'role-employee','category' => 'jobs'],
            ['name' => 'job delete','role' => 'role-employee','category' => 'jobs'],

            ['name' => 'role read','role' => 'role-employee','category' => 'role'],
            ['name' => 'role create','role' => 'role-employee','category' => 'role'],
            ['name' => 'role edit','role' => 'role-employee','category' => 'role'],
            ['name' => 'role delete','role' => 'role-employee','category' => 'role'],

            ['name' => 'employee read','role' => 'role-employee','category' => 'employee'],
            ['name' => 'employee create','role' => 'role-employee','category' => 'employee'],
            ['name' => 'employee edit','role' => 'role-employee','category' => 'employee'],
            ['name' => 'employee delete','role' => 'role-employee','category' => 'employee'],
            ['name' => 'employeeChangePassword edit','role' => 'role-employee','category' => 'employee'],

            ['name' => 'representative read','role' => 'role-employee','category' => 'Representative'],
            ['name' => 'representative create','role' => 'role-employee','category' => 'Representative'],
            ['name' => 'representative edit','role' => 'role-employee','category' => 'Representative'],
            ['name' => 'representativeChangePassword edit','role' => 'role-employee','category' => 'Representative'],
            // end role-employee

            ['name' => 'CreditCapacity read','role' => '','category' => 'Credit Capacity'],
            ['name' => 'CreditCapacity edit','role' => '','category' => 'Credit Capacity'],

            // start financial Accounts
            ['name' => 'AccountsTree read','role' => 'financial Accounts','category' => 'financial Accounts'],
            ['name' => 'AccountsTree create','role' => 'financial Accounts','category' => 'financial Accounts'],
            ['name' => 'AccountsTree edit','role' => 'financial Accounts','category' => 'financial Accounts'],
            ['name' => 'DailyRestriction read','role' => 'financial Accounts','category' => 'financial Accounts'],
            ['name' => 'DailyRestriction create','role' => 'financial Accounts','category' => 'financial Accounts'],
            ['name' => 'DailyRestriction edit','role' => 'financial Accounts','category' => 'financial Accounts'],
            ['name' => 'TrialBalance read','role' => 'financial Accounts','category' => 'financial Accounts'],
            ['name' => 'FinancialCenter read','role' => 'financial Accounts','category' => 'financial Accounts'],
            ['name' => 'IncomeList read','role' => 'financial Accounts','category' => 'financial Accounts'],
            ['name' => 'AccountStatement read','role' => 'financial Accounts','category' => 'financial Accounts'],
            // end financial Accounts

            // start ads
            ['name' => 'popupAds read','role' => 'ads','category' => 'Ads'],
            ['name' => 'popupAds create','role' => 'ads','category' => 'Ads'],
            ['name' => 'popupAds edit','role' => 'ads','category' => 'Ads'],
            ['name' => 'popupAds delete','role' => 'ads','category' => 'Ads'],

            ['name' => 'firstSliderAds read','role' => 'ads','category' => 'Ads'],
            ['name' => 'firstSliderAds create','role' => 'ads','category' => 'Ads'],
            ['name' => 'firstSliderAds edit','role' => 'ads','category' => 'Ads'],
            ['name' => 'firstSliderAds delete','role' => 'ads','category' => 'Ads'],

            ['name' => 'secondSliderAds read','role' => 'ads','category' => 'Ads'],
            ['name' => 'secondSliderAds create','role' => 'ads','category' => 'Ads'],
            ['name' => 'secondSliderAds edit','role' => 'ads','category' => 'Ads'],
            ['name' => 'secondSliderAds delete','role' => 'ads','category' => 'Ads'],
            // end ads

            // start sitting
            ['name' => 'setting read','role' => '','category' => 'Setting'],
            ['name' => 'setting edit','role' => '','category' => 'Setting'],
            // end setting

            // start order
            ['name' => 'order read','role' => 'order','category' => 'order'],
            ['name' => 'order create','role' => 'order','category' => 'order'],
            ['name' => 'order edit','role' => 'order','category' => 'order'],
            ['name' => 'order delete','role' => 'order','category' => 'order'],
            ['name' => 'avg price product','role' => 'order','category' => 'order'],

            ['name' => 'orderOnline read','role' => 'order','category' => 'order'],
            ['name' => 'orderOnline edit','role' => 'order','category' => 'order'],
            ['name' => 'orderOnline delete','role' => 'order','category' => 'order'],

            ['name' => 'orderReturned read','role' => 'order','category' => 'order'],
            ['name' => 'orderDelivered read','role' => 'order','category' => 'order'],
            // end order

            // start platform Accounts
            ['name' => 'treasury read','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'treasury create','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'treasury edit','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'treasury delete','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'income read','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'income create','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'income edit','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'income delete','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'expense read','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'expense create','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'expense edit','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'expense delete','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'income&expense read','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'income&expense create','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'income&expense edit','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'income&expense delete','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'treasuriesIncome read','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'treasuriesExpense read','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'transferringTreasury read','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'transferringTreasury create','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'transferringTreasury edit','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'transferringTreasury delete','role' => 'platform Accounts','category' => 'platform Accounts'],

            ['name' => 'purchaseExpenses read','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'supplierAccountStatement read','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'clientAccountStatement read','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'financialCondition read','role' => 'platform Accounts','category' => 'platform Accounts'],

            ['name' => 'supplierExpenses read','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'supplierExpenses create','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'supplierExpenses edit','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'supplierExpenses delete','role' => 'platform Accounts','category' => 'platform Accounts'],

            ['name' => 'clientExpenses read','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'clientExpenses create','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'clientExpenses edit','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'clientExpenses delete','role' => 'platform Accounts','category' => 'platform Accounts'],

            ['name' => 'SupplierIncomes read','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'SupplierIncomes create','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'SupplierIncomes edit','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'SupplierIncomes delete','role' => 'platform Accounts','category' => 'platform Accounts'],

            ['name' => 'clientIncomes read','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'clientIncomes create','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'clientIncomes edit','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'clientIncomes delete','role' => 'platform Accounts','category' => 'platform Accounts'],

            ['name' => 'orderIncomes read','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'purchaseReturnIncomes read','role' => 'platform Accounts','category' => 'platform Accounts'],

            ['name' => 'CapitalOwnerAccount read','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'CapitalOwnerAccount create','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'CapitalOwnerAccount edit','role' => 'platform Accounts','category' => 'platform Accounts'],
            ['name' => 'CapitalOwnerAccount delete','role' => 'platform Accounts','category' => 'platform Accounts'],

            // end platform Accounts

            // start Suggestion
            ['name' => 'Suggestions read','role' => 'Suggestions','category' => 'Suggestions'],
            ['name' => 'Suggestions create','role' => 'Suggestions','category' => 'Suggestions'],
            ['name' => 'Suggestions edit','role' => 'Suggestions','category' => 'Suggestions'],
            ['name' => 'Suggestions delete','role' => 'Suggestions','category' => 'Suggestions'],
            ['name' => 'SuggestionClients read','role' => 'Suggestions','category' => 'Suggestions'],
            // end Suggestion

            // start Suggestion
            ['name' => 'addNotificationApp create','role' => 'Suggestions','category' => 'Suggestions'],
            // end Suggestion

        ];


        $notifies = [
            'Add Suggestion',
            'Add Client',
            'Add OnlineOrder',
        ];

        foreach ($notifies as $notify) {
            Notify::create(['name' => $notify]);
        }

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'role' => $permission['role'],
                'category' => $permission['category']
            ]);
        }
    }
}
