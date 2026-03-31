<?php

namespace App\Models;

use Faker\Guesser\Name;
use Illuminate\Database\Eloquent\Model;

class pointsModel extends Model
{
    protected $table = 'points';
    protected $guarded = ['id'];
}
