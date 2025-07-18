<?php

namespace App\Models;

use App\Models\ProductColor;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Product extends Model
{
    use HasFactory;
    
    public function productColor() : BelongsTo
    {
        return $this->belongsTo(ProductColor::class);
    }

    public function productCategory() : BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function types() : MorphToMany
    {
        return $this->morphToMany(ProductType::class, 'type_assignments', 'type_assignments', 'type_assignments_id', 'id');
    }
}
