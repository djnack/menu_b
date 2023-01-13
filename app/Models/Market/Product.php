<?php

namespace App\Models\Market;

use App\Models\Categories;
use App\Models\Image;
use App\Models\Tags;
use App\Models\Translate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function marketplace()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function translate()
    {
        return $this->morphToMany(Translate::class, 'translatables');
    }

    public function image()
    {
        return $this->morphToMany(Image::class, 'imageables')->withPivot('detail');
    }

    public function tags()
    {
        return $this->morphToMany(Tags::class, 'taggables');
    }

    public function categories()
    {
        return $this->morphToMany(Categories::class, 'categoryables');
    }
}
