<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Product;
use App\Traits\Message;
use App\Traits\NotificationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NotificationAppController extends Controller
{
    use Message;
    use NotificationTrait;

    public function store(Request $request){

        // Validator request
        $v = Validator::make($request->all(), [
            'title' => 'required',
            'notification' => 'required',
            'product_id' => 'nullable|integer|exists:products,id',
        ]);

        if ($v->fails()) {
            return $this->sendError('There is an error in the data', $v->errors());
        }
        $product = Product::find($request->product_id);
        $tokens = Client::whereNotNull('firebase_token')->get('firebase_token')->pluck('firebase_token');
        $title = $request->title;
        $body = $request->notification;
        $type = "send notification";
        $productData = $product;

        $this->notificationAllClient($tokens,$body,$title,$type,$productData);

        return $this->sendResponse([], 'Data exited successfully');
    }
}
