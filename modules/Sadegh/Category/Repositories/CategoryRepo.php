<?php

namespace Sadegh\Category\Repositories;


use Sadegh\Category\Models\Category;

class CategoryRepo
{
    public function all()
    {
        return Category::all();
     }

    public function allExceptById($id)
    {
        return  $this->all()->filter(function ($item)use ($id){
               return $item->id != $id;
        });

     }

    public function findById($catId)
    {
        return Category::find($catId);
     }

    public function store($values)
    {
       return Category::create([
            'title'      => $values->title,
            'slug'      => $values->slug,
            'parent_id' => $values->parent_id,
        ]);
     }

    public function update($id,$values)
    {
         Category::where('id',$id)->update([
             'title'      => $values->title,
             'slug'      => $values->slug,
             'parent_id' => $values->parent_id,
         ]);
     }

    public function delete($id)
    {
        Category::where('id',$id)->delete();
     }
}
