<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sellable extends Model
{
    protected $fillable = ["label", "price", "category_id", "vat_id"];

    public function sellable()
    {
        $this->morphTo();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot(["price", "vat", "label", "quantity"]);
    }

    public function vat()
    {
        return $this->belongsTo(Vat::class);
    }
}
