<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    protected $table = 'main_categories';
    protected $fillable = ['translation_lang', 'translation_of', 'name', 'slug', 'photo', 'active'];

    public function scopeActive($query)
    {

        return $query->where('active', 1);

    }

    public function scopeSelection($query)
    {

        return $query->select('id', 'translation_lang', 'translation_of', 'name', 'slug', 'photo', 'active');

    }

    public function getphotoAttribute($value)
    {

        return ($value !== NULL) ? asset('assets/' . $value) : '';
    }

    public function getActive()
    {

        return $this->active == 1 ? 'مفعل' : 'غير مفعل';

    }

    public function categories()
    {

        return $this->hasMany(self::class, 'translation_of'); // علاقه في نفس الجدول
    }

    public function vendors()
    {
        return $this->hasMany(Vendor::class, 'category_id');


    }

}
