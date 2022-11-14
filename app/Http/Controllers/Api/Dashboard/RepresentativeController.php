<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Representative;
use App\Models\User;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class RepresentativeController extends Controller
{
    use Message;

    /**
     * change Password
     */

    public function changePassword(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // Validator request
            $v = Validator::make($request->all(), [
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            Representative::find($id)->user->update(["password" => $request->password]);

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }


    }

    /**
     * get active Representative
     */
    public function activeRepresentative()
    {
        $representatives = User::where('status',1)->whereAuthId(1)->whereJsonContains('role_name','representative')->get();
        return $this->sendResponse(['representatives' => $representatives], 'Data exited successfully');
    }

    /**
     * activation Representative
     */
    public function activationRepresentative($id)
    {
        $representative = Representative::find($id)->user;

        if ($representative->status == 1) {
            $representative->update([
                "status" => 0
            ]);
        } else {
            $representative->update([
                "status" => 1
            ]);
        }
        return $this->sendResponse([], 'Data exited successfully');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $representatives = Representative::with('user:id,name,email,phone,status','areas')
            ->where(function ($q) use($request){
                $q->when($request->search,function ($q) use($request){
                    return $q->OrWhere('hiring_date','like','%'.$request->search.'%')
                        ->orWhereRelation('user','email','like','%'.$request->search.'%')
                        ->orWhereRelation('user','name','like','%'.$request->search.'%')
                        ->orWhereRelation('user','phone','like','%'.$request->search.'%');
                });
            })->latest()->paginate(10);

        return $this->sendResponse(['representatives' => $representatives], 'Data exited successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $areas = Area::all();
        return $this->sendResponse(['areas' => $areas],'Data exited successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8',
                'confirmtion' => 'required|same:password',
                'phone' => 'required|string|unique:users,phone',
                'address' => 'required|string|min:3',
                'National_ID' => 'required|string|min:8|unique:employees,National_ID',
                'birth_date' => 'required|date',
                'hiring_date' => 'required|date',
                'salary' => 'required',
                'file' => 'image|mimes:jpeg,jpg,png,webp|max:5048',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            // start create user
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => $request->password,
                "auth_id" => 1,
                'role_name' => ['representative'],
                "status" => 1,
                'phone' => $request->phone
            ]);

            $user->representative()->create([
                'address' => $request->address,
                'National_ID' => $request->National_ID,
                'birth_date' => $request->birth_date,
                'hiring_date' => $request->hiring_date,
                'salary' => $request->salary,
            ]);

            $areas = explode(',',$request->area_id);
            $user->representative->areas()->attach($areas);

            $file_size = $request->file->getSize();
            $file_type = $request->file->getMimeType();
            $image = time() . '.' . $request->file->getClientOriginalName();

            // picture move
            $request->file->storeAs('representative_profile', $image, 'general');

            $user->media()->create([
                'file_name' => $image,
                'file_size' => $file_size,
                'file_type' => $file_type,
                'file_sort' => 1
            ]);

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }

    }

    public function edit($id)
    {
        try {

            $representative = Representative::with('user:id,name,email,phone,status,role_name','areas')->find($id)->makeVisible('translations');
            $media = $representative->user->media->file_name;
            $areas = Area::all();

            return $this->sendResponse(['representative' => $representative, 'media' => $media,'areas'=>$areas], 'Data exited successfully');

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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Representative $representative)
    {
        try {

            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users,email' . ($representative->user_id ? ",$representative->user_id" : ''),
                'phone' => 'required|string|unique:users,phone' . ($representative->user_id ? ",$representative->user_id" : ''),
                'address' => 'required|string|min:3',
                'National_ID' => 'required|string|min:8|unique:employees,National_ID' . ($representative->id ? ",$representative->id" : ''),
                'birth_date' => 'required|date',
                'hiring_date' => 'required|date',
                'salary' => 'required',
                'file' => 'nullable' . ($request->hasFile('file') ? '|mimes:jpeg,jpg,png,webp|max:5048' : ''),
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $representative->user()->update([
                "name" => $request->name,
                "email" => $request->email,
                'phone' => $request->phone
            ]);

            $representative->update([
                'address' => $request->address,
                'National_ID' => $request->National_ID,
                'birth_date' => $request->birth_date,
                'hiring_date' => $request->hiring_date,
                'salary' => $request->salary,
            ]);
            $areas = explode(',',$request->area_id);
            $representative->areas()->sync($areas);
            if ($request->hasFile('file')) {
                if (File::exists('upload/representative_profile/' . $representative->user->media->file_name)) {
                    unlink('upload/representative_profile/' . $representative->user->media->file_name);
                }
                $representative->user->media->delete();
                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image = time() . '.' . $request->file->getClientOriginalName();

                // picture move
                $request->file->storeAs('representative_profile', $image, 'general');

                $representative->user->media()->create([
                    'file_name' => $image,
                    'file_size' => $file_size,
                    'file_type' => $file_type,
                    'file_sort' => 1
                ]);
            }

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');
        } catch (\Exception $e) {

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
       //
    }
}
