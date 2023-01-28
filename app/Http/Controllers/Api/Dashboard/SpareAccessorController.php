<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\ProductPricing;
use App\Models\SellingMethod;
use App\Models\SpareAccessor;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SpareAccessorController extends Controller
{

    use Message;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $spareAccessors = SpareAccessor::with('media:file_name,mediable_id')
        ->when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%');
        })
        ->latest()->paginate(10);
        return $this->sendResponse(['spareAccessors' => $spareAccessors], 'Data exited successfully');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            DB::beginTransaction();
            // Validator request
            $v = Validator::make($request->all(), [
                'name' => ['required','string'],
                'description' => ['required','string'],
                'price' => ['required'],
                //
                'barcode' => 'nullable|numeric|unique:products,barcode',
                'count_unit' => 'required|numeric',
                'maximum_product' => 'required|numeric',
                'Re_order_limit' => 'required|numeric',
                'main_measurement_unit_id' => 'required|integer|exists:measurement_units,id',
                'sub_measurement_unit_id' => 'required|integer|exists:measurement_units,id',
                'sell_app' => 'required',
                'selling_method' => 'required',
                'selling_method.*' => 'required|exists:selling_methods,id',
//                'shipping' => 'required',
//                'guarantee' => 'required',
                'description' => 'nullable',
                'image' => 'required|file|mimes:png,jpg,jpeg',
                'files' => 'required|array',
                'files.*' => 'required|file|mimes:png,jpg,jpeg',
                //
            ]);
            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name','description','price']);
            $spareAccessor = SpareAccessor::create($data);
            //
            $image = time() . '.' . $request->image->getClientOriginalName();
            // picture move
            $request->image->storeAs('product', $image, 'general');
            $product = Product::create([
                'name' => $request->name,
                'barcode' => $request->barcode,
                'description' => $request->description ? $request->description : '',
                'Re_order_limit' => $request->Re_order_limit,
                'maximum_product' => $request->maximum_product,
                'image' => $image,
                'category_id' => 4,
                'main_measurement_unit_id' => $request->main_measurement_unit_id,
                'sub_measurement_unit_id' => $request->sub_measurement_unit_id,
                'count_unit' => $request->count_unit,
                'sell_app' => $request->sell_app,
//                'shipping' => $request->shipping,
                'guarantee' => $request->guarantee,
                'accessor_id' =>  $spareAccessor->id,
            ]);
            $imageProduct = explode(',', $request->selling_method);
            $product->selling_method()->attach($imageProduct);
            foreach ($imageProduct as $item) {
                ProductPricing::create([
                    'product_id' => $product->id,
                    'selling_method_id' => $item,
                    'measurement_unit_id' => $request->main_measurement_unit_id
                ]);
                if ($request['count_unit'] > 1) {
                    ProductPricing::create([
                        'product_id' => $product->id,
                        'selling_method_id' => $item,
                        'measurement_unit_id' => $request->sub_measurement_unit_id
                    ]);
                }
            }
            $i = 0;
            if ($request->hasFile('files'))
            {
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
            //
            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try
        {
            $spareAccessor = SpareAccessor::find($id);
            $product = Product::with(['media:mediable_id,file_name,id'])->where('accessor_id',$id)->first();
            $measures = MeasurementUnit::select('id', 'name')->get();
            $sellingMethods = SellingMethod::select('id', 'name')->get();
            $sellingMethodChange = $product->selling_method;
            return $this->sendResponse([
                'spareAccessor' => $spareAccessor,
                'product' => $product,
                'measures' => $measures,
                'sellingMethods' => $sellingMethods,
                'sellingMethodChange' => $sellingMethodChange,
            ], 'Data exited successfully');
        }
        catch (\Exception $e)
        {
            return $this->sendError('An error occurred in the system');
        }
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
        DB::beginTransaction();
        try
        {
            $spareAccessor = SpareAccessor::find($id);
            $product = Product::with(['media:mediable_id,file_name,id'])->where('accessor_id',$id)->first();
            // Validator request
            $v = Validator::make($request->all(), [
                'name' => ['required','string'],
                'description' => ['required','string'],
                'price' => ['required'],
                // 'file' => 'nullable' . ($request->hasFile('file') ? '|file|mimes:jpeg,jpg,png' : ''),
            ]);
            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name','description','price']);
            $spareAccessor->update($data);
            $product->update([
                'name' => $request->name,
                'Re_order_limit' => $request->Re_order_limit,
                'maximum_product' => $request->maximum_product,
                'description' => $request->description,
                'main_measurement_unit_id' => $request->main_measurement_unit_id,
                'sub_measurement_unit_id' => $request->sub_measurement_unit_id,
                'count_unit' => $request->count_unit,
                'sell_app' => $request->sell_app,
            ]);

            $product->selling_method()->sync( $request->selling_method);
            foreach ($request->selling_method as $item)
            {
                $ProductPricing = ProductPricing::where([
                    ['product_id', $product->id],
                    ['selling_method_id', $item],
                ])->get();
                if (count($ProductPricing) == 0)
                {
                    ProductPricing::create([
                        'product_id' => $product->id,
                        'selling_method_id' => $item,
                        'measurement_unit_id' => $request->main_measurement_unit_id
                    ]);
                    if ($request['count_unit'] > 1)
                    {
                        ProductPricing::create([
                            'product_id' => $product->id,
                            'selling_method_id' => $item,
                            'measurement_unit_id' => $request->sub_measurement_unit_id
                        ]);
                    }
                }
                else
                {
                    foreach ($ProductPricing as $index=>$value)
                    {
                        if ($index == 0)
                        {
                            $value->update([
                                'measurement_unit_id' => $request->main_measurement_unit_id
                            ]);
                        }
                        else
                        {
                            if ($request['count_unit'] > 1)
                            {
                                $value->update([
                                    'measurement_unit_id' => $request->sub_measurement_unit_id,
                                ]);
                            }
                            else
                            {
                                $value->update([
                                    'measurement_unit_id' => $request->sub_measurement_unit_id,
                                    'active' => 0,
                                ]);
                            }
                        }
                    }
                }
            }
            DB::commit();
            return $this->sendResponse([],'Data exited successfully');
        }
        catch (\Exception $e)
        {
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
        try
        {
            $spareAccessor = SpareAccessor::find($id);
            $product = Product::with(['media:mediable_id,file_name,id'])->where('accessor_id',$id)->first();
            if ($spareAccessor && $product)
            {
                $spareAccessor->delete();
                $product->delete();
                return $this->sendResponse([], 'Deleted successfully');
            }
            else
            {
                return $this->sendError('ID is not exist');
            }
        }
        catch (\Exception $e)
        {
            return $this->sendError('An error occurred in the system');
        }
    }
}
