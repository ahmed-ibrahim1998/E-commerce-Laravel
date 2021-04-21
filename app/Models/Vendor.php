<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vendor extends Model
{
    use HasFactory;
    use Notifiable;  // should be import this to send mail to vendors

    protected $table = 'vendors';

    protected $fillable = [
        'name', 'logo', 'mobile','password','address','email','category_id','active','created_at', 'updated_at',
    ];

    protected $hidden = ['category_id','password'];  // beacouse whene i make selected any thing any thing they dont show with me

    public function scopeActive($query) {

        return  $query-> where('active',1);

    }

    public function getLogoAttribute($vale) {

        return ($vale !== null) ? asset('assets/'.$vale) : "";

    }

    public function scopeSelection($query) {

        return $query -> select('id','category_id','latitude','longitude','name','active','email','address','logo','mobile');

    }

    public function category() {

        return $this ->belongsTo('App\Models\MainCategory','category_id','id');
    }

    public function getActive()
    {
        return $this->active == 1 ? 'مفعل' : 'غير مفعل';
    }

    public function setPasswordAttribute($password) {
        if (!empty($password))
             $this-> attributes['password'] = bcrypt($password);
    }

}
