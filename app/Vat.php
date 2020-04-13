<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vat extends Model
{
    protected $fillable = ["value", "is_default"];

    public $timestamps = false;

    //

    public function sellables()
    {
        return $this->hasMany(Sellable::class);
    }
}
