<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\SupplierAccount;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{

    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $suppliers = Supplier::select('id','name_supplier','status','phone_supplier')
        ->when($request->search, function ($q) use ($request) {
            return $q->where('name_supplier', 'like', '%' . $request->search . '%')
                    ->orWhere('phone_supplier', 'like', '%' . $request->search . '%')
                    ->orWhere('id', 'like', '%' . $request->search . '%');
        })->paginate(10);

        return $this->sendResponse(['suppliers' => $suppliers], 'Data exited successfully');
    }

    /**
     * get active Supplier
     */
    public function activeSupplier()
    {
        $suppliers = Supplier::where('status', 1)->get();
        return $this->sendResponse(['suppliers' => $suppliers], 'Data exited successfully');
    }


    public function activationSupplier($id)
    {
        $department = Supplier::find($id);

        if ($department->status == 1)
        {
            $department->update([
                "status" => 0
            ]);
        }else{
            $department->update([
                "status" => 1
            ]);
        }
        return $this->sendResponse([], 'Data exited successfully');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'name_supplier' => ['required','string'],
                'address' => ['required','string'],
                'phone_supplier' => ['required','string'],
                'commercial_record' => ['nullable','string'],
                'tax_card' => ['nullable','string'],
                'name' => ['nullable','string'],
                'phone' => ['nullable','string'],
                'amount' => ['required'],
                'commercialRegister' => 'required|file|mimes:png,jpg,jpeg',
                'taxCard' => 'required|file|mimes:png,jpg,jpeg',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name_supplier','address','phone_supplier','commercial_record','tax_card','name','phone','amount','commercialRegister','taxCard']);

            $supplier = Supplier::create($data);

            SupplierAccount::create([
                'amount'=>$data['amount'],
                'supplier_id'=>$supplier->id
            ]);

            DB::commit();

            if ($request->hasFile('commercialRegister')) {

                $file_size = $request->commercialRegister->getSize();
                $file_type = $request->commercialRegister->getMimeType();
                $image = time() . 2 . '.' . $request->commercialRegister->getClientOriginalName();

                // picture move
                $request->commercialRegister->storeAs('supplier', $image, 'general');

                $supplier->media()->create([
                    'file_name' => asset('upload/supplier/' . $image),
                    'file_size' => $file_size,
                    'file_type' => $file_type,
                    'file_sort' => 2
                ]);
            }

            if ($request->hasFile('taxCard')) {

                $file_size = $request->taxCard->getSize();
                $file_type = $request->taxCard->getMimeType();
                $image = time() . 3 . '.' . $request->taxCard->getClientOriginalName();

                // picture move
                $request->taxCard->storeAs('supplier', $image, 'general');

                $supplier->media()->create([
                    'file_name' => asset('upload/supplier/' . $image),
                    'file_size' => $file_size,
                    'file_type' => $file_type,
                    'file_sort' => 3
                ]);
            }
            return $this->sendResponse([], 'Data exited successfully');

        } catch (\Exception $e) {
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
        try {

            $supplier = Supplier::find($id);
            $account = SupplierAccount::where('supplier_id',$id)->first();

            return $this->sendResponse(['supplier' => $supplier,'account' => $account], 'Data exited successfully');

        } catch (\Exception $e) {

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
        try {

            $supplier = Supplier::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                    'name_supplier' => ['required','string'],
                    'address' => ['required','string'],
                    'phone_supplier' => ['required','string'],
                    'commercial_record' => ['nullable','string'],
                    'tax_card' => ['nullable','string'],
                    'name' => ['nullable','string'],
                    'phone' => ['nullable','string'],
                    'amount' => ['required'],
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name_supplier','address','phone_supplier','commercial_record','tax_card','name','phone','amount']);

            $supplier->update($data);

            $account = SupplierAccount::where('supplier_id',$id)->first();
            $account->update([
                'amount'=>$data['amount'],
            ]);

            DB::commit();
            return $this->sendResponse([],'Data exited successfully');
        }catch (\Exception $e){

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
            $supplier = Supplier::find($id);
            $purchaseProduct = Purchase::whereSupplierId($supplier->id);
            if ($supplier && count($purchaseProduct) > 0){

                $supplier->delete();
                return $this->sendResponse([],'Deleted successfully');
            }else{
                return $this->sendError('ID is not exist');
            }

        }catch (\Exception $e){
            return $this->sendError('An error occurred in the system');
        }

    }
}
