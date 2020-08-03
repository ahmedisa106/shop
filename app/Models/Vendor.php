<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vendor extends Model
{
    use Notifiable;
    protected $table = 'vendors';
    protected $fillable = ['name', 'mobile', 'address', 'email', 'password', 'active', 'logo', 'category_id'];

    protected $hidden = ['category_id'];

    public function scopeActive($value)
    {

        return $value->where('active', 1);
    }

    public function getActive()
    {

        return $this->active == 1 ? 'مفعل' : 'غير مفعل';

    }

    public function getLogoAttribute($value)
    {

        return ($value !== NULL) ? asset('assets/' . $value) : '';

    }

    public function category()
    {
        return $this->belongsTo(MainCategory::class, 'category_id');


    }

}
