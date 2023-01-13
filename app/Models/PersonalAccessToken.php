<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\PersonalAccessToken as PAT;

class PersonalAccessToken extends PAT
{
    use SoftDeletes;
}