<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\Message;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use Message;

    /**
     * get Category Home
     */

    public function categoryHome()
    {
        $categories = Category::where([
            ['status', 1],
            ['parent_id', 0],
        ])->inRandomOrder()->limit(6)->get();
        return $this->sendResponse(['categories' => $categories], trans('message.messageSuccessfully'));
    }

    /**
     * get Category
     */

    public function category()
    {
        $categories = Category::where([
            ['status', 1],
            ['parent_id', 0],
        ])->get();
        return $this->sendResponse(['categories' => $categories], trans('message.messageSuccessfully'));
    }

    /**
     * get sub Category
     */

    public function subCategory($id)
    {
        $subCategories = Category::where([
            ['parent_id',$id],
            ['status', 1]
        ])->get();
        return $this->sendResponse(['subCategories' => $subCategories], trans('message.messageSuccessfully'));
    }

    /**
     * get all sub Category
     */
    public function allSubCategory()
    {
        $allSubCategory = Category::where([
            ['parent_id','!=',0],
            ['status', 1]
        ])->get();
        return $this->sendResponse(['allSubCategory' => $allSubCategory], trans('message.messageSuccessfully'));
    }
}
