<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\LogsActivity;

class Review extends Model
{
    use LogsActivity;
    protected $table = 'reviews';
}
