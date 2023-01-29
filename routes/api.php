<?php

use App\Http\Controllers\Api\MobileApp\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group([ 'prefix' => 'v1','middleware' => ['secretAPI','changeLang']],function () {

    //start Dashboard auth
    Route::group([ 'prefix' => 'auth','namespace' => 'Dashboard'],function () {

        //start login
        Route::post('login','AuthDashboardController@login');

        //check token
        Route::get('checkToken',  'AuthDashboardController@authorizeUser');

    });
    //api token_access

    /****************************** start web ******************************/
    Route::group([ 'prefix' => 'web','namespace' => 'Web'],function () {

        //check token
        Route::get('checkToken',  'AuthController@authorizeUser');

        //start Register[company,merchant,client]
        Route::post('company','RegisterController@companyRegister');
        Route::post('merchant','RegisterController@merchantRegister');
        Route::post('client','RegisterController@clientRegister');
        Route::get('province','RegisterController@province');
        Route::get('area/{area}','RegisterController@area');

        //start reset[company,merchant,client]
        Route::post('forgot-password', 'AuthController@forgotPassword');
        Route::post('confirmOtp', 'AuthController@confirmOtp');
        Route::post('reset-password', 'AuthController@reset');

        //start Login[company,merchant,client]
        Route::post('login','AuthController@login');

        Route::middleware(['auth:api'])->group(function () {
            //start logout
            Route::post('logout','AuthController@logout');
        });

    });
    /****************************** end web ******************************/


    /****************************** start Dashboard ******************************/
    Route::middleware(['auth:api'])->group(function () {
        Route::group(['prefix' => 'dashboard','namespace' => 'Dashboard'],function () {

            //start Notification
            Route::get('getAllNot','NotificationController@getAllNot');
            Route::get('getNotNotRead','NotificationController@getNotNotRead');
            Route::post('clearItem/{id}','NotificationController@clearItem');
            Route::post('getNotNotRead','NotificationController@clearAll');

            //start Dashboard
            Route::get('getDashboard','DashboardController@getDashboard');
            Route::get('getChart','DashboardController@getChart');

            //start User
            Route::apiResource('user','UserController');

            //start role
            Route::resource('role','RoleController');

            //department
            Route::resource('department','DepartmentController');
            Route::get('activeDepartment','DepartmentController@activeDepartment');
            Route::get('activationDepartment/{id}','DepartmentController@activationDepartment');

            //job
            Route::resource('job','JobController');
            Route::get('activeJob','JobController@activeJob');
            Route::get('activationJob/{id}','JobController@activationJob');

            //employee
            Route::resource('employee','EmployeeController');
            Route::get('activationEmployee/{id}','EmployeeController@activationEmployee');
            Route::post('employee/changePassword/{id}','EmployeeController@changePassword');
            Route::get('role','EmployeeController@role');
            Route::get('salesEmployee','EmployeeController@salesEmployee');

            //supplier
            Route::resource('supplier','SupplierController')->except(['show']);
            Route::get('activationSupplier/{id}','SupplierController@activationSupplier');
            Route::get('activeSupplier','SupplierController@activeSupplier');

            //category
            Route::resource('category','CategoryController');
            Route::get('activationCategory/{id}','CategoryController@activationCategory');
            Route::get('activeCategory','CategoryController@activeCategory');

            //sub category
            Route::resource('subCategory','SubCategoryController')->except(['show']);

            //company
            Route::resource('company','CompanyController')->except(['show']);
            Route::get('activationCompany/{id}','CompanyController@activationCompany');

            //measure
            Route::resource('measure','MeasurementUnitController')->except(['show']);
            Route::get('activationMeausre/{id}','MeasurementUnitController@activationMeausre');

            //discount
            Route::resource('discount','DiscountController')->except(['show']);
            Route::get('activationDiscount/{id}','DiscountController@activationDiscount');

            //tax
            Route::resource('tax','TaxController')->except(['show']);
            Route::get('activationTax/{id}','TaxController@activationTax');
            Route::get('activationTaxApp/{id}','TaxController@activationTaxApp');

            //sellingMethod
            Route::resource('sellingMethod','SellingMethodController')->except(['show']);
            Route::get('activeSellingMethod','SellingMethodController@activeSellingMethod');
            Route::get('activationSellingMethod/{id}','SellingMethodController@activationSellingMethod');

            //product
            Route::resource('product','ProductController')->except(['show']);
            Route::get('activationProduct/{id}','ProductController@activationProduct');
            Route::post('deleteOne/{id}','ProductController@deleteOne');
            Route::get('activeProduct','ProductController@activeProduct');
            Route::get('mobileProduct','ProductController@mobileProduct');
            Route::get('purchaseInvoiceProduct','ProductController@purchaseInvoiceProduct');

            //start logout
            Route::post('logout','AuthDashboardController@logout');

            //province
            Route::resource('province','ProvinceController');

            //area
            Route::resource('area','AreaController')->except(['show']);

            //Representative
            Route::resource('representative','RepresentativeController')->except(['show']);
            Route::get('activeShift','RepresentativeController@activeShift');
            Route::get('activeRepresentative','RepresentativeController@activeRepresentative');
            Route::post('representative/changePassword/{id}','RepresentativeController@changePassword');

            //shift
            Route::resource('shift','ShiftController');
            Route::get('activeShift','ShiftController@activeShift');
            Route::get('activationShift/{id}','ShiftController@activationShift');

            //store
            Route::resource('store','StoreController');
            Route::get('activationStore/{id}','StoreController@activationStore');
            Route::get('mainStore/{id}','StoreController@MainStore');
            Route::get('activeStore','StoreController@activeStore');

            //Offer & Discount
            Route::resource('offerDiscount','OfferDiscountController');
            Route::get('activationOfferDiscount/{id}','OfferDiscountController@activationOfferDiscount');

            //Credit Capacity
            Route::resource('creditCapacity','CreditCapacityController');

            //Purchase Invoice
            Route::resource('purchaseInvoice','PurchaseController');

            //employee Shift
            Route::resource('employeeShift','EmployeeShiftController');

            //examination Record
            Route::resource('examinationRecord','ExaminationRecordController');

            //Purchase Return
            Route::resource('purchaseReturn','PurchaseReturnController');

            //Purchase Return Account
            Route::post('purchaseReturnAccount','PurchaseReturnAccountController@purchaseReturnAccount');
            Route::get('purchaseReturnAccount','PurchaseReturnAccountController@index');

            //Purchase Expenses
            Route::get('purchaseExpenses','PurchaseExpensesController@index');

            //Products Pricing
            Route::resource('productsPricing','ProductsPricingController');

            //earned Discount
            Route::resource('earnedDiscount','EarnedDiscountController');

            //client
            Route::resource('client','ClientController');
            Route::get('activationClient/{id}','ClientController@activationClient');
            Route::post('sendClientNotification','ClientController@sendClientNotification');

            //Main Account
            Route::resource('mainAccount','MainAccountController');

            //Sub Account
            Route::get('subAccount/{main}/{id}','SubAccountController@index');
            Route::get('getMainSub/{id}','SubAccountController@getMainSub');
            Route::post('storeSubAccount/{main}/{id}','SubAccountController@store');
            Route::put('updateSubAccount/{id}','SubAccountController@update');
            Route::get('editSubAccount/{id}','SubAccountController@edit');

            //Daily Restriction
            Route::resource('dailyRestriction','DailyRestrictionController');

            //trial Balance
            Route::resource('trialBalance','TrialBalanceController');

            //Financial Center
            Route::resource('financialCenter','FinancialCenterController');

            //Account Statement
            Route::resource('accountStatement','AccountStatementController');
            Route::get('accountDaily','AccountStatementController@accountDaily');

            //Income List
            Route::resource('incomeList','IncomeListController');

            //Order Direct
            Route::resource('orderDirect','OrderDirectController');
            Route::get('orderDirectStore','OrderDirectController@storeChoose');
            Route::get('orderDirectReport','OrderDirectController@orderDirectReport');
            Route::get('orderDirectStatus/{id}','OrderDirectController@activeOrder');

            //order Online
            Route::resource('orderOnline','OrderOnlineController');

            //order Returned
            Route::resource('orderReturned','OrderReturnedController');

            //order Delivered
            Route::resource('orderDelivered','OrderDeliveredController');

            //order Income
            Route::resource('orderIncome','OrderIncomeController');

            //order Status
            Route::resource('orderStatus','OrderStatusController');

            //Capital Owner Account
            Route::resource('capitalOwnerAccount','CapitalOwnerAccountController');

            //Popup Ads
            Route::resource('popupAds','PopupAdsController');

            //slider Ads
            Route::resource('ads','AdsController');

            //setting Ads
            Route::resource('setting','SettingController');

            //UpcomingPayment
            Route::resource('upcomingPayment','UpcomingPaymentController');

            //treasury management
            Route::resource('treasury','TreasuryController');
            Route::get('mainTreasury','TreasuryController@mainTreasury');
            Route::get('activeTreasury','TreasuryController@activeTreasury');
            Route::get('activationTreasury/{id}','TreasuryController@activationTreasury');
            Route::get('treasuriesIncome/{id}','TreasuryController@treasuriesIncome');
            Route::get('treasuriesExpense/{id}','TreasuryController@treasuriesExpense');
            Route::get('treasuriesClientIncome/{id}','TreasuryController@clientIncomes');
            Route::get('treasuriesSupplierIncome/{id}','TreasuryController@supplierIncomes');
            Route::get('treasuriesOwnerIncomes/{id}','TreasuryController@ownerIncomes');
            Route::get('treasuriesClientExpense/{id}','TreasuryController@clientExpense');
            Route::get('treasuriesSupplierExpense/{id}','TreasuryController@supplierExpense');
            Route::get('treasuriesOwnerExpense/{id}','TreasuryController@ownerExpense');

            //income
            Route::resource('income','IncomeController');
            Route::get('mainIncome','IncomeController@mainIncome');
            Route::get('activeIncome','IncomeController@activeIncome');
            Route::get('activationIncome/{id}','IncomeController@activationIncome');

            //expense
            Route::resource('expense','ExpenseController');
            Route::get('mainExpense','ExpenseController@mainExpense');
            Route::get('activeExpense','ExpenseController@activeExpense');
            Route::get('activationExpense/{id}','ExpenseController@activationExpense');

            //income and expense
            Route::resource('incomeAndExpense','IncomeAndExpenseController');
            Route::get('calcIncome','IncomeAndExpenseController@calcIncome');
            Route::get('editExpense/{id}/edit','IncomeAndExpenseController@editExpense');
            Route::get('calcExpense','IncomeAndExpenseController@calcExpense');

            //Transferring Treasury management
            Route::resource('transferringTreasury','TransferringTreasuryController');

            //start Report
            Route::get('productReport','ProductCategoryReportController@product');
            Route::get('categoryReport','ProductCategoryReportController@category');
            Route::get('clientOldNew','ClientReport@clientOldNew');
            Route::get('clientQty','ClientReport@clientQty');
            Route::get('clientPrice','ClientReport@clientPrice');
            Route::get('storeReport','AreaReportController@storeReport');
            Route::get('suggestionReport','AreaReportController@suggestionReport');

            //suggestion
            Route::resource('suggestion','SuggestionController')->except('show');
            Route::get('suggestionClient','SuggestionClientController@index');
            Route::get('suggestionClient/{id}','SuggestionClientController@show');

            //complaint
            Route::resource('complaint','ComplaintController')->except('show');
            Route::get('complaintClient','ComplaintClientController@index');
            Route::get('complaintClient/{id}','ComplaintClientController@show');

            //supplier Expense
            Route::resource('supplierExpense','SupplierExpensesController')->except('show');

            //Supplier Incomes
            Route::resource('SupplierIncomes','SupplierIncomesController')->except('show');

            //client Expense
            Route::resource('clientExpense','ClientExpensesController')->except('show');

            //client Incomes
            Route::resource('clientIncomes','ClientIncomesController')->except('show');

            //Supplier Account Statement
            Route::resource('SupplierAccountStatement','SupplierAccountStatementController');

            //client Account Statement
            Route::resource('clientAccountStatement','ClientAccountStatementController');

            //Notification App
            Route::resource('notificationApp','NotificationAppController');

            //order Filter Wax
            Route::resource('filterWax','FilterWaxController');

            //order Spare Part
            Route::resource('sparePart','SparePartController');

            //order Spare Accessor
            Route::resource('spareAccessor','SpareAccessorController');

            //userCompany && merchant
            Route::resource('userCompany','UserCompanyController');
            Route::get('activationCompany/{id}','UserCompanyController@activationCompany');
            Route::resource('merchant','MerchantController');
            Route::get('activationMerchant/{id}','MerchantController@activationMerchant');
            Route::resource('knowledgeWay','KnowledgeWayController');

            //start leadSourse
            Route::resource('leadSourse','LeadSourceController');

            //start leadFollowUps
            Route::resource('leadFollowUps','LeadFollowUpController');

            //start lead
            Route::resource('lead','LeadController');
            Route::get('leadClient','LeadController@leadClient');
            Route::post('storeClient','LeadController@storeClient');
            Route::post('addAction', 'LeadController@addAction');
            Route::post('changeLeadClient/{id}','LeadController@changeLeadToClient');
            Route::get('leadClientGet','LeadController@leadClientGet');

            //crm
            Route::resource('targetPlan', 'TargetPlanController');
            Route::resource('target', 'TargetController');
            Route::resource('sellerCategory', 'SellerCategoryController');
            Route::resource('leads', 'LeadController');
            Route::get('changeEmployeeLead/{id}', 'LeadController@changeEmployeeLead');
            Route::put('updateEmployeeLead/{id}', 'LeadController@updateEmployeeLead');
            Route::resource('salesLead', 'SalesLeadController');
            Route::get('getTenLead/{id}', 'SalesLeadController@getTenLead');
            Route::resource('leadComment', 'LeadCommentController');
            Route::resource('targetAchieved', 'TargetAchievedController');

            //problem
            Route::resource('problemtargetPlan', 'problemTargetPlanController');
            Route::resource('problemtarget', 'problemTargetController');
            Route::resource('problemsellerCategory', 'problemSellerCategoryController');
            Route::resource('problemleads', 'problemLeadController');
            Route::get('problemchangeEmployeeLead/{id}', 'problemLeadController@changeEmployeeLead');
            Route::put('problemupdateEmployeeLead/{id}', 'problemLeadController@updateEmployeeLead');
            Route::resource('problemsalesLead', 'problemSalesLeadController');
            Route::get('problemgetTenLead/{id}', 'problemSalesLeadController@getTenLead');
            Route::resource('problemleadComment', 'problemLeadCommentController');
            Route::resource('problemtargetAchieved', 'problemTargetAchievedController');

            //follow
            Route::resource('followtargetPlan', 'followTargetPlanController');
            Route::resource('followtarget', 'followTargetController');
            Route::resource('followsellerCategory', 'followSellerCategoryController');
            Route::resource('followleads', 'followLeadController');
            Route::get('followchangeEmployeeLead/{id}', 'followLeadController@changeEmployeeLead');
            Route::put('followupdateEmployeeLead/{id}', 'followLeadController@updateEmployeeLead');
            Route::resource('followsalesLead', 'followSalesLeadController');
            Route::get('followgetTenLead/{id}', 'followSalesLeadController@getTenLead');
            Route::resource('followleadComment', 'followLeadCommentController');
            Route::resource('followtargetAchieved', 'followTargetAchievedController');

            //PeriodicMaintenance
            Route::resource('periodicMaintenance','PeriodicMaintenanceController');
            Route::get('activationPeriodicMaintenance/{id}','PeriodicMaintenanceController@activationPeriodic');
            Route::get('nearPeriodicMaintenance','PeriodicMaintenanceController@nearPeriodic');
            Route::post('confirmPeriodic/{id}','PeriodicMaintenanceController@confirmPeriodic');
            Route::get('periodicchangeEmployeeLead/{id}','PeriodicMaintenanceController@changeEmployeeLead');
            Route::put('periodicupdateEmployeeLead/{id}','PeriodicMaintenanceController@updateEmployeeLead');

            //companyProfile
            Route::resource('companyProfile', 'CompanyProfileController');

            //termsPrivacy
            Route::resource('termsPrivacy', 'TermsPrivacyController');

            //competition
            Route::resource('competition', 'CompetitionController');
            Route::get('activationCompetition/{id}', 'CompetitionController@activationCompetition');
            Route::get('activeCompetition', 'CompetitionController@activeCompetition');

            //share
            Route::resource('share', 'ShareController');

            //pointPrice
            Route::resource('pointPrice', 'PointPriceController')->except(['show']);
            Route::get('activationPointPrice/{id}', 'PointPriceController@activationPointPrice');

            //pointPrice
            Route::resource('pointWelcome', 'PointWelcomeController')->except(['show']);
            Route::get('activationPointWelcome/{id}', 'PointWelcomeController@activationPointWelcome');

            //wallet
            Route::resource('wallet', 'WalletController');
        });
    });
    /****************************** end dashboard ******************************/



    #######################################################################################################################
    ######################################   Start Mobile Api   ###########################################################
    #######################################################################################################################

    /****************************** start Api mobile ******************************/
    Route::group([ 'prefix' => 'mobile','namespace' => 'MobileApp','middleware' => ['closeApp']],function ()
    {

        //provinces with areas
        Route::get('provinces','ProvinceController@province');

        //selling Method
        Route::get('sellingMethod','SellingMethodController@sellingMethod');

        //setting
        Route::get('setting','SettingController@setting');

        //check token
        Route::get('checkToken','AuthController@authorizeUser');

        //start Register[company,merchant,client]
        Route::post('company','RegisterController@companyRegister');
        Route::post('merchant','RegisterController@merchantRegister');
        Route::post('client','RegisterController@clientRegister');
        Route::get('province','RegisterController@province');
        Route::get('area/{area}','RegisterController@area');

        //start reset[company,merchant,client]
        Route::post('userByPhone','AuthController@userByPhone');
        Route::post('userByCode','AuthController@userByCode');
        Route::post('forgot-password','AuthController@forgotPassword');
        Route::post('confirmOtp','AuthController@confirmOtp');
        Route::post('reset-password','AuthController@reset');

        //start Login[company,merchant,client]
        Route::post('login','AuthController@login');

        Route::middleware(['auth:api'])->group(function () {

            Route::get('me','AuthMobileController@me');
            Route::post('changePassword','AuthMobileController@changePassword');
            Route::post('updateProfile','ProfileController@updateProfile');

            //ads
            Route::get('popupAds','AdsController@popupAds');
            Route::get('slidersAds','AdsController@slidersAds');

            //category
            Route::get('categoryHome','CategoryController@categoryHome');
            Route::get('category','CategoryController@category');
            Route::get('subCategory/{id}','CategoryController@subCategory');
            Route::get('allSubCategory','CategoryController@allSubCategory');

            //company
            Route::get('company/{id}','CompanyController@companyBySubCategoryId');
            Route::get('company','CompanyController@company');
            Route::get('companyHome','CompanyController@companyHome');

            //product
            Route::get('productCompany','ProductController@productCompany');
            Route::get('getProductByCategory/{category}','ProductController@getProductByCategory');
            Route::get('getProductByBarcode/{barcode}','ProductController@getProductByBarcode');
            Route::get('pestProduct','ProductController@pestProduct');
            Route::get('similarProducts/{id}','ProductController@similarProducts');
            Route::get('products','ProductController@products');
            Route::get('filters','ProductController@filters');
            Route::get('waxes','ProductController@waxes');
            Route::get('spareParts','ProductController@spareParts');
            Route::get('spareAccessors','ProductController@spareAccessors');
            Route::get('getProductById/{category}/{subCategory?}','ProductController@getProductById');
            Route::get('getProductByName/{name}','ProductController@getProductByName');
            Route::get('getProductByGuarantee/{guarantee}','ProductController@getProductByGuarantee');
            Route::get('getProductByPrice/{price}','ProductController@getProductByPrice');
            Route::post('getProductBySearch','ProductController@getProductBySearch');

            //suggestion
            Route::get('getSuggestion','SuggestionClientController@getSuggestion');
            Route::post('suggestion','SuggestionClientController@suggestion');

            //complaint
            Route::get('getComplaint','ComplaintClientController@getComplaint');
            Route::post('complaint','ComplaintClientController@complaint');

            //tax
            Route::get('getTaxes','TaxController@getTaxes');

            //shippingPrice
            Route::get('shippingPrice','ShippingController@shippingPrice');

            //checkCoupon
            Route::post('checkCoupon','CouponController@checkCoupon');

            //order
            Route::post('companyOrder','CompanyOrderController@order');
            Route::post('order','OrderController@order');
            Route::get('trackingOrder','OrderController@trackingOrder');
            Route::get('pendingOrders','OrderController@pendingOrders');
            Route::get('clientsOrder/{id}', 'OrderController@clientsOrder');

            //clientDebts
            Route::get('clientDebts','ClientDebtsController@clientDebts');

            //periodicMaintenance
            Route::get('periodicMaintenanceCreate','PeriodicMaintenanceController@create');
            Route::post('periodicMaintenance','PeriodicMaintenanceController@store');
            Route::post('delayPeriodicMaintenance','PeriodicMaintenanceController@update');
            Route::get('nextMaintenance', 'PeriodicMaintenanceController@nextMaintenance');
            Route::get('filterWaxes', 'CompanyPeriodicMaintenanceController@filterWaxes');
            Route::post('companyPeriodicMaintenance','CompanyPeriodicMaintenanceController@store');

            //problemLead
            Route::post('problemLead','ProblemLeadController@store');

            //termsPrivacy
            Route::get('termsPrivacy','TermsPrivacyController@index');

            //share
            Route::get('allCompetition','ShareController@allCompetition');
            Route::get('getCompetitionByCount/{count}','ShareController@getCompetitionByCount');
            Route::get('getCompetitionByCountAndDays/{count}/{days}', 'ShareController@getCompetitionByCountAndDays');
            Route::post('share','ShareController@store');
            Route::get('wallet/{id}', 'ShareController@wallet');

            //wallet
            Route::get('wallet','WalletController@wallet');
            Route::get('welcomePoints','WalletController@welcomePoints');
            Route::get('productPoints','WalletController@productPoints');
            Route::get('pointDetails','WalletController@pointDetails');

            //notification
            Route::get('getAllNot','NotificationController@getAllNot');
            Route::get('getNotNotRead','NotificationController@getNotNotRead');

            //start logout
            Route::post('logout','AuthController@logout');
        });
    });
    /****************************** end Api mobile ******************************/

    #######################################################################################################################
    ######################################   End Mobile Api   ###########################################################
    #######################################################################################################################



    // start representative
    Route::group([ 'prefix' => 'representative','namespace' => 'Representative'],function () {

        Route::post('signIn','AuthRepresentativeController@signIn');

        Route::middleware(['auth:api'])->group(function () {

            Route::get('me','AuthRepresentativeController@me');
            Route::post('changePassword','AuthRepresentativeController@changePassword');
            Route::get('representativeOrder','OrderController@getRepresentativeOrder');
            Route::get('orderDelivered/{id}','NotificationOrderController@orderDelivered');
            Route::get('orderReturned/{id}','NotificationOrderController@orderReturned');

        });

    });
    //end representative
});
