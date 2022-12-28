<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\problemLead;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProblemLeadController extends Controller
{
    use Message;
    // public $selling_method;

    // public function __construct()
    // {
    //     $this->selling_method = auth()->user()->client->selling_method_id;
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
                'name' => 'required|string|min:3',
                'address' => 'required|string|min:3',
                'description' => 'required|string|min:3',
                'phone' => 'required|string',
                // 'seller_category_id' => 'required|integer|exists:problem_seller_categories,id',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors(), 401);
            }

            // $data = $request->only(['name', 'address', 'description', 'phone', 'seller_category_id']);

            problemLead::create([
                'name'               => $request->name,
                'address'            => $request->address,
                'description'        => $request->description,
                'phone'              => $request->phone,
                'seller_category_id' => 1,
            ]);

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
