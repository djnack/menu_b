<?php

namespace App\Models;

use App\Models\Market\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function products()
    {
        return $this->morphedByMany(Product::class, 'categoryables');
    }
}
