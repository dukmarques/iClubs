<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Signatures extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'club_id',
        'status',
    ];
}
