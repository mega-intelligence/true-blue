<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = ["name"];


    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot("quantity");
    }
}
