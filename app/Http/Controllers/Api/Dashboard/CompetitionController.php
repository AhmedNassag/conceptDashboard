<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CompetitionController extends Controller
{

    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $competitions = Competition::with('media:file_name,mediable_id')
        ->when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('count', 'like', '%' . $request->search . '%')
                ->orWhere('days', 'like', '%' . $request->search . '%')
                ->orWhere('money', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);

        return $this->sendResponse(['competitions' => $competitions], 'Data exited successfully');
    }



    /**
     * get active Category
     */
    public function activeCompetition()
    {
        $competitions = Competition::where([['status', 1]])->get();
        return $this->sendResponse(['competitions' => $competitions], 'Data exited successfully');
    }



    public function activationCompetition($id)
    {
        $department = Competition::find($id);

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
                'name'  => ['required'],
                'count' => ['required'],
                'days'  => ['required'],
                'type'  => ['required'],
                'money' => ['nullable'],
                'file'  => 'required_if:type:,==,prize' . ($request->hasFile('file') ? '|file|mimes:jpeg,jpg,png' : ''),
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $competition = Competition::create([
                'name' => $request->name,
                'count' => $request->count,
                'days' => $request->days,
                'type' => $request->type,
                'money' => $request->type == 'finance' ? $request->money : Null,
            ]);

            if($request->type == 'prize')
            {
                if($request->hasFile('file'))
                {
                    $file_size = $request->file->getSize();
                    $file_type = $request->file->getMimeType();
                    $image = time().'.'. $request->file->getClientOriginalName();

                    // picture move
                    $request->file->storeAs('competition', $image,'general');

                    $competition->media()->create([
                        'file_name' => $image ,
                        'file_size' => $file_size,
                        'file_type' => $file_type,
                        'file_sort' => 1
                    ]);

                }
            }

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

            $competition = Competition::with('media:file_name,mediable_id')->find($id);
            return $this->sendResponse(['competition' => $competition], 'Data exited successfully');

        } catch (\Exception $e) {

            return $this->sendError('An error occurred in the system');

        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $competitions = Competition::where([
            ['id',$id],
            ['status', 1]
        ])->get();
        return $this->sendResponse(['competitions' => $competitions], 'Data exited successfully');
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
        // try {

            $competition = Competition::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'name'  => ['required'],
                'count' => ['required'],
                'days'  => ['required'],
                'type'  => ['required'],
                'money' => ['nullable'],
                'file'  => 'required_if:type:,==,prize' . ($request->hasFile('file') ? '|file|mimes:jpeg,jpg,png' : ''),
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $competition->update([
                'name' => $request->name,
                'count' => $request->count,
                'days' => $request->days,
                'money' => $request->type == 'finance' ? $request->money : Null,
            ]);

            if($request->type == 'prize')
            {
                if($request->hasFile('file')){

                    if(File::exists('upload/competition/'.$competition->media->file_name)){
                        unlink('upload/competition/'. $competition->media->file_name);
                    }
                    $competition->media->delete();

                    $file_size = $request->file->getSize();
                    $file_type = $request->file->getMimeType();
                    $image = time().'.'. $request->file->getClientOriginalName();

                    // picture move
                    $request->file->storeAs('competition', $image,'general');

                    $competition->media()->create([
                        'file_name' => $image ,
                        'file_size' => $file_size,
                        'file_type' => $file_type,
                        'file_sort' => 1
                    ]);

                }
            }

            DB::commit();
            return $this->sendResponse([],'Data exited successfully');

        // }catch (\Exception $e){

        //     DB::rollBack();
        //     return $this->sendError('An error occurred in the system');

        // }
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
            $competition = Competition::find($id);
            if ($competition)
            {

                if($competition->media)
                {
                    if (File::exists('upload/competition/' . $competition->media->file_name)) {
                        unlink('upload/competition/' . $competition->media->file_name);
                        $competition->media->delete();
                    }
                }

                $competition->delete();
                return $this->sendResponse([],'Deleted successfully');
            }else{
                return $this->sendError('ID is not exist');
            }

        }catch (\Exception $e){
            return $this->sendError('An error occurred in the system');
        }
    }
}
