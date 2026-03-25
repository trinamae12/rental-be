<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Room extends Model
{
    use HasApiTokens, SoftDeletes;

    protected $fillable = ['room_name', 'room_description', 'room_month_price'];
}
