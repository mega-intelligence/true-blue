<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ["quantity"];

    public function sellable()
    {
        return $this->morphOne(Sellable::class, 'sellable');
    }

    public function category()
    {
        return $this->sellable->category();
    }
}
