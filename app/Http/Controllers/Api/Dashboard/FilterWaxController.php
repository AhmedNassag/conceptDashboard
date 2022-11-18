<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\FilterWax;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FilterWaxController extends Controller
{

    use Message;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filterWaxes = FilterWax::
        when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%');

        })
        ->latest()->paginate(10);

        return $this->sendResponse(['filterWaxes' => $filterWaxes], 'Data exited successfully');
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
                'name' => ['required','string'],
                'price' => ['required'],
                'model' => ['required','string']
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name','price','model']);

            $filterWax = FilterWax::create($data);

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {

            $filterWax = FilterWax::find($id);

            return $this->sendResponse(['filterWax' => $filterWax], 'Data exited successfully');

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

            $filterMax = FilterWax::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => ['required','string'],
                'price' => ['required'],
                'model' => ['required','string']
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','price','model']);

            $filterMax->update($data);

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
            $filterMax = FilterWax::find($id);
            if ($filterMax) {

                $filterMax->delete();
                return $this->sendResponse([], 'Deleted successfully');
            } else {
                return $this->sendError('ID is not exist');
            }

        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
    }
}
