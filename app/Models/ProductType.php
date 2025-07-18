<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class ProductType extends Model
{
    /** @use HasFactory<\Database\Factories\ProductTypeFactory> */
    use HasFactory;

    public function products() : MorphToMany
    {
        return $this->morphedByMany(Product::class, 'type_assignments');
    }

    public function categories() : MorphToMany
    {
        return $this->morphedToMany(ProductCategory::class, 'type_assignments');
    }
}
