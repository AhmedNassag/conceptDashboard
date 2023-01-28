<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ComplaintUser;
use App\Traits\Message;
use Illuminate\Http\Request;

class ComplaintClientController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $complaints = ComplaintUser::with(['user','complaint'])->when($request->search, function ($q) use ($request) {
            return $q->where('notes', 'like', '%' . $request->search . '%')
                ->orWhereRelation('user','name','like','%'.$request->search.'%')
                ->orWhereRelation('user','phone','like','%'.$request->search.'%')
                ->orWhereRelation('complaint','name','like','%'.$request->search.'%');
        })->latest()->paginate(15);

        return $this->sendResponse(['complaints' => $complaints], 'Data exited successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $complaint = ComplaintUser::with(['complaint','user' => function($q){
            $q->with('client');
        }])->findOrFail($id);

        return $this->sendResponse(['complaint' => $complaint], 'Data exited successfully');
    }
}
