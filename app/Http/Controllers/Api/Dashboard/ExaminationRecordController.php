<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ExaminationRecord;
use App\Models\Product;
use App\Models\ProductStatus;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\PurchaseReturn;
use App\Models\ReturnProduct;
use App\Models\StoreProduct;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExaminationRecordController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $purchases = Purchase::with(['purchaseProducts.product'=>function($qu){
            $qu->with(['mainMeasurementUnit','subMeasurementUnit']);
        },'store','supplier','examinationRecord'=>function($qu){
            $qu->with(['storeProducts','user']);
        },'purchaseReturns.returnProducts'])
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('note', 'like', '%' . $request->search . '%')
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
      //
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
                'purchase_id' => 'required|integer|exists:purchases,id',
                'store_id' => 'required|integer|exists:stores,id',
                'notes_received' => 'nullable|string|min:5',
                'notes_return' => 'nullable|string|min:5',
                'product.*.product_id' => 'required|integer|exists:products,id',
                'product.*.product_status_id' => 'required|integer|exists:product_statuses,id',
                'product.*.quantity_received' => 'required|integer',
                'product.*.sub_quantity_received' => 'required|integer',
                'product.*.return_quantity' => 'required|integer',
                'product.*.return_sub_quantity' => 'required|integer',
                'product.*.count_unit' => 'required|integer',
                'product.*.production_date' => 'nullable|date',
                'product.*.expiry_date' => 'nullable|date|after:product.*.production_date',
                'product.*.note' => 'nullable|string|min:3',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            if ($request->received > 0){
               $this->storeProduct($request);
            }
            if ($request->return > 0){
               $this->purchaseReturn($request);
            }
            $purchase = Purchase::find($request->purchase_id);
            $purchase->update([
                'is_received' => 1,
            ]);

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }

    }

    public function storeProduct($request){

        $employee_id = auth()->user()->id;

        $examination = ExaminationRecord::create([
            'user_id'=>$employee_id,
            'purchase_id'=>$request->purchase_id,
            'note'=>$request->notes_received,
        ]);

        foreach ($request->product as $value){
            if ($value['quantity_received'] > 0 || $value['sub_quantity_received']){
                $product = Product::find($value['product_id']);
                StoreProduct::create([
                    'examination_record_id'=>$examination->id,
                    'product_status_id'=>$value['product_status_id'],
                    'product_id'=>$value['product_id'],
                    'main_measurement_unit_id'=>$product->main_measurement_unit_id,
                    'sub_measurement_unit_id'=>$product->sub_measurement_unit_id,
                    'sub_category_id'=> $product->sub_category_id,
                    'store_id'=>$request['store_id'],
                    'quantity'=>$value['quantity_received'],
                    'sub_quantity'=>$value['sub_quantity_received'],
                    'expiry_date'=>$value['expiry_date'],
                    'production_date'=>$value['production_date'],
                    'count_unit'=>$value['count_unit'],
                    'purchase_product_id'=>$value['purchase_product_id'],
                    'sub_quantity_order'=>0,
                ]);
            }
        }
    }

    public function purchaseReturn($request){

        $employee_id = auth()->user()->id;

        $purchase = Purchase::find($request->purchase_id);

        $PurchaseReturn = PurchaseReturn::create([
            'user_id'=>$employee_id,
            'purchase_id'=>$request->purchase_id,
            'supplier_id'=>$purchase->type_invoice == 1 ? $purchase->supplier_id: null,
            'client_id'=>$purchase->type_invoice == 0 ? $purchase->user_id: null,
            'store_id'=>$request->store_id,
            'note'=>$request->notes_return
        ]);

        foreach ($request->product as $value){

            if ($value['return_quantity'] > 0 || $value['return_sub_quantity'] > 0){
                $purchase_product = PurchaseProduct::find($value['purchase_product_id']);
                ReturnProduct::create([
                    'purchase_return_id'=>$PurchaseReturn->id,
                    'product_id'=>$value['product_id'],
                    'quantity'=>$value['return_quantity'],
                    'sub_quantity'=>$value['return_sub_quantity'],
                    'price'=>$purchase_product['price'],
                    'sub_price'=>$purchase_product['price'] / $purchase_product['count_unit'],
                    'note'=>$value['note'],
                    'purchase_product_id'=>$value['purchase_product_id'],
                ]);
            }
        }
    }

    public function edit($id)
    {
        try {

            $purchase = Purchase::with(['purchaseProducts.product'=>function($qu){
                $qu->with(['mainMeasurementUnit','subMeasurementUnit']);
            },'store','supplier','examinationRecord.storeProducts','purchaseReturns.returnProducts'])->find($id);

            $productStatuses = ProductStatus::all();

            return $this->sendResponse(['purchase' => $purchase,'productStatuses' => $productStatuses], 'Data exited successfully');

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
        $purchase = Purchase::with(['purchaseProducts.product'=>function($qu){
            $qu->with(['mainMeasurementUnit','subMeasurementUnit']);
        },'store','supplier'])->find($id);

        $productStatuses = ProductStatus::all();

        return $this->sendResponse(['purchase' => $purchase,'productStatuses' => $productStatuses], 'Data exited successfully');

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
                'purchase_id' => 'required|integer|exists:purchases,id',
                'store_id' => 'required|integer|exists:stores,id',
                'notes_received' => 'nullable|string|min:5',
                'notes_return' => 'nullable|string|min:5',
                'product.*.product_id' => 'required|integer|exists:products,id',
                'product.*.product_status_id' => 'required|integer|exists:product_statuses,id',
                'product.*.quantity_received' => 'required|integer',
                'product.*.sub_quantity_received' => 'required|integer',
                'product.*.return_quantity' => 'required|integer',
                'product.*.return_sub_quantity' => 'required|integer',
                'product.*.count_unit' => 'required|integer',
                'product.*.production_date' => 'nullable|date',
                'product.*.expiry_date' => 'nullable|date|after:product.*.production_date',
                'product.*.note' => 'nullable|string|min:3',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $purchase = Purchase::find($id);
            if ($purchase->examinationRecord){
                $purchase->examinationRecord->delete();
            }
            if ( $purchase->purchaseReturns ){
                $purchase->purchaseReturns->delete();
            }
            if ($request->received > 0){
                $this->storeProduct($request);
            }
            if ($request->return > 0){
                $this->purchaseReturn($request);
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
    public function destroy(Purchase $purchase)
    {

    }
}
