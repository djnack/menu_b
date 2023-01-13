<?php

namespace App\Models;

use App\Models\Market\Marketplace;
use App\Models\Market\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public function marketplace()
    {
        return $this->morphedByMany(Marketplace::class, 'imageables');
    }

    public function users()
    {
        return $this->morphedByMany(User::class, 'imageables');
    }
    public function products()
    {
        return $this->morphedByMany(Product::class, 'imageables');
    }

}
