<?php

namespace App\Models;

use App\Models\MainCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SubCategory extends Model
{
    use HasFactory;

    protected $table = 'sub_categories';

    protected $fillable = [
        'translation_lang','parent_id','photo','translation_of', 'name','slug','active','created_at', 'updated_at',
    ];


    public function scopeActive($query) {

        return $query->where('active',1);
    }
    public function scopeSelection($query)
    {

        return $query -> select('id','translation_lang','parent_id','name', 'slug', 'photo', 'active','translation_of');
    }

    public function getPhotoAttribute($vale) {

        return ($vale !== null) ? asset('assets/'.$vale) : "";

    }

    public function getActive()
    {
        return $this->active == 1 ? 'مفعل' : 'غير مفعل';
    }

    //get main category of subcategory
    public  function mainCategory(){
        return $this -> belongsTo('App\Models\MainCategory','category_id','id');
    }


}
