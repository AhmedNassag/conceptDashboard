<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\OfferDiscount;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\OrderDiscount;
use App\Models\OrderStoreProduct;
use App\Models\Product;
use App\Models\ProductPricing;
use App\Models\Setting;
use App\Models\StoreProduct;
use App\Models\Tax;
use App\Models\User;
use App\Notifications\Admin\AddNotification;
use App\Traits\Message;
use App\Traits\StoreTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use Message;
    use StoreTrait;
    public $selling_method ;
    public $order_amount;

    public function __construct(){
        if(auth()->user()->type = 'client')
        {
            $this->selling_method = auth()->user()->client->selling_method_id;
            $this->order_amount = auth()->user()->client->sellingMethod->order_amount;
        }
        else
        {
            $this->selling_method = 2;
            $this->order_amount = $this->selling_method->order_amount;
        }
    }



    public function order(Request $request)
    {
        // Validator request
        $v = Validator::make($request->all(), [
            'code' => 'nullable|exists:discounts,code',
            'is_shipping' => 'required|boolean',
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.product_price_id' => 'required|integer|exists:product_pricings,id',
            'products.*.quantity' => 'required|numeric|gte:1',
        ]);

        if ($v->fails()) {
            return $this->sendError(trans('message.messageError'), $v->errors());
        }

        $errors = [];
        $totalBeforeDiscount = 0;
        $discount = 0;
        $tax_amount = 0;
        $store_id = $this->store();

        foreach ($request->products as $index => $item) {
            $product_pricing = ProductPricing::where([
                ['id', $item['product_price_id']],
                ['product_id', $item['product_id']],
                ['selling_method_id', $this->selling_method],
            ])->first();

            if ($item['quantity'] > $product_pricing->max_quantity) {
                $errors['products.' . $index . '.quantity'][] = " الكمية يجب ان تكون اقل من او يساوى " . $product_pricing->max_quantity;
                return $this->sendError(trans('message.messageError'), $errors);
            }

            if ($item['quantity'] < $product_pricing->less_quantity) {
                $errors['products.' . $index . '.quantity'][] = " الكمية يجب ان تكون اكبر من او يساوى " . $product_pricing->less_quantity;
                return $this->sendError(trans('message.messageError'), $errors);
            }

            if ($item['quantity'] > $product_pricing->available_quantity) {
                $errors['products.' . $index . '.quantity'][] = "لا يوجد كمية متاحة فى المخزن";
                return $this->sendError(trans('message.messageError'), $errors);
            }

            $totalBeforeDiscount += $product_pricing['price'] * $item['quantity'];
        }

        if ($this->order_amount > $totalBeforeDiscount){
            $errors['order_amount'][] = "اجمالى سعر الشراء يجب ان يكون اكثر من " . $this->order_amount;
            return $this->sendError(trans('message.messageError'), $errors);
        }

        if ($request->code){
            $coupon=new CouponController();
            $coupon_data = $coupon->checkCoupon($request);

            if ($coupon_data->getData()->success == false){
                return $coupon_data;
            }

            $offer_id = $coupon_data->getData()->data->coupon->id;
            $offer = Discount::find($offer_id);
            $discount += $offer->discount($totalBeforeDiscount);
        }

        $totalAfterDiscount = $totalBeforeDiscount - $discount;
        $tax_app = new TaxController();
        $taxes = $tax_app->getTaxes();
        $taxes_arr = $taxes->getData()->data->taxes;

        foreach ($taxes_arr as $tax) {
            $tax_amount += ($totalAfterDiscount * $tax->percentage) / 100;
        }

        $totalAfterTax = $totalAfterDiscount + $tax_amount;
        $shipping_price = 0;

        if ($request->is_shipping){
            $shipping_price = auth()->user()->client->area->shipping_price;
        }

        $totalAfterShipping = $totalAfterTax + $shipping_price;
        $order = Order::create([
            'user_id' => auth()->id(),
            'store_id' => $store_id,
            'discount' => $discount,
            'sub_total' => $totalBeforeDiscount,
            'tax' => $tax_amount,
            'shippingPrice' => $shipping_price,
            'total' => $totalAfterShipping,
            'is_online' => 1,
            'is_shipping' => $request->is_shipping
        ]);

        if (count($taxes_arr) > 0) {

            foreach ($taxes_arr as $ta) {
                $order->orderTax()->create([
                    'tax_id' => $ta->id,
                    'name' => $ta->name,
                    'percentage' => $ta->percentage,
                ]);
            }

        }

        if ($request->code) {
            $coupon= new CouponController();
            $coupon_data = $coupon->checkCoupon($request);

            if ($coupon_data->getData()->success == false){
                return $coupon_data;
            }

            $offer_id = $coupon_data->getData()->data->coupon->id;
            $d = Discount::find($offer_id);
            $d->update([
               'used_times' => $d->used_times + 1
            ]);
            $order->orderDiscount()->create([
                'discount_id' => $d['id'],
                'code' => $d['code'],
                'value' => $d['value'],
                'type' => $d['type']
            ]);
        }

        foreach ($request->products as $product) {
            $product_pricing = ProductPricing::where([
                ['id', $product['product_price_id']],
                ['product_id', $product['product_id']],
                ['selling_method_id', $this->selling_method],
            ])->first();
            $order_details = OrderDetails::where([
                ['order_id',$order['id']],
                ['selling_method_id', $this->selling_method],
                ['product_id', $product['product_id']],
            ])->first();
            $order_details_id = null;

            if ($order_details){

                if ($order_details->main_measurement_unit_id == $product_pricing['measurement_unit_id']){
                    $order_details->update([
                        'quantity' => $product['quantity'],
                        'price' => $product_pricing['price'],
                    ]);
                    $order_details_id = $order_details->id;
                }else{
                    $order_details->update([
                        'sub_quantity' => $product['quantity'],
                        'sub_price' => $product_pricing['price'],
                    ]);
                    $order_details_id = $order_details->id;
                }

            }else{

                if ($product_pricing['measurement_unit_id'] == $product_pricing->product->main_measurement_unit_id){
                    $order_details = OrderDetails::create([
                        'order_id' =>$order['id'],
                        'quantity' => $product['quantity'],
                        'main_measurement_unit_id' => $product_pricing->product->main_measurement_unit_id,
                        'sub_measurement_unit_id' => $product_pricing->product->sub_measurement_unit_id,
                        'selling_method_id' => $this->selling_method,
                        'product_id' => $product['product_id'],
                        'price' => $product_pricing['price'],
                    ]);
                    $order_details_id = $order_details->id;
                }else{
                    $order_details = OrderDetails::create([
                        'order_id' =>$order['id'],
                        'sub_quantity' => $product['quantity'],
                        'main_measurement_unit_id' => $product_pricing->product->main_measurement_unit_id,
                        'sub_measurement_unit_id' => $product_pricing->product->sub_measurement_unit_id,
                        'selling_method_id' => $this->selling_method,
                        'product_id' => $product['product_id'],
                        'sub_price' => $product_pricing['price'],
                    ]);
                    $order_details_id = $order_details->id;
                }

            }

            $this->storeProductData($store_id,$product['product_id'],$product_pricing['measurement_unit_id'],$product['quantity'],$order_details_id);
        }

        $id = $order->id;
        $message = " يوجد طلب جديد من العميل " .auth()->user()->name;
        $image = auth()->user()->image_path;
        $this->sendNotification($id,$message,$image);

        return $this->sendResponse(['order'=>$order], trans('message.messageSuccessfully'));

    }



    public function storeProductData($store_id,$product_id,$measurement_unit_id,$quantity,$order_details_id)
    {
        $store_main_measurement = StoreProduct::where([
            ['store_id',$store_id],
            ['product_id',$product_id],
            ['main_measurement_unit_id',$measurement_unit_id],
        ])->get();

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
                    $quantity = 0 ;
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

        $store_sub_measurement = StoreProduct::where([
            ['store_id',$store_id],
            ['product_id',$product_id],
            ['sub_quantity_order','>',0],
            ['sub_measurement_unit_id',$measurement_unit_id],
        ])->get();

        foreach ($store_sub_measurement as $store){
            if ($store->sub_quantity_order > 0 && $quantity > 0){
                if ($store->sub_quantity_order >= $quantity){
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
                    $quantity = 0 ;
                }else{
                    OrderStoreProduct::create([
                        'order_details_id'=>$order_details_id,
                        'store_product_id'=>$store['id'],
                        'quantity'=> $store->sub_quantity_order,
                        'measurement_unit_id'=>$measurement_unit_id,
                        'product_id'=>$product_id,
                    ]);
                    $quantity = $quantity - $store->main_quantity ;
                    $store->update([
                        'sub_quantity_order' => 0
                    ]);
                }
            }
        }
    }



    public function sendNotification($id,$message,$image)
    {
        User::whereAuthId(1)
        ->whereRelation('roles.notify','name','Add OnlineOrder')
        ->each(function ($admin) use($id,$message,$image){
            $admin->notify(new AddNotification('',$id,'showOrderOnline',$message,$image));
        });
    }



    public function trackingOrder()
    {
        $orders = Order::where([
            ['order_status_id','!=',6],
            ['user_id',auth()->id()],
        ])->where([
            ['is_online',1],
            ['order_status_id','!=',7],
        ])->where([
            ['is_online',1],
            ['order_status_id','!=',8],
        ])->with(['orderStatus','orderOtherOffer','orderOffer','orderTax','orderDetails' => function ($q) {
            $q->with(['sellingMethod:id,name',
                'mainMeasurementUnit:id,name',
                'subMeasurementUnit:id,name',
                'product'
            ]);
        }])->latest()->paginate(15);
        return $this->sendResponse(['orders' => $orders], trans('message.messageSuccessfully'));
    }



    public function pendingOrders()
    {
        $orders = Order::where([
            ['order_status_id',8],
            ['user_id',auth()->id()],
        ])->with(['orderStatus','orderOtherOffer','orderOffer','orderTax','orderDetails' => function ($q) {
            $q->with(['sellingMethod:id,name',
                'mainMeasurementUnit:id,name',
                'subMeasurementUnit:id,name',
                'product:id,name'
            ]);
        }])->latest()->paginate(15);
        return $this->sendResponse(['orders' => $orders], trans('message.messageSuccessfully'));
    }



    public function clientsOrder($id)
    {
        $orders = OrderDetails::with('order')
        ->where([
            // ['order.order_status_id', 5],
            ['product_id', $id],
        ])
        // ->orwhere([
        //     // ['order.order_status_id', 7],
        //     ['product_id', $id],
        // ])
        ->count();
        return $this->sendResponse(['orders' => $orders], trans('message.messageSuccessfully'));
    }



    public function companyOrder(Request  $request)
    {
        // Validator request
        $v = Validator::make($request->all(), [
            'code' => 'nullable|exists:discounts,code',
            'is_shipping' => 'required|boolean',
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.product_price_id' => 'required|integer|exists:product_pricings,id',
            'products.*.quantity' => 'required|numeric|gte:1',
        ]);

        if ($v->fails()) {
            return $this->sendError(trans('message.messageError'), $v->errors());
        }

        $errors = [];
        $totalBeforeDiscount = 0;
        $discount = 0;
        $tax_amount = 0;
        $store_id = $this->store();

        foreach ($request->products as $index => $item) {
            $product_pricing = ProductPricing::where([
                ['id', $item['product_price_id']],
                ['product_id', $item['product_id']],
                ['selling_method_id', 2],
            ])->first();

            if ($item['quantity'] > $product_pricing->max_quantity) {
                $errors['products.' . $index . '.quantity'][] = " الكمية يجب ان تكون اقل من او يساوى " . $product_pricing->max_quantity;
                return $this->sendError(trans('message.messageError'), $errors);
            }

            if ($item['quantity'] < $product_pricing->less_quantity) {
                $errors['products.' . $index . '.quantity'][] = " الكمية يجب ان تكون اكبر من او يساوى " . $product_pricing->less_quantity;
                return $this->sendError(trans('message.messageError'), $errors);
            }

            if ($item['quantity'] > $product_pricing->available_quantity) {
                $errors['products.' . $index . '.quantity'][] = "لا يوجد كمية متاحة فى المخزن";
                return $this->sendError(trans('message.messageError'), $errors);
            }

            $totalBeforeDiscount += $product_pricing['price'] * $item['quantity'];
        }

        if ($this->order_amount > $totalBeforeDiscount){
            $errors['order_amount'][] = "اجمالى سعر الشراء يجب ان يكون اكثر من " . $this->order_amount;
            return $this->sendError(trans('message.messageError'), $errors);
        }

        if ($request->code){
            $coupon=new CouponController();
            $coupon_data = $coupon->checkCoupon($request);

            if ($coupon_data->getData()->success == false){
                return $coupon_data;
            }

            $offer_id = $coupon_data->getData()->data->coupon->id;
            $offer = Discount::find($offer_id);
            $discount += $offer->discount($totalBeforeDiscount);
        }

        $totalAfterDiscount = $totalBeforeDiscount - $discount;
        $tax_app = new TaxController();
        $taxes = $tax_app->getTaxes();
        $taxes_arr = $taxes->getData()->data->taxes;

        foreach ($taxes_arr as $tax) {
            $tax_amount += ($totalAfterDiscount * $tax->percentage) / 100;
        }

        $totalAfterTax = $totalAfterDiscount + $tax_amount;
        $shipping_price = 0;

        if ($request->is_shipping){
            $shipping_price = auth()->user()->client->area->shipping_price;
        }

        $totalAfterShipping = $totalAfterTax + $shipping_price;
        $order = Order::create([
            'user_id' => auth()->id(),
            'store_id' => $store_id,
            'discount' => $discount,
            'sub_total' => $totalBeforeDiscount,
            'tax' => $tax_amount,
            'shippingPrice' => $shipping_price,
            'total' => $totalAfterShipping,
            'is_online' => 1,
            'is_shipping' => $request->is_shipping
        ]);

        if (count($taxes_arr) > 0) {

            foreach ($taxes_arr as $ta) {
                $order->orderTax()->create([
                    'tax_id' => $ta->id,
                    'name' => $ta->name,
                    'percentage' => $ta->percentage,
                ]);
            }

        }

        if ($request->code) {
            $coupon= new CouponController();
            $coupon_data = $coupon->checkCoupon($request);

            if ($coupon_data->getData()->success == false){
                return $coupon_data;
            }

            $offer_id = $coupon_data->getData()->data->coupon->id;
            $d = Discount::find($offer_id);
            $d->update([
                'used_times' => $d->used_times + 1
            ]);
            $order->orderDiscount()->create([
                'discount_id' => $d['id'],
                'code' => $d['code'],
                'value' => $d['value'],
                'type' => $d['type']
            ]);
        }

        foreach ($request->products as $product) {
            $product_pricing = ProductPricing::where([
                ['id', $product['product_price_id']],
                ['product_id', $product['product_id']],
                ['selling_method_id', 2],
            ])->first();
            $order_details = OrderDetails::where([
                ['order_id',$order['id']],
                ['selling_method_id', 2],
                ['product_id', $product['product_id']],
            ])->first();
            $order_details_id = null;

            if ($order_details){

                if ($order_details->main_measurement_unit_id == $product_pricing['measurement_unit_id']){
                    $order_details->update([
                        'quantity' => $product['quantity'],
                        'price' => $product_pricing['price'],
                    ]);
                    $order_details_id = $order_details->id;
                }else{
                    $order_details->update([
                        'sub_quantity' => $product['quantity'],
                        'sub_price' => $product_pricing['price'],
                    ]);
                    $order_details_id = $order_details->id;
                }

            }else{

                if ($product_pricing['measurement_unit_id'] == $product_pricing->product->main_measurement_unit_id){
                    $order_details = OrderDetails::create([
                        'order_id' =>$order['id'],
                        'quantity' => $product['quantity'],
                        'main_measurement_unit_id' => $product_pricing->product->main_measurement_unit_id,
                        'sub_measurement_unit_id' => $product_pricing->product->sub_measurement_unit_id,
                        'selling_method_id' => $this->selling_method,
                        'product_id' => $product['product_id'],
                        'price' => $product_pricing['price'],
                    ]);
                    $order_details_id = $order_details->id;
                }else{
                    $order_details = OrderDetails::create([
                        'order_id' =>$order['id'],
                        'sub_quantity' => $product['quantity'],
                        'main_measurement_unit_id' => $product_pricing->product->main_measurement_unit_id,
                        'sub_measurement_unit_id' => $product_pricing->product->sub_measurement_unit_id,
                        'selling_method_id' => $this->selling_method,
                        'product_id' => $product['product_id'],
                        'sub_price' => $product_pricing['price'],
                    ]);
                    $order_details_id = $order_details->id;
                }

            }

            $this->storeProductData($store_id,$product['product_id'],$product_pricing['measurement_unit_id'],$product['quantity'],$order_details_id);
        }

        $id = $order->id;
        $message = " يوجد طلب جديد من الشركة " .auth()->user()->name;
        $image = auth()->user()->image_path;
        $this->sendNotification($id,$message,$image);

        return $this->sendResponse(['order'=>$order], trans('message.messageSuccessfully'));

    }
}
