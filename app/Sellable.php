<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sellable extends Model
{
    protected $fillable = ["label", "price", "category_id"];

    public function sellable()
    {
        $this->morphTo();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
