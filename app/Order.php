<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /*
     * An order should contain the following information:
     * total amount
     * delivered_at
     * paid_at
     * is_draft draft order can export a quote
     */

    public function sellables()
    {
        return $this->belongsToMany(Sellable::class)->withPivot(["price", "vat", "label", "quantity"]);
    }
}

