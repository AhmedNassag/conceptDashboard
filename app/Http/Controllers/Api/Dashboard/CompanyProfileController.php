<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CompanyProfileController extends Controller
{

    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companyProfiles = CompanyProfile::with('media:file_name,mediable_id')
        ->when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);


        return $this->sendResponse(['companyProfiles' => $companyProfiles], 'Data exited successfully');
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
                'address' => ['required','string'],
                'phone' => ['required','string'],
                'commercial_record' => ['nullable','string'],
                'tax_card' => ['nullable','string'],
                'file' => 'required|file|mimes:png,jpg,jpeg',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','address','phone','commercial_record','tax_card','commercialRecord'/*,'taxCard'*/]);
            $companyProfile = CompanyProfile::create($data);

            if ($request->hasFile('file')) {

                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image = time() . '.' . $request->file->getClientOriginalName();

                // picture move
                $request->file->storeAs('companyProfile', $image, 'general');

                $companyProfile->media()->create([
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $companyProfile = CompanyProfile::with('media:file_name,mediable_id')->find($id);
            return $this->sendResponse(['companyProfile' => $companyProfile], 'Data exited successfully');

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

            $companyProfile = CompanyProfile::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => ['required', 'string'],
                'address' => ['required', 'string'],
                'phone' => ['required', 'string'],
                'commercial_record' => ['nullable', 'string'],
                'tax_card' => ['nullable', 'string'],
                'file' => 'nullable' . ($request->hasFile('file') ? '|file|mimes:jpeg,jpg,png' : ''),
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','address','phone','commercial_record','tax_card']);

            $companyProfile->update($data);

            if ($request->hasFile('file')) {

                if (File::exists('upload/companyProfile/' . $companyProfile->media->file_name)) {
                    unlink('upload/companyProfile/' . $companyProfile->media->file_name);
                }
                $companyProfile->media->delete();

                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image = time() . '.' . $request->file->getClientOriginalName();

                // picture move
                $request->file->storeAs('companyProfile', $image, 'general');

                $companyProfile->media()->create([
                    'file_name' => $image,
                    'file_size' => $file_size,
                    'file_type' => $file_type,
                    'file_sort' => 1
                ]);
            }

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
            $companyProfile = CompanyProfile::find($id);
            if ($companyProfile){

                if (File::exists('upload/companyProfile/' . $companyProfile->media->file_name)) {
                    unlink('upload/companyProfile/' . $companyProfile->media->file_name);
                }
                $companyProfile->media->delete();

                $companyProfile->delete();
                return $this->sendResponse([],'Deleted successfully');

            }else{
                return $this->sendError('ID is not exist');
            }

        }catch (\Exception $e){
            return $this->sendError('An error occurred in the system');
        }

    }
}
