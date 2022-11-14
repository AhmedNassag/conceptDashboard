<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ClientAccount;
use App\Models\ClientExpense;
use App\Models\Company;
use App\Models\ExaminationRecord;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Store;
use App\Models\StoreProduct;
use App\Models\Supplier;
use App\Models\SupplierAccount;
use App\Models\SupplierExpense;
use App\Models\Treasury;
use App\Models\User;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $purchases = Purchase::with(['purchaseProducts.product.mainMeasurementUnit','store'])
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('note', 'like', '%' . $request->search . '%')
                        ->orWhere('price', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('store','name','like','%'.$request->search.'%')
                        ->orWhereRelation('supplier','name_supplier','like','%'.$request->search.'%')
                        ->orWhereRelation('user','name','like','%'.$request->search.'%');
                });
            })->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('created_at', ">=", $request->from_date)
                        ->whereDate('created_at', "<=", $request->to_date);
                });
            })->where(function ($q) use ($request) {
                $q->when($request->purchase_id, function ($q) use ($request) {
                    $q->where('id', $request->purchase_id);
                });
            })->latest()->paginate(5);
        return $this->sendResponse(['purchases' => $purchases], 'Data exited successfully');
    }

    public function create(){
        $products =  Product::where('status', 1)->with('mainMeasurementUnit', 'subMeasurementUnit', 'company')->get();
        $stores = Store::where('status', 1)->get();
        $suppliers = Supplier::where('status', 1)->get();
        $clients = User::where('status',1)->whereAuthId(2)->whereJsonContains('role_name','client')->get();
        $treasuries = Treasury::where('active',1)->get();
        return $this->sendResponse(['products' => $products,'stores'=>$stores,'suppliers'=>$suppliers,'clients'=>$clients,'treasuries'=>$treasuries], 'Data exited successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'store_id' => 'required|integer|exists:stores,id',
                'treasury_id' => 'required|integer|exists:treasuries,id',
                'supplier_id' => 'nullable|required_if:type_invoice,==,1|integer|exists:suppliers,id',
                'type_invoice' => 'required|integer',
                'is_received' => 'required|integer',
                'note' => 'nullable|string|min:5',
                'price' => 'required|numeric',
                'product.*.product_id' => 'required|integer|exists:products,id',
                'product.*.quantity' => 'required|numeric',
                'product.*.sub_quantity' => 'required|numeric',
                'product.*.count_unit' => 'required|numeric',
                'product.*.price' => 'required|numeric',
                'product.*.production_date' => 'nullable|date',
                'product.*.expiry_date' => 'nullable|date|after:product.*.production_date',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $product_price = 0;

            $purchase = Purchase::create([
                'store_id' => $request->store_id,
                'supplier_id' => $request->type_invoice == 1 ? $request->supplier_id : null ,
                'user_id' => $request->type_invoice == 0 ? $request->supplier_id : null ,
                'note' => $request->note,
                'price' => $request->price,
                'is_received' => $request->is_received,
                'type_invoice' => $request->type_invoice,
            ]);

            foreach ($request->product as $product){
                PurchaseProduct::create([
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'sub_quantity' => $product['sub_quantity'],
                    'price' => $product['price'],
                    'production_date' => $product['production_date'],
                    'expiry_date' => $product['expiry_date'],
                    'purchase_id' => $purchase['id'],
                    'count_unit' => $product['count_unit'],
                ]);
                $product_price +=  $product['quantity'] * $product['price'];
                $product_price +=  $product['sub_quantity'] * ($product['price'] / $product['count_unit']) ;
            }

            $amount_accounts = $product_price - $request->price;

            if ($product_price != $request->price)
            {
                if ($request->type_invoice == 1){
                    SupplierAccount::create([
                        'supplier_id' => $request->supplier_id,
                        'purchase_id' => $purchase['id'],
                        'amount' => $amount_accounts
                    ]);
                    if ($request->price > 0){
                        SupplierExpense::create([
                            'supplier_id' => $request->supplier_id,
                            'expense_id' => 1,
                            'treasury_id' => $request->treasury_id,
                            'purchase_id' => $purchase['id'],
                            'amount' => $request->price,
                            'payment_date' => now(),
                            'user_id' => auth()->id(),
                        ]);
                    }
                }else{
                    ClientAccount::create([
                        'user_id' => $request->supplier_id,
                        'purchase_id' => $purchase['id'],
                        'amount' => $amount_accounts
                    ]);
                    if ($request->price > 0) {
                        ClientExpense::create([
                            'client_id' => $request->supplier_id,
                            'expense_id' => 1,
                            'treasury_id' => $request->treasury_id,
                            'purchase_id' => $purchase['id'],
                            'amount' => $request->price,
                            'payment_date' => now(),
                            'user_id' => auth()->id(),
                        ]);
                    }
                }
            }else{
                if ($request->type_invoice == 1 && $request->price > 0){

                    SupplierExpense::create([
                        'supplier_id' => $request->supplier_id,
                        'expense_id' => 1,
                        'treasury_id' => $request->treasury_id,
                        'purchase_id' => $purchase['id'],
                        'amount' => $request->price,
                        'payment_date' => now(),
                        'user_id' => auth()->id(),
                    ]);

                }else{
                    if ($request->price > 0) {
                        ClientExpense::create([
                            'client_id' => $request->supplier_id,
                            'expense_id' => 1,
                            'treasury_id' => $request->treasury_id,
                            'purchase_id' => $purchase['id'],
                            'amount' => $request->price,
                            'payment_date' => now(),
                            'user_id' => auth()->id(),
                        ]);
                    }
                }
            }

            if ($request->is_received == 1){
                $purchase_products = PurchaseProduct::where('purchase_id',$purchase['id'])->get();
                $this->storeProduct($purchase_products,$purchase);
            }

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }

    }

    public function storeProduct($purchase_products,$purchase){

        $employee_id = auth()->user()->id;

        $examination = ExaminationRecord::create([
            'user_id'=>$employee_id,
            'purchase_id'=>$purchase['id']
        ]);

        foreach ($purchase_products as $value){

            $product = Product::find($value['product_id']);

            StoreProduct::create([
                'examination_record_id'=>$examination->id,
                'product_status_id'=>1,
                'product_id'=>$value['product_id'],
                'main_measurement_unit_id'=>$product->main_measurement_unit_id,
                'sub_measurement_unit_id'=>$product->sub_measurement_unit_id,
                'sub_category_id'=> $product->sub_category_id,
                'store_id'=>$purchase['store_id'],
                'quantity'=>$value['quantity'],
                'sub_quantity'=>$value['sub_quantity'],
                'expiry_date'=>$value['expiry_date'],
                'production_date'=>$value['production_date'],
                'count_unit'=>$value['count_unit'],
                'purchase_product_id'=>$value['id'],
                'sub_quantity_order'=>0,
            ]);

        }
    }

    public function edit($id)
    {
        try {

            $purchase = Purchase::with(['user','supplier','purchaseProducts'=>function($q){
                $q->with(['product' =>function($qu){
                    $qu->with('mainMeasurementUnit','subMeasurementUnit');
                }]);
            }])->find($id);

            $stores = Store::where('status', 1)->get();
            $suppliers = Supplier::where('status', 1)->with('supplierExpense',function($q) use ($id){
                $q->where('purchase_id',$id);
            })->get();
            $products =  Product::where('status', 1)->with('mainMeasurementUnit', 'subMeasurementUnit', 'company')->get();
            $clients = User::where('status',1)->whereAuthId(2)->whereJsonContains('role_name','client')
                ->with('clientExpense',function($q) use ($id){
                $q->where('purchase_id',$id);
            })->get();
            $treasuries = Treasury::where('active',1)->get();
            return $this->sendResponse(['purchase' => $purchase,'treasuries' => $treasuries,'clients' => $clients,'products' => $products,'stores'=>$stores,'suppliers'=>$suppliers], 'Data exited successfully');

        } catch (\Exception $e) {

            return $this->sendError('An error occurred in the system');

        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'store_id' => 'required|integer|exists:stores,id',
                'treasury_id' => 'required|integer|exists:treasuries,id',
                'supplier_id' => 'nullable|required_if:type_invoice,==,1|integer|exists:suppliers,id',
                'type_invoice' => 'required|integer',
                'is_received' => 'required|integer',
                'note' => 'nullable|string|min:5',
                'price' => 'required|numeric',
                'product.*.product_id' => 'required|integer|exists:products,id',
                'product.*.quantity' => 'required|numeric',
                'product.*.sub_quantity' => 'required|numeric',
                'product.*.count_unit' => 'required|numeric',
                'product.*.price' => 'required|numeric',
                'product.*.production_date' => 'nullable|date',
                'product.*.expiry_date' => 'nullable|date|after:product.*.production_date',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $product_price = 0;
            $purchase = Purchase::find($id);

            $purchase->update([
                'store_id' => $request->store_id,
                'supplier_id' => $request->type_invoice == 1 ? $request->supplier_id : null ,
                'user_id' => $request->type_invoice == 0 ? $request->supplier_id : null ,
                'note' => $request->note,
                'price' => $request->price,
                'is_received' => $request->is_received,
                'type_invoice' => $request->type_invoice,
            ]);

            foreach ($purchase->purchaseProducts as $data){
                $data->delete();
            }

            foreach ($request->product as $product){
                PurchaseProduct::create([
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'sub_quantity' => $product['sub_quantity'],
                    'price' => $product['price'],
                    'production_date' => $product['production_date'],
                    'expiry_date' => $product['expiry_date'],
                    'purchase_id' => $id,
                    'count_unit' => $product['count_unit'],
                ]);
                $product_price +=  $product['quantity'] * $product['price'];
                $product_price +=  $product['sub_quantity'] * ($product['price'] / $product['count_unit']) ;
            }

            foreach ($purchase->supplierExpense as $data){
                $data->delete();
            }

            foreach ($purchase->clientExpense as $data){
                $data->delete();
            }

            foreach ($purchase->supplierAccounts as $data){
                $data->delete();
            }

            foreach ($purchase->clientAccount as $data){
                $data->delete();
            }

            $amount_accounts = $product_price - $request->price;

            if ($product_price != $request->price)
            {
                if ($request->type_invoice == 1){

                    SupplierAccount::create([
                        'supplier_id' => $request->supplier_id,
                        'purchase_id' => $purchase['id'],
                        'amount' => $amount_accounts
                    ]);
                    if ($request->price > 0){
                        SupplierExpense::create([
                            'supplier_id' => $request->supplier_id,
                            'expense_id' => 1,
                            'treasury_id' => $request->treasury_id,
                            'purchase_id' => $purchase['id'],
                            'amount' => $request->price,
                            'payment_date' => now(),
                            'user_id' => auth()->id(),
                        ]);
                    }
                }else{
                    ClientAccount::create([
                        'user_id' => $request->supplier_id,
                        'purchase_id' => $purchase['id'],
                        'amount' => $amount_accounts
                    ]);
                    if ($request->price > 0) {
                        ClientExpense::create([
                            'client_id' => $request->supplier_id,
                            'expense_id' => 1,
                            'treasury_id' => $request->treasury_id,
                            'purchase_id' => $purchase['id'],
                            'amount' => $request->price,
                            'payment_date' => now(),
                            'user_id' => auth()->id(),
                        ]);
                    }
                }
            }else{
                if ($request->type_invoice == 1 && $request->price > 0){

                    SupplierExpense::create([
                        'supplier_id' => $request->supplier_id,
                        'expense_id' => 1,
                        'treasury_id' => $request->treasury_id,
                        'purchase_id' => $purchase['id'],
                        'amount' => $request->price,
                        'payment_date' => now(),
                        'user_id' => auth()->id(),
                    ]);

                }else{
                    if ($request->price > 0) {
                        ClientExpense::create([
                            'client_id' => $request->supplier_id,
                            'expense_id' => 1,
                            'treasury_id' => $request->treasury_id,
                            'purchase_id' => $purchase['id'],
                            'amount' => $request->price,
                            'payment_date' => now(),
                            'user_id' => auth()->id(),
                        ]);
                    }
                }
            }

            if ($request->is_received == 1){
                $purchase_products = PurchaseProduct::where('purchase_id',$purchase['id'])->get();
                $this->storeProduct($purchase_products,$purchase);
            }


            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');
        } catch (\Exception $e) {

            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase = Purchase::find($id);
        if ($purchase->purchaseReturns == null && $purchase->examinationRecord == null)
        {
            $purchase->delete();
            return $this->sendResponse([],'Deleted successfully');
        }else{
            return $this->sendError('ID is not exist');
        }
    }
}
