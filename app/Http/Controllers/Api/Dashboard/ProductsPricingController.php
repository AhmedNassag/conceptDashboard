<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PricingHistory;
use App\Models\Product;
use App\Models\ProductPricing;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductsPricingController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('status', 1)->get();

        return $this->sendResponse(['products' => $products], 'Data exited successfully');
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
                'product_id' => 'required|integer|exists:products,id',
                'product.*.id' => 'required|integer|exists:product_pricings,id',
                'product.*.less_quantity' => 'required|integer',
                'product.*.max_quantity' => 'required|integer|gte:product.*.less_quantity',
                'product.*.active' => 'required|integer',
                'product.*.selling_method_id' => 'required|integer|exists:selling_methods,id',
                'product.*.profit' => 'required|numeric',
                'product.*.profit_percentage' => 'required|numeric',
                'product.*.price' => 'required|numeric',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            foreach ($request->product as $item){
                $product = ProductPricing::find($item['id']);
                $product->update([
                    'price'=>$item['price'],
                    'less_quantity'=>$item['less_quantity'],
                    'max_quantity'=>$item['max_quantity'],
                    'active'=>$item['active'],
                    'measurement_unit_id'=>$item['measurement_unit_id']
                ]);

                PricingHistory::create([
                   'product_id' =>$request->product_id,
                   'user_id' =>auth()->user()->id,
                   'selling_method_id' =>$item['selling_method_id'],
                   'measurement_unit_id' =>$item['measurement_unit_id'],
                   'price' =>$item['price'],
                   'profit' =>$item['profit'],
                   'profit_percentage' =>$item['profit_percentage'],
                    'less_quantity'=>$item['less_quantity'],
                    'max_quantity'=>$item['max_quantity'],
                ]);
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
        $product = Product::with(['productPrice'=>function($q){
            $q->with(['sellingMethod','measurementUnit']);
        },'purchaseProducts','storeProducts'=>function($qu){
            $qu->with(['productStatus','store']);
        },'mainMeasurementUnit','subMeasurementUnit'])->find($id);

        return $this->sendResponse(['product' => $product], 'Data exited successfully');
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
      //
    }
}
