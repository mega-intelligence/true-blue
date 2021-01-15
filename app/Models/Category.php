<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Category extends Model implements Sortable
{
    public const ROOT_CATEGORY_VALUE = -1;

    use SortableTrait;

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
        return $this->hasMany(Category::class)->ordered();
    }

    public function buildSortQuery()
    {
        return static::query()->where('category_id', $this->category_id);
    }

    public function getLowestOrderNumber(): int
    {
        return (int)$this->buildSortQuery()->min($this->determineOrderColumnName());
    }

    public function isLastInOrder(): bool
    {
        $orderColumnName = $this->determineOrderColumnName();

        return $this->$orderColumnName === $this->getHighestOrderNumber();
    }

    public function isFirstInOrder(): bool
    {
        $orderColumnName = $this->determineOrderColumnName();

        return $this->$orderColumnName === $this->getLowestOrderNumber();
    }

}
