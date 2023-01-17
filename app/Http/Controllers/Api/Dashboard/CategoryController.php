<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::where('parent_id',0)
        ->with('media:file_name,mediable_id')
        ->when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);

        return $this->sendResponse(['categories' => $categories], 'Data exited successfully');
    }

    /**
     * get active Category
     */
    public function activeCategory()
    {
        $categories = Category::where([
            ['status', 1],
            ['parent_id', 0],
        ])->get();
        return $this->sendResponse(['categories' => $categories], 'Data exited successfully');
    }

    public function activationCategory($id)
    {
        $department = Category::find($id);

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
                'name' => ['required'],
                'file' => 'required|file|mimes:png,jpg,jpeg',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $category = Category::create(['name' => $request->name,'parent_id' => 0]);

            if($request->hasFile('file')){

                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image = time().'.'. $request->file->getClientOriginalName();

                // picture move
                $request->file->storeAs('category', $image,'general');

                $category->media()->create([
                    'file_name' => $image ,
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

            $category = Category::with('media:file_name,mediable_id')->find($id);

            return $this->sendResponse(['category' => $category], 'Data exited successfully');

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
        $subCategories = Category::where([
            ['parent_id',$id],
            ['status', 1]
        ])->get();
        return $this->sendResponse(['subCategories' => $subCategories], 'Data exited successfully');
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

            $category = Category::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string',
                'file' => 'nullable'.($request->hasFile('file')?'|file|mimes:jpeg,jpg,png':''),
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $category->update(['name' => $request->name,'parent_id' => 0]);

            if($request->hasFile('file')){

                if(File::exists('upload/category/'.$category->media->file_name)){
                    unlink('upload/category/'. $category->media->file_name);
                }
                $category->media->delete();

                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image = time().'.'. $request->file->getClientOriginalName();

                // picture move
                $request->file->storeAs('category', $image,'general');

                $category->media()->create([
                    'file_name' => $image ,
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
            $category = Category::find($id);
            if ($category && count($category->products) == 0 && count($category->children) == 0){

                if(File::exists('upload/category/'.$category->media->file_name)){
                    unlink('upload/category/'. $category->media->file_name);
                }
                $category->media->delete();

                $category->delete();
                return $this->sendResponse([],'Deleted successfully');
            }else{
                return $this->sendError('ID is not exist');
            }

        }catch (\Exception $e){
            return $this->sendError('An error occurred in the system');
        }
    }
}
