<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ClientAccount;
use App\Models\ClientExpense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\PurchaseReturn;
use App\Models\ReturnProduct;
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

class PurchaseReturnController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $purchases = PurchaseReturn::with(['user','supplier','returnProducts'=>function($qu){
            $qu->with(['product','purchaseProduct']);
            },'store','purchase'=>function($qu){
            $qu->with(['purchaseProducts.product.mainMeasurementUnit','store','supplier']);
            }])->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->orWhereRelation('user','name','like','%'.$request->search.'%')
                        ->orWhereRelation('store','name','like','%'.$request->search.'%')
                        ->orWhereRelation('supplier','name_supplier','like','%'.$request->search.'%')
                        ->orWhereRelation('client','name','like','%'.$request->search.'%');
                });
            })->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('created_at', ">=", $request->from_date)
                        ->whereDate('created_at', "<=", $request->to_date);
                });
            })->where(function ($q) use ($request) {
                $q->when($request->supplier_id, function ($q) use ($request) {
                    $q->where('supplier_id', $request->supplier_id);
                });
            })->latest()->paginate(5);

        $treasuries = Treasury::where('active',1)->get();

        return $this->sendResponse(['purchases' => $purchases,'treasuries'=>$treasuries], 'Data exited successfully');
    }

    public function create(){
        $products =  Product::where('status', 1)
            ->with(['mainMeasurementUnit', 'subMeasurementUnit', 'company','purchaseProducts','storeProducts'])->whereHas('storeProducts')->get();
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
                'note' => 'nullable|string|min:5',
                'price' => 'required|numeric',
                'product.*.product_id' => 'required|integer|exists:products,id',
                'product.*.quantity' => 'required|numeric|lte:product.*.total_main_quantity',
                'product.*.sub_quantity' => 'required|numeric|lte:product.*.total_sub_quantity',
                'product.*.count_unit' => 'required|numeric',
                'product.*.price' => 'required|numeric',
                'product.*.sub_price' => 'required|numeric',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $purchaseReturn = PurchaseReturn::create([
                'user_id' => auth()->id(),
                'store_id' => $request->store_id,
                'supplier_id' => $request->type_invoice == 1 ? $request->supplier_id : null ,
                'client_id' => $request->type_invoice == 0 ? $request->supplier_id : null ,
                'note' => $request->note,
            ]);

            foreach ($request->product as $product){
                $storeProducts = StoreProduct::where([
                    ['store_id',$request->store_id],
                    ['product_id',$product['product_id']],
                ])->get();
                $quantity = $product['quantity'] ?? 0;
                $sub_quantity = $product['sub_quantity'] ?? 0;

                foreach ($storeProducts as $storeProduct){
                    $return_main_quantity = 0;
                    $return_sub_quantity = 0;
                    if ($storeProduct->main_quantity > 0 && $quantity > 0){
                        if ($storeProduct->main_quantity >= $quantity){
                            $return_main_quantity = $quantity;
                            $quantity -= $return_main_quantity;
                        }else{
                            $return_main_quantity = $storeProduct->main_quantity;
                            $quantity -= $return_main_quantity;
                        }
                    }

                    if ( $storeProduct->sub_quantity_order - ($storeProduct->main_quantity * $storeProduct->count_unit) > 0 && $sub_quantity > 0){
                        if ($storeProduct->sub_quantity_order - ($storeProduct->main_quantity * $storeProduct->count_unit) >= $sub_quantity){
                            $return_sub_quantity = $sub_quantity;
                            $sub_quantity -= $return_sub_quantity;
                        }else{
                            $return_sub_quantity = $storeProduct->sub_quantity_order - ($storeProduct->main_quantity * $storeProduct->count_unit);
                            $sub_quantity -= $return_sub_quantity;
                        }
                    }
                    if ($return_main_quantity > 0 || $return_sub_quantity > 0){
                        ReturnProduct::create([
                            'purchase_return_id' => $purchaseReturn['id'],
                            'product_id' => $product['product_id'],
                            'purchase_product_id' => $storeProduct['purchase_product_id'],
                            'quantity' => $return_main_quantity,
                            'sub_quantity' => $return_sub_quantity,
                            'price' => $product['price'],
                            'sub_price' => $product['sub_price'],
                            'return_status' => 1,
                        ]);
                        $storeProduct->update([
                            'sub_quantity_order'=>0,
                        ]);
                    }
                }
            }

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }

    public function edit($id)
    {
       //
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
       //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $purchaseReturn = PurchaseReturn::find($id);
       foreach ($purchaseReturn->returnProducts as $returnProduct){
           $purchaseProduct = $returnProduct->purchaseProduct;
           $returnProduct->delete();
           foreach ($purchaseProduct->storeProducts as $storeProduct){
               $storeProduct->update([
                   'sub_quantity_order'=>0,
               ]);
           }
       }
        $purchaseReturn->delete();

        return $this->sendResponse([],'Deleted successfully');
    }
}
