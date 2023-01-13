<?php

namespace App\Models;

use App\Models\Market\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function products()
    {
        return $this->morphedByMany(Product::class, 'taggables');
    }
}
