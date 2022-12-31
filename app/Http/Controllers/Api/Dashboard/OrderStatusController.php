<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ClientAccount;
use App\Models\ClientIncome;
use App\Models\followLead;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\OrderRetuen;
use App\Models\OrderStatus;
use App\Models\OrderStoreProduct;
use App\Models\PeriodicMaintenance;
use App\Models\StoreProduct;
use App\Models\User;
use App\Notifications\Admin\AddNotification;
use App\Traits\Message;
use App\Traits\NotificationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class OrderStatusController extends Controller
{
    use Message;
    use NotificationTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $orderStatus = OrderStatus::all();
        return $this->sendResponse(['orderStatus' => $orderStatus], 'Data exited successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // try {
            DB::beginTransaction();
            // Validator request
            $v = Validator::make($request->all(), [
                'order_id' => 'required|integer|exists:orders,id',
                'order_status_id' => 'required|integer|exists:order_statuses,id',
                'representative_id' => 'nullable|integer|exists:users,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
<<<<<<< HEAD
            $order = Order::with('orderDetails.product.maintenance')->find($request->order_id);
=======
            $order = Order::with('orderDetails.product.filterWax')->find($request->order_id);
>>>>>>> 417c5a33e15b99f534eca336330fc5dcb5a6da41

            $order->update([
               'order_status_id' => $request->order_status_id,
               'representative_id' => $request->representative_id ? $request->representative_id : $order->representative_id,
            ]);

            switch ($request->order_status_id){
                case 2 :
                    $tokens = [];
                    $tokens[] = $order->user->client->firebase_token;
                    $body = " تم تأكيد طلبك رقم " . $request->order_id;
                    $type = "order status";
                    $productData = $order;

                    $this->notification($tokens,$body,$type,$productData);
                    break;
                case 3:

                    $tokens = [];
                    $tokens[] = $order->user->client->firebase_token;
                    $body = " الطلب رقم " . $request->order_id . " فى مرحلة التجهيز ";
                    $type = "order status";
                    $productData = $order;

                    $this->notification($tokens,$body,$type,$productData);
                    break;

                case 4:
                    $tokens = [];
                    $tokens[] = $order->user->client->firebase_token;
                    $body = " الطلب رقم " . $request->order_id . " خرج مع المندوب فى الطريق اليك ";
                    $type = "order status";
                    $productData = $order;

                    $this->notification($tokens,$body,$type,$productData);
                    break;

                case 5:

                    $this->deleteOldAccount($order);
                    $this->orderAccount($request->all());

                    $tokens = [];
                    $tokens[] = $order->user->client->firebase_token;
                    $body = " تم استلام الطلب رقم " . $request->order_id . " بنجاح شكرا لك شريك النجاح ";
                    $type = "order status";
                    $productData = $order;

                    // add order to follow after selling
                    $followLead = User::where('id',$order->user_id)->with('client')->first();
                    if($followLead)
                    {
                        followLead::create([
                            'name' => $followLead->name,
                            'address' => $followLead->client->address,
                            'email' => $followLead->email,
                            'phone' => $followLead->phone,
                            'seller_category_id' => 1,
                        ]);
                    }

<<<<<<< HEAD
                    // add order to periodicMaintenance
                    PeriodicMaintenance::create([
                        'order_id' => $order->id,
                        'name' => $order->user->name,
                        'quantity' => $order->orderDetails[0]->quantity,
                        'price' => ($order->orderDetails[0]->product->maintenance->price) * ($order->orderDetails[0]->quantity),
                        'next_maintenance' => Carbon::now()->addDays($order->orderDetails[0]->product->maintenance->period),
                    ]);

=======
                    $filterWax = $order->orderDetails->first()->product->filterWax;
                    $count = $filterWax->count();

                    for($i = 0; $i < $count; $i++)
                    {
                        // add order to periodicMaintenance
                        $periodic = PeriodicMaintenance::create([
                            'order_id' => $order->id,
                            'name' => $order->user->name,
                            'quantity' => $order->orderDetails[0]->quantity,
                            'price' => ($filterWax[$i]->price) * ($order->orderDetails[0]->quantity),
                            'next_maintenance' => Carbon::now()->addDays($filterWax[$i]->period),
                            // 'order_id' => 1,
                            // 'name' => 'name',
                            // 'quantity' => 1,
                            // 'price' => 1,
                            // 'next_maintenance' => date("Y-m-d H:i:s"),
                        ]);
                    }
>>>>>>> 417c5a33e15b99f534eca336330fc5dcb5a6da41

                    $this->notification($tokens,$body,$type,$productData);
                    break;

                case 6:
                    $this->deleteOldAccount($order);
                    $this->orderReturnAllProduct($order);

                    $tokens = [];
                    $tokens[] = $order->user->client->firebase_token;
                    $body = " تم ارجاع الطلب رقم " . $request->order_id ;
                    $type = "order status";
                    $productData = $order;

                    $this->notification($tokens,$body,$type,$productData);
                    break;

                case 7:
                    $this->deleteOldAccount($order);
                    $this->orderReturnSomeProduct($order,$request['products']);
                    $this->orderAccount($request->all());

                    $tokens = [];
                    $tokens[] = $order->user->client->firebase_token;
                    $body = " تم التعديل على الطلب رقم " . $request->order_id ;
                    $type = "order status";
                    $productData = $order;

                    $this->notification($tokens,$body,$type,$productData);
                    break;

                case 8:
                    $tokens = [];
                    $tokens[] = $order->user->client->firebase_token;
                    $body = " الطلب رقم " . $request->order_id . " مؤجل الان ";
                    $type = "order status";
                    $productData = $order;

                    $this->notification($tokens,$body,$type,$productData);
                    break;

                default:
                    $tokens = [];
                    $tokens[] = $order->user->client->firebase_token;
                    $body = " نحن نعمل على تجهيز الطلب رقم " . $request->order_id . " شكرا لك شريك النجاح ";
                    $type = "order status";
                    $productData = $order;

                    $this->notification($tokens,$body,$type,$productData);
            }

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return $this->sendError('An error occurred in the system');
        // }
    }
    //return all product

    public function orderReturnAllProduct($order){
        foreach ($order->orderDetails as $detail){
            foreach ($detail->orderStoreProducts as $orderStoreProduct){
                $store_product_id = $orderStoreProduct->store_product_id;
                $orderStoreProduct->delete();
                $store_product = StoreProduct::find($store_product_id);
                $store_product->update([
                    'sub_quantity_order' => 0
                ]);
            }
            OrderRetuen::create([
                'order_id' =>$detail['order_id'],
                'quantity' => $detail['quantity'],
                'sub_quantity' => $detail['sub_quantity'],
                'main_measurement_unit_id' => $detail['main_measurement_unit_id'],
                'sub_measurement_unit_id' => $detail['sub_measurement_unit_id'],
                'selling_method_id' => $detail['selling_method_id'],
                'product_id' => $detail['product_id'],
                'price' => $detail['price'],
                'sub_price' => $detail['sub_price'],
                'order_details_id' => $detail['id'],
            ]);
        }
    }

    //return some product

    public function orderReturnSomeProduct($order,$products){
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

        $sub_total = 0;
        $tax = $order['tax'];
        $discount = $order['discount'];
        $shippingPrice = $order['shippingPrice'];
        $other_discount = $order->orderOtherOffer ? $order->orderOtherOffer->offer : 0;
        $total = 0;

        foreach ($products as $product){
            $order_details = OrderDetails::find($product['order_details_id']);
            $order_details->update([
                'quantity'=>$product['quantity'],
                'sub_quantity'=>$product['sub_quantity'],
            ]);
            $sub_total += ($product['quantity'] * $order_details['price']) + ($product['sub_quantity'] * $order_details['sub_price']) ;
            OrderRetuen::create([
                'order_id' =>$detail['order_id'],
                'quantity' => $product['return_quantity'],
                'sub_quantity' => $product['return_sub_quantity'],
                'main_measurement_unit_id' => $detail['main_measurement_unit_id'],
                'sub_measurement_unit_id' => $detail['sub_measurement_unit_id'],
                'selling_method_id' => $detail['selling_method_id'],
                'product_id' => $detail['product_id'],
                'price' => $detail['price'],
                'sub_price' => $detail['sub_price'],
                'order_details_id' => $detail['id'],
            ]);

            $this->storeProductData($order['store_id'],$order_details['product_id'],$order_details['main_measurement_unit_id'],$order_details['sub_measurement_unit_id'],$product['quantity'],$product['sub_quantity'],$product['order_details_id']);
        }
        $total_after_discount = $sub_total - $discount - $other_discount ;
        $total = $total_after_discount + $tax + $shippingPrice ;

        $order->update([
            'sub_total' => $sub_total,
            'total' => $total,
        ]);
    }

    //update product in store
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

    //client accounts
    public function orderAccount($request){

        if ($request['type_invoice'] == 0){
            $this->clientAccount($request);
        }elseif ($request['type_invoice'] == 1){
            $this->treasuryAccount($request);
        }else{
            $this->treasuryAndClientAccount($request);
        }

        return $this->sendResponse([],'Data exited successfully');
    }

    public function treasuryAccount($request){
        ClientIncome::create([
            'client_id' => $request['sender_id'],
            'income_id' => 3,
            'treasury_id' => $request['treasury_id'],
            'order_id' => $request['order_id'],
            'amount' => $request['treasury_amount'],
            'payment_date' => now(),
            'user_id' => auth()->id(),
        ]);
    }

    public function clientAccount($request){

        ClientAccount::create([
            'user_id' => $request['sender_id'],
            'order_id' => $request['order_id'],
            'amount' => -$request['sender_amount'],
        ]);
    }

    public function treasuryAndClientAccount($request){
        $clientIncome =  ClientIncome::create([
            'client_id' => $request['sender_id'],
            'income_id' => 3,
            'treasury_id' => $request['treasury_id'],
            'order_id' => $request['order_id'],
            'amount' => $request['treasury_amount'],
            'payment_date' => now(),
            'user_id' => auth()->id(),
        ]);

        ClientAccount::create([
            'user_id' => $request['sender_id'],
            'order_id' => $request['order_id'],
            'amount' => -$request['sender_amount'],
            'client_income_id' => $clientIncome['id']
        ]);
    }

    // delete old Account

    public function deleteOldAccount($order){

        foreach ($order->clientAccounts as $clientAccount){
            $clientAccount->delete();
        }
        foreach ($order->clientIncomes as $clientIncome){
            $clientIncome->delete();
        }
    }

}
