<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SuggestionUser;
use App\Traits\Message;
use Illuminate\Http\Request;

class SuggestionClientController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $suggestions = SuggestionUser::with(['user','suggestion'])->when($request->search, function ($q) use ($request) {
            return $q->where('notes', 'like', '%' . $request->search . '%')
                ->orWhereRelation('user','name','like','%'.$request->search.'%')
                ->orWhereRelation('user','phone','like','%'.$request->search.'%')
                ->orWhereRelation('suggestion','name','like','%'.$request->search.'%');
        })->latest()->paginate(15);

        return $this->sendResponse(['suggestions' => $suggestions], 'Data exited successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $suggestion = SuggestionUser::with(['suggestion','user' => function($q){
            $q->with('client');
        }])->findOrFail($id);

        return $this->sendResponse(['suggestion' => $suggestion], 'Data exited successfully');
    }
}
