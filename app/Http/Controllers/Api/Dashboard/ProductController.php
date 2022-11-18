<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\FilterWax;
use App\Models\MeasurementUnit;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductPricing;
use App\Models\PurchaseProduct;
use App\Models\SellingMethod;
use App\Models\SparePart;
use App\Models\Store;
use App\Models\StoreProduct;
use App\Models\Tax;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::
        with('company:id,name', 'category:id,name', 'tax:id,name')
            ->when($request->search, function ($q) use ($request) {
                return $q->where('name', 'like', "%" . $request->search . "%")
                    ->orWhereRelation('company', 'name', 'like', '%' . $request->search . '%')
                    ->orWhereRelation('category', 'name', 'like', '%' . $request->search . '%');
            })->paginate(15);

        return $this->sendResponse(['products' => $products], 'Data exited successfully');
    }

    /**
     * get Purchase Invoice Product
     */
    public function purchaseInvoiceProduct(Request $request)
    {
        $products = Product::where([
            ['status', 1],
            ['category_id', $request->category_id],
            ['sub_category_id', $request->sub_category_id]
        ])->with('mainMeasurementUnit', 'subMeasurementUnit', 'company')->get();

        return $this->sendResponse(['products' => $products], 'Data exited successfully');
    }

    /**
     * get active Product
     */
    public function activeProduct()
    {
        $products = Product::where('status', 1)->get();
        return $this->sendResponse(['products' => $products], 'Data exited successfully');
    }

    /**
     * get mobile Product
     */
    public function mobileProduct()
    {
        $products = Product::where([
            ['status', 1],
            ['sell_app', 1],
        ])->whereHas('productPrice', function ($q) {
            $q->where('active', 1);
        })->whereHas('storeProducts', function ($q) {
            $q->where('sub_quantity_order', '>=', 1);
        })->with(['media', 'productPrice' => function ($q) {
            $q->where('active', 1);
            $q->with('sellingMethod', 'measurementUnit');
        }])->get();

        return $this->sendResponse(['products' => $products], 'Data exited successfully');
    }

    public function activationProduct($id)
    {
        $product = Product::find($id);

        if ($product->status == 1) {
            $product->update([
                "status" => 0
            ]);
        } else {
            $product->update([
                "status" => 1
            ]);
        }
        return $this->sendResponse([], 'Data exited successfully');
    }

    public function create()
    {
        try {

            $companies = Company::select('id', 'name')->get();
            $categories = Category::where('parent_id', 0)->select('id', 'name')->get();
            $measures = MeasurementUnit::select('id', 'name')->get();
            $sellingMethods = SellingMethod::select('id', 'name')->get();
            $stores = Store::where('status',1 )->get();
            $filterWaxes = FilterWax::get();
            $spareParts = SparePart::get();

            return $this->sendResponse([
                'companies' => $companies,
                'categories' => $categories,
                'measures' => $measures,
                'sellingMethods' => $sellingMethods,
                'stores' => $stores,
                "filterWaxes" => $filterWaxes,
                "spareParts" => $spareParts
            ], 'Data exited successfully');

        } catch (\Exception $e) {

            return $this->sendError('An error occurred in the system');

        }
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
                'name' => 'required|unique:products',
                'barcode' => 'nullable|numeric|unique:products,barcode',
                'Re_order_limit' => 'required|numeric',
                'maximum_product' => 'required|numeric',
                'description' => 'nullable',
                'image' => 'required|file|mimes:png,jpg,jpeg',
                'files' => 'required|array',
                'files.*' => 'required|file|mimes:png,jpg,jpeg',
                'category_id' => 'required|integer|exists:categories,id',
                'sub_category_id' => 'required|integer|exists:categories,id',
                'company_id' => 'required|integer|exists:companies,id',
                'main_measurement_unit_id' => 'required|integer|exists:measurement_units,id',
                'sub_measurement_unit_id' => 'required|integer|exists:measurement_units,id',
                'count_unit' => 'required|numeric',
                'selling_method' => 'required',
                'sell_app' => 'required',
                'selling_method.*' => 'required|exists:selling_methods,id',
                'quantity' => 'required|numeric',
                'sub_quantity' => 'required|numeric',
                'price' => 'required|numeric',
                'sub_price' => 'required|numeric',
                'store_id' => 'required|integer|exists:stores,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $image = time() . '.' . $request->image->getClientOriginalName();

            // picture move
            $request->image->storeAs('product', $image, 'general');

            $product = Product::create([
                'name' => $request->name,
                'barcode' => $request->barcode,
                'description' => $request->description,
                'Re_order_limit' => $request->Re_order_limit,
                'maximum_product' => $request->maximum_product,
                'image' => $image,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'company_id' => $request->company_id,
                'main_measurement_unit_id' => $request->main_measurement_unit_id,
                'sub_measurement_unit_id' => $request->sub_measurement_unit_id,
                'count_unit' => $request->count_unit,
                'sell_app' => $request->sell_app,
            ]);

            $imageProduct = explode(',', $request->selling_method);

            $product->selling_method()->attach($imageProduct);
            foreach ($imageProduct as $item) {

                ProductPricing::create([
                    'product_id' => $product->id,
                    'selling_method_id' => $item,
                    'measurement_unit_id' => $request->main_measurement_unit_id
                ]);
                ProductPricing::create([
                    'product_id' => $product->id,
                    'selling_method_id' => $item,
                    'measurement_unit_id' => $request->sub_measurement_unit_id
                ]);
            }

            $i = 0;
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $index => $file) {

                    $file_size = $file->getSize();
                    $file_type = $file->getMimeType();
                    $image = time() . $i . '.' . $file->getClientOriginalName();

                    // picture move
                    $file->storeAs('product', $image, 'general');

                    $product->media()->create([
                        'file_name' => asset('upload/product/' . $image),
                        'file_size' => $file_size,
                        'file_type' => $file_type,
                        'file_sort' => $i
                    ]);

                    $i++;
                }
            }

            StoreProduct::create([
                'product_id'=>$product->id,
                'main_measurement_unit_id'=>$request->main_measurement_unit_id,
                'sub_measurement_unit_id'=>$request->sub_measurement_unit_id,
                'sub_category_id'=> $request->sub_category_id,
                'store_id'=>$request['store_id'],
                'quantity'=>$request['quantity'],
                'sub_quantity'=>$request['sub_quantity'],
                'count_unit'=>$request['count_unit'],
                'sub_quantity_order'=>0,
            ]);

            PurchaseProduct::create([
                'product_id' => $product->id,
                'quantity'=>$request['quantity'],
                'sub_quantity'=>$request['sub_quantity'],
                'price' => $request['price'],
                'count_unit' => $request['count_unit'],
            ]);

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {

            $product = Product::with('media:mediable_id,file_name,id')->find($id);
            $companies = Company::select('id', 'name')->get();
            $categories = Category::where('parent_id', 0)->select('id', 'name')->get();
            $measures = MeasurementUnit::select('id', 'name')->get();
            $sellingMethods = SellingMethod::select('id', 'name')->get();
            $filterWaxes = FilterWax::get();
            $spareParts = SparePart::get();
            $sellingMethodChange = $product->selling_method;
            $stores = Store::where('status',1 )->get();
            $storeProduct = $product->storeProducts()->first();
            $purchaseProducts = $product->purchaseProducts()->first();

            return $this->sendResponse([
                'product' => $product,
                'companies' => $companies,
                'categories' => $categories,
                'measures' => $measures,
                'sellingMethods' => $sellingMethods,
                'sellingMethodChange' => $sellingMethodChange,
                'stores' => $stores,
                'storeProduct' => $storeProduct,
                'purchaseProducts' => $purchaseProducts,
                "filterWaxes" => $filterWaxes,
                "spareParts" => $spareParts
            ], 'Data exited successfully');

        } catch (\Exception $e) {

            return $this->sendError('An error occurred in the system');

        }
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
        DB::beginTransaction();
        try {

            $product = Product::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|unique:products,name,' . $product->id,
                'Re_order_limit' => 'required|numeric',
                'maximum_product' => 'required|numeric',
                'barcode' => 'nullable|unique:products,barcode,' . $product->id,
                'description' => 'nullable',
                'image' => 'nullable' . ($request->hasFile('image') ? '|file|mimes:jpeg,jpg,png' : ''),
                'files' => 'nullable',
                'files.*' => 'nullable' . ($request->hasFile('files') ? '|file|mimes:jpeg,jpg,png' : ''),
                'category_id' => 'required|integer|exists:categories,id',
                'sub_category_id' => 'required|integer|exists:categories,id',
                'company_id' => 'required|integer|exists:companies,id',
                'main_measurement_unit_id' => 'required|integer|exists:measurement_units,id',
                'sub_measurement_unit_id' => 'required|integer|exists:measurement_units,id',
                'count_unit' => 'required|numeric',
                'selling_method' => 'required',
                'sell_app' => 'required',
                'selling_method.*' => 'required|exists:selling_methods,id',

                'quantity' => 'required|numeric',
                'sub_quantity' => 'required|numeric',
                'price' => 'required|numeric',
                'sub_price' => 'required|numeric',
                'store_id' => 'required|integer|exists:stores,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data['name'] = $request->name;
            $data['Re_order_limit'] = $request->Re_order_limit;
            $data['maximum_product'] = $request->maximum_product;
            $data['barcode'] = $request->barcode != "null" ? $request->barcode : $product->barcode;
            $data['description'] = $request->description;
            if ($request->hasFile('image')) {
                if (File::exists('upload/product/' . $product->image)) {
                    unlink('upload/product/' . $product->image);
                }
                $image = time() . '.' . $request->image->getClientOriginalName();
                $request->image->storeAs('product', $image, 'general');
                $data['image'] = $image;
            }
            $data['category_id'] = $request->category_id;
            $data['sub_category_id'] = $request->sub_category_id;
            $data['company_id'] = $request->company_id;
            $data['main_measurement_unit_id'] = $request->main_measurement_unit_id;
            $data['sub_measurement_unit_id'] = $request->sub_measurement_unit_id;
            $data['count_unit'] = $request->count_unit;
            $data['sell_app'] = $request->sell_app;


            $product->update($data);

            $imageProduct = explode(',', $request->selling_method);

            $product->selling_method()->sync($imageProduct);

            foreach ($imageProduct as $item) {

                $ProductPricing = ProductPricing::where([
                    ['product_id', $id],
                    ['selling_method_id', $item],
                ])->get();
                if (count($ProductPricing) == 0) {
                    ProductPricing::create([
                        'product_id' => $id,
                        'selling_method_id' => $item,
                        'measurement_unit_id' => $request->main_measurement_unit_id
                    ]);
                    ProductPricing::create([
                        'product_id' => $id,
                        'selling_method_id' => $item,
                        'measurement_unit_id' => $request->sub_measurement_unit_id
                    ]);
                } else {
                    foreach ($ProductPricing as $index=>$value){
                        if ($index == 0){
                            $value->update([
                                'measurement_unit_id' => $request->main_measurement_unit_id
                            ]);
                        }else{
                            $value->update([
                                'measurement_unit_id' => $request->sub_measurement_unit_id
                            ]);
                        }

                    }
                }
            }

            $i = 0;
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $index => $file) {

                    $file_size = $file->getSize();
                    $file_type = $file->getMimeType();
                    $image = time() . $i . '.' . $file->getClientOriginalName();

                    // picture move
                    $file->storeAs('product', $image, 'general');

                    $product->media()->create([
                        'file_name' => asset('upload/product/' . $image),
                        'file_size' => $file_size,
                        'file_type' => $file_type,
                        'file_sort' => $i
                    ]);

                    $i++;
                }
            }

            $store_product = StoreProduct::where('product_id',$id)->first();
            $store_product->update([
                'main_measurement_unit_id'=>$request->main_measurement_unit_id,
                'sub_measurement_unit_id'=>$request->sub_measurement_unit_id,
                'sub_category_id'=> $request->sub_category_id,
                'store_id'=>$request['store_id'],
                'quantity'=>$request['quantity'],
                'sub_quantity'=>$request['sub_quantity'],
                'count_unit'=>$request['count_unit'],
                'sub_quantity_order'=>0,
            ]);

            $purchase_product = PurchaseProduct::where('product_id',$id)->first();
            $purchase_product->update([
                'quantity'=>$request['quantity'],
                'sub_quantity'=>$request['sub_quantity'],
                'price' => $request['price'],
                'count_unit' => $request['count_unit'],
            ]);

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');
        } catch (\Exception $e) {

            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }


    public function deleteOne(Request $request, $id)
    {
        try {
            $product = Product::find($id);
            if ($product) {

                $media = Media::find($request->idMedia);

                if (File::exists('upload/product/' . $media->file_name)) {
                    unlink('upload/product/' . $media->file_name);
                }

                $media->delete();
                return $this->sendResponse([], 'Deleted successfully');
            } else {
                return $this->sendError('ID is not exist');
            }

        } catch (\Exception $e) {
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
        try {
            $Product = Product::find($id);
            if ($Product) {

                if (File::exists('upload/product/' . $Product->image)) {
                    unlink('upload/product/' . $Product->image);
                }

                foreach ($Product->media as $item) {
                    if (File::exists('upload/product/' . $item->file_name)) {
                        unlink('upload/product/' . $item->file_name);
                    }
                    $item->delete();
                }

                $Product->delete();
                return $this->sendResponse([], 'Deleted successfully');
            } else {
                return $this->sendError('ID is not exist');
            }

        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
    }

}
