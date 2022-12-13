<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LeadComment;
use App\Models\User;
use App\Notifications\Admin\AddCommentInLeadNotification;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LeadCommentController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function create()
    {
        //
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
                'comment' => 'required|string|min:3',
                'lead_id' => 'required|integer|exists:leads,id',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['comment','lead_id']);
            $data['employee_id'] = auth()->user()->employee->id;

            LeadComment::create($data);

            User::whereAuthId(1)
                ->whereRelation('roles.notify','name','Add Comment In Lead');
                // ->each(function ($admin) use($request){
                //     $admin->notify(new AddCommentInLeadNotification($request));
                // });

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');

        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }

    public function edit($id)
    {
        try
        {
            $lead = LeadComment::find($id);
            return $this->sendResponse(['lead' => $lead], 'Data exited successfully');
        }
        catch (\Exception $e)
        {
            return $this->sendError('An error occurred in the system');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $comments = LeadComment::where('lead_id',$id)->with('employee.user')
        ->when($request->search, function ($q) use ($request) {
            return $q->where('comment','like','%'.$request->search.'%')
            ->orWhereRelation('employee.user','name','like','%'.$request->search.'%');
        })->latest()->paginate(15);

        $crm = $comments[0]->lead->seller_category_id;

        return $this->sendResponse(['comments' => $comments,'crm'=>$crm], 'Data exited successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        try
        {
            $comment = LeadComment::find($id);
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'comment' => 'required|string|min:3',
                'lead_id' => 'required|integer|exists:leads,id',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['comment','lead_id']);
            $comment->update($data);

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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = LeadComment::find($id);
        $comment->delete();
        return $this->sendResponse([], 'Data exited successfully');
    }
}
