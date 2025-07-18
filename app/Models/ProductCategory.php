<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class ProductCategory extends Model
{
    /** @use HasFactory<\Database\Factories\ProductCategoryFactory> */
    use HasFactory;

    public function products() : HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function types() : MorphToMany
    {
        return $this->morphToMany(ProductType::class, 'type_assignments');
    }
}
