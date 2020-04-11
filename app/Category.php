<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ["category_id", "name"];

    public function sellables()
    {
        return $this->hasMany(Sellable::class);
    }

    public function products()
    {
        return $this->sellables()->has(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
