<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [""];

    public function sellable()
    {
        return $this->morphOne(Sellable::class, 'sellable');
    }

    public function category()
    {
        return $this->sellable->category();
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class)->withPivot("quantity");
    }
}
