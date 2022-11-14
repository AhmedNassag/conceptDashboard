<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\OfferDiscount;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\OrderStoreProduct;
use App\Models\Product;
use App\Models\ProductPricing;
use App\Models\Store;
use App\Models\StoreProduct;
use App\Models\Tax;
use App\Models\Treasury;
use App\Models\User;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderOnlineController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders= Order::where([
            ['is_online',1],
            ['order_status_id','!=',5],
        ])->where([
            ['is_online',1],
            ['order_status_id','!=',6],
        ])->where([
            ['is_online',1],
            ['order_status_id','!=',7],
        ])->with(['orderStatus','representative:id,name','orderOtherOffer','user' => function ($q){
                $q->with('client');
            },'orderOffer','orderTax','orderDetails' => function ($q) {
                $q->with(['sellingMethod:id,name',
                    'mainMeasurementUnit:id,name',
                    'subMeasurementUnit:id,name',
                    'product:id,name'
                ]);
            },
            'store:id,name',
        ])->where(function ($q) use ($request) {
            $q->when($request->search, function ($q) use ($request) {
                return $q->where('id','like','%'.$request->search.'%')
                    ->orWhereRelation('store','name','like','%'.$request->search.'%')
                    ->orWhereRelation('orderStatus','name','like','%'.$request->search.'%')
                    ->orWhereRelation('user','name','like','%'.$request->search.'%');
            });
        })->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('created_at', ">=", $request->from_date)
                        ->whereDate('created_at', "<=", $request->to_date);
                });
            })
            ->where(function ($q) use ($request) {
                $q->when($request->status, function ($q) use ($request) {
                    $q->where('order_status_id', $request->status);
                });
            })->where(function ($q) use ($request) {
                $q->when($request->order_id, function ($q) use ($request) {
                    $q->where('id', $request->order_id);
                });
            })->latest()->paginate(15);

        $treasuries = Treasury::where('active',1)->get();

        return $this->sendResponse(['orders' => $orders,'treasuries'=>$treasuries], 'Data exited successfully');
    }

    public function show($id){
        $order = Order::where('is_online',1)->with(['orderStatus','orderOtherOffer','user' => function ($q){
            $q->with('client');
        },'orderOffer','orderTax','orderDetails' => function ($q) {
            $q->with(['sellingMethod:id,name',
                'mainMeasurementUnit:id,name',
                'subMeasurementUnit:id,name',
                'product:id,name'
            ]);
        },'store:id,name',
        ])->find($id);

        return $this->sendResponse(['order' => $order], 'Data exited successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       //
    }

    public function storeChoose(Request $request)
    {
        $productStore =  Product::select('id','name','barcode','count_unit')
            ->whereRelation('storeProducts.store','store_id',$request->store_id)
            ->whereRelation('productPrice','selling_method_id',$request->selling_method_id)
            ->with(['productPrice' => function ($q) use($request){
                $q->where('selling_method_id',$request->selling_method_id)->with('measurementUnit:id,name');
            },'storeProducts' => function ($q) {
                $q->with('purchaseProduct:id,price')
                    ->where('sub_quantity_order','>',0)
                    ->whereNotNull('purchase_product_id');
            }])->get();

        return $this->sendResponse([
            'products' => $productStore
        ], 'Data exited successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tax = Tax::select('id','name','percentage')->where('status',true)->get();

        $offerDiscount = OfferDiscount::select('id','name','type','value')->where('status',true)->get();

        $order = Order::with('orderOtherOffer')->
        with([
            'orderOffer',
            'orderTax',
            'orderDetails',
            'store:id,name',
            'user' => function ($q){
                $q->with('client.sellingMethod');
            }
        ])->find($id);

        $productStore =  Product::select('id','name','barcode','count_unit')
            ->whereRelation('storeProducts.store','store_id',$order->store_id)
            ->whereRelation('productPrice','selling_method_id',$order->user->client->selling_method_id)
            ->with(['productPrice' => function ($q) use($order){
                $q->where('selling_method_id',$order->user->client->selling_method_id)->with('measurementUnit:id,name');
            },'storeProducts' => function ($q) {
                $q->with('purchaseProduct:id,price')
                    ->where('sub_quantity_order','>',0)
                    ->whereNotNull('purchase_product_id');
            }])->get();

        return $this->sendResponse([
            'taxs' => $tax,
            'offerDiscounts'=> $offerDiscount,
            'order' => $order,
            'products' => $productStore
        ], 'Data exited successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'store_id' => 'required|integer|exists:stores,id',
                'client_id' => 'required|integer|exists:users,id',
                'selling_method_id' => 'required|integer|exists:selling_methods,id',
                'discounts.*' => 'nullable' ,
                'taxs.*' => 'nullable' ,
                'nameOffer' => 'nullable|string',
                'priceOffer' => 'nullable|numeric',
                'product.*.product_id' => 'required|integer|exists:products,id',
                'product.*.mainId' => 'required|integer|exists:product_pricings,id',
                'product.*.branchId' => 'required|integer|exists:product_pricings,id',
                'product.*.mainQuantity' => 'required|integer',
                'product.*.branchQuantity' => 'required|integer'
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $order = Order::find($id);

            if($order->orderDetails){
                foreach ($order->orderDetails as $detail){
                    foreach ($detail->orderStoreProducts as $orderStoreProduct){
                        $store_product_id = $orderStoreProduct->store_product_id;
                        $orderStoreProduct->delete();
                        $store_product = StoreProduct::find($store_product_id);
                        $store_product->update([
                            'sub_quantity_order' => 0
                        ]);
                    }
                    $detail->delete();
                }
            }

            if($order->orderTax){
                foreach ($order->orderTax as $tax){
                    $tax->delete();
                }
            }

            if($order->orderOtherOffer){
                $order->orderOtherOffer->delete();
            }


            $totalBefourDiscount = 0;
            $errors = [];
            foreach ($request->product as $product){
                $product_pricing_main = ProductPricing::where([
                    ['id', $product['mainId']],
                    ['product_id', $product['product_id']],
                    ['selling_method_id', $request->selling_method_id],
                ])->first();

                $product_pricing_branch = ProductPricing::where([
                    ['id', $product['branchId']],
                    ['product_id', $product['product_id']],
                    ['selling_method_id', $request->selling_method_id],
                ])->first();

                if ($product['mainQuantity'] > $product_pricing_main->available_quantity) {
                    $errors['products.' . $product . '.mainQuantity'][] = "لا يوجد كمية متاحة فى المخزن";
                    return $this->sendError(trans('message.messageError'), $errors);
                }

                $totalBefourDiscount += (($product_pricing_main['price'] * $product['mainQuantity']) + ($product_pricing_branch['price'] * $product['branchQuantity'])) ;
            }

            $discount = $order->discount;

            $totalAfterDiscount = $totalBefourDiscount - $discount - $request->priceOffer;

            $tax = 0;
            foreach ($request->taxs as $tax_price) {
                $ta = Tax::find($tax_price);
                $tax += ($totalAfterDiscount * $ta->percentage)/ 100;
            }$tax = $order->tax;

            $totalAfterTax = $totalAfterDiscount + $tax;

            $order->update([
                'subTotal' => $totalBefourDiscount,
                'tax' => $tax,
                'total' => $totalAfterTax
            ]);

            if($request->taxs){
                foreach ($request->taxs as $ta){
                    $off = Tax::find($ta);
                    $order->orderTax()->create([
                        'tax_id' => $off['id'],
                        'name' => $off['name'],
                        'percentage' => $off['percentage'],
                    ]);
                }
            }

            if($request->nameOffer != null && $request->priceOffer){
                $order->orderOtherOffer()->create([
                    'name' => $request->nameOffer,
                    'offer' => $request->priceOffer
                ]);
            }

            foreach ($request->product as $product) {

                $product_pricing = ProductPricing::where([
                    ['id', $product['mainId']],
                    ['product_id', $product['product_id']],
                    ['selling_method_id', $request->selling_method_id],
                ])->first();

                $order_details_id = null;

                $order_details = OrderDetails::create([
                    'order_id' =>$order['id'],
                    'quantity' => $product['mainQuantity'],
                    'sub_quantity' => $product['branchQuantity'],
                    'main_measurement_unit_id' => $product_pricing->product->main_measurement_unit_id,
                    'sub_measurement_unit_id' => $product_pricing->product->sub_measurement_unit_id,
                    'selling_method_id' => $request->selling_method_id,
                    'product_id' => $product['product_id'],
                    'price' => $product['mainPrice'],
                    'sub_price' => $product['branchPrice'],
                ]);
                $order_details_id = $order_details->id;
                $main_measurement_unit_id = $order_details->main_measurement_unit_id;
                $sub_measurement_unit_id = $order_details->sub_measurement_unit_id;

                $this->storeProductData(
                    $request->store_id,
                    $product['product_id'],
                    $main_measurement_unit_id,
                    $sub_measurement_unit_id,
                    $product['mainQuantity'],
                    $product['branchQuantity'],
                    $order_details_id
                );
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $order = Order::find($id);
            if ($order && $order->order_status_id == 1){
                foreach ($order->orderDetails as $detail){
                    foreach ($detail->orderStoreProducts as $orderStoreProduct){
                        $store_product_id = $orderStoreProduct->store_product_id;
                        $orderStoreProduct->delete();
                        $store_product = StoreProduct::find($store_product_id);
                        $store_product->update([
                            'sub_quantity_order' => 0
                        ]);
                    }
                }
                $order->delete();
                return $this->sendResponse([],'Deleted successfully');
            }else{
                return $this->sendError('ID is not exist');
            }

        }catch (\Exception $e){
            return $this->sendError('An error occurred in the system');
        }
    }


    public function storeProductData($store_id,$product_id,$measurement_unit_id,$measurement_sub_unit_id,$quantity,$sub_quantity,$order_details_id){

        $store_main_measurement = StoreProduct::where([
            ['store_id',$store_id],
            ['product_id',$product_id],
            ['main_measurement_unit_id',$measurement_unit_id],
        ])->get();

        $store_sub_measurement = StoreProduct::where([
            ['store_id',$store_id],
            ['product_id',$product_id],
            ['sub_quantity_order','>',0],
            ['sub_measurement_unit_id',$measurement_sub_unit_id],
        ])->get();

        if($store_main_measurement){
            foreach ($store_main_measurement as $store){
                if ($store->main_quantity > 0 && $quantity > 0){
                    if ($store->main_quantity >= $quantity){
                        OrderStoreProduct::create([
                            'order_details_id'=>$order_details_id,
                            'store_product_id'=>$store['id'],
                            'quantity'=>$quantity,
                            'measurement_unit_id'=>$measurement_unit_id,
                            'product_id'=>$product_id,
                        ]);
                        $store->update([
                            'sub_quantity_order' => 0
                        ]);
                        $quantity = 0;
                    }else{
                        OrderStoreProduct::create([
                            'order_details_id'=>$order_details_id,
                            'store_product_id'=>$store['id'],
                            'quantity'=> $store->main_quantity,
                            'measurement_unit_id'=>$measurement_unit_id,
                            'product_id'=>$product_id,
                        ]);
                        $quantity -= $store->main_quantity ;
                        $store->update([
                            'sub_quantity_order' => 0
                        ]);
                    }
                }

            }
        }

        if($store_sub_measurement){
            foreach ($store_sub_measurement as $store){
                if ($store->sub_quantity_order > 0 && $sub_quantity > 0){
                    if ($store->sub_quantity_order >= $sub_quantity){
                        OrderStoreProduct::create([
                            'order_details_id'=>$order_details_id,
                            'store_product_id'=>$store['id'],
                            'quantity'=>$sub_quantity,
                            'measurement_unit_id'=>$measurement_sub_unit_id,
                            'product_id'=> $product_id,
                        ]);
                        $store->update([
                            'sub_quantity_order' => 0
                        ]);
                        $sub_quantity = 0 ;
                    }else{
                        OrderStoreProduct::create([
                            'order_details_id'=> $order_details_id,
                            'store_product_id'=> $store['id'],
                            'quantity'=> $store->sub_quantity_order,
                            'measurement_unit_id'=> $measurement_sub_unit_id,
                            'product_id'=> $product_id,
                        ]);
                        $sub_quantity = $sub_quantity - $store->main_quantity ;
                        $store->update([
                            'sub_quantity_order' => 0
                        ]);
                    }
                }
            }
        }
    }

}
