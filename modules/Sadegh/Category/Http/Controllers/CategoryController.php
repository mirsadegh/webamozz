<?php

namespace Sadegh\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Sadegh\Category\Models\Category;
use Sadegh\Category\Http\Requests\CategoryRequest;
use Sadegh\Category\Repositories\CategoryRepo;
use Sadegh\Category\Responses\AjaxResponses;


class CategoryController extends Controller
{
    public $repo;
    public function __construct(CategoryRepo $categoryRepo)
    {
        $this->repo = $categoryRepo;
    }

    public function index()
   {
    $categories = $this->repo->all();
    return view('Categories::index',compact('categories'));
   }

   public function store(CategoryRequest $request)
   {
          $this->repo->store($request);
          return back();
   }

   public function edit($catId)
   {
         $category = $this->repo->findById($catId);
         $categories = $this->repo->allExceptById($catId);
       return view('Categories::edit',compact('category','categories'));
   }

   public function update($catId,CategoryRequest $request)
   {
        $this->repo->update($catId,$request);
        return redirect()->route('categories.index');
   }

   public function destroy($catId)
   {
       $this->repo->delete($catId);
       return AjaxResponses::SuccessResponse();
   }

}

