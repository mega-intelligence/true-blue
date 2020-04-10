<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sellable extends Model
{
    protected $fillable = ["label", "price"];

    public function sellable()
    {
        $this->morphTo();
    }
}
